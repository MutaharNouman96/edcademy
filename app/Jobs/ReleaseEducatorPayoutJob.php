<?php

namespace App\Jobs;

use App\Mail\EducatorPayoutReleasedMail;
use App\Models\EducatorPayout;
use App\Services\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

/**
 * Releases educator payouts a short while after a course purchase.
 *
 * The job is dispatched with a delay (2 minutes) right after the payouts are
 * created during checkout. When it executes it moves the net (post-commission)
 * funds to each educator's connected Stripe account via a Stripe Transfer,
 * stores the Stripe response against the payout, updates its status and then
 * notifies the educator by email.
 */
class ReleaseEducatorPayoutJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    /**
     * @param  array<int, int>  $payoutIds  Ids of the educator_payout rows to release.
     * @param  string  $processedBy  The processor that released the payout (e.g. "stripe").
     */
    public function __construct(
        public array $payoutIds,
        public string $processedBy = 'stripe'
    ) {
    }

    public function handle(): void
    {
        if (empty($this->payoutIds)) {
            return;
        }

        // Only release payouts that are still pending so the job is idempotent
        // and safe to retry.
        $payouts = EducatorPayout::with(['educator', 'payment'])
            ->whereIn('id', $this->payoutIds)
            ->where('status', 'pending')
            ->get();

        if ($payouts->isEmpty()) {
            return;
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        $released = collect();

        foreach ($payouts as $payout) {
            $educator = $payout->educator;

            // The educator cannot be paid until they have completed Stripe
            // Connect onboarding (connected account + payouts enabled).
            if (! $educator || ! $educator->canReceivePayouts()) {
                $payout->update([
                    'status'          => 'failed',
                    'processed_at'    => now(),
                    'processed_by'    => $this->processedBy,
                    'stripe_response' => [
                        'error' => 'Educator has not completed Stripe Connect onboarding.',
                    ],
                ]);

                Log::warning('Educator payout skipped: account not ready', [
                    'payout_id'   => $payout->id,
                    'educator_id' => optional($educator)->id,
                ]);

                continue;
            }

            $currency = strtolower($payout->payment->currency ?? setting('currency', 'USD'));
            $amountInCents = (int) round($payout->amount * 100);

            try {
                // Move the net amount from the platform balance to the educator's
                // connected account. Stripe then pays it out to their IBAN/bank
                // according to the connected account's payout schedule.
                $transfer = $stripe->transfers->create([
                    'amount'           => $amountInCents,
                    'currency'         => $currency,
                    'destination'      => $educator->stripe_connect_id,
                    'transfer_group'   => 'payout_' . $payout->id,
                    'metadata'         => [
                        'educator_payout_id' => $payout->id,
                        'educator_id'        => $educator->id,
                        'payment_id'         => $payout->payment_id,
                    ],
                ]);

                $payout->update([
                    'status'           => 'completed',
                    'processed_at'     => now(),
                    'processed_by'     => $this->processedBy,
                    'stripe_payout_id' => $transfer->id,
                    'stripe_response'  => $transfer->toArray(),
                ]);

                $released->push($payout);
            } catch (ApiErrorException $e) {
                $payout->update([
                    'status'          => 'failed',
                    'processed_at'    => now(),
                    'processed_by'    => $this->processedBy,
                    'stripe_response' => [
                        'error'        => $e->getMessage(),
                        'stripe_code'  => $e->getStripeCode(),
                        'http_status'  => $e->getHttpStatus(),
                    ],
                ]);

                Log::error('Stripe educator payout transfer failed', [
                    'payout_id'   => $payout->id,
                    'educator_id' => $educator->id,
                    'error'       => $e->getMessage(),
                ]);
            }
        }

        // Notify each educator once with a consolidated summary of the payouts
        // that were successfully released.
        $released->groupBy('educator_id')->each(function ($educatorPayouts) {
            $educator = $educatorPayouts->first()->educator;

            if (! $educator || ! $educator->email) {
                return;
            }

            try {
                EmailService::send(
                    $educator->email,
                    new EducatorPayoutReleasedMail($educator, $educatorPayouts->values()),
                    'emails'
                );
            } catch (\Throwable $e) {
                Log::error('Failed to send educator payout released email', [
                    'educator_id' => $educator->id,
                    'error'       => $e->getMessage(),
                ]);
            }
        });
    }
}
