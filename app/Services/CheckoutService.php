<?php

namespace App\Services;

use App\Jobs\ReleaseEducatorPayoutJob;
use App\Mail\OrderInvoiceMail;
use App\Mail\StudentCourseEnrollmentMail;
use App\Models\Course;
use App\Models\Earning;
use App\Models\EducatorPayment;
use App\Models\EducatorPayout;
use App\Models\Lesson;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserPurchasedItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Shared post-payment fulfillment for cart checkout orders.
 * Gateway controllers verify payment with Stripe/PayPal/etc., then call
 * completePaidOrder() to grant access, record educator payments, and send emails.
 */
class CheckoutService
{
    /**
     * Fulfill an order after the payment gateway has confirmed payment.
     */
    public function completePaidOrder(
        Order $order,
        string $transactionId,
        string $paymentMethod,
        mixed $paymentDetails = null,
        string $payoutProcessor = 'stripe',
    ): Order {
        $order->loadMissing(['items', 'user']);

        if (in_array($order->status, ['paid', 'completed'], true)) {
            return $order;
        }

        $payoutIds = [];

        $order = DB::transaction(function () use ($order, $transactionId, $paymentMethod, $paymentDetails, &$payoutIds) {
            $order->update([
                'status' => 'paid',
                'transaction_id' => $transactionId,
                'payment_details' => $this->encodePaymentDetails($paymentDetails),
                'payment_method' => $paymentMethod,
                'is_active' => true,
            ]);

            $paymentId = $order->id . Str::uuid();

            foreach ($order->items as $item) {
                $itemEducatorId = $this->resolveEducatorId($item);

                if (! $itemEducatorId) {
                    continue;
                }

                $commissionRate = optional(User::find($itemEducatorId))->commissionRate()
                    ?? User::DEFAULT_COMMISSION_RATE;
                $commission = money($item->total * $commissionRate / 100);

                UserPurchasedItem::firstOrCreate(
                    [
                        'user_id' => $order->user_id,
                        'purchasable_id' => $item->item_id,
                        'purchasable_type' => $item->model,
                    ],
                    ['active' => true]
                );

                // $educatorPayment = EducatorPayment::firstOrCreate(
                //     [
                //         'educator_id' => $itemEducatorId,
                //         'order_id' => $order->id,
                //         'order_item_id' => $item->id,
                //     ],
                //     [
                //         'gross_amount' => $item->total,
                //         'currency' => setting('currency', 'USD'),
                //         'platform_commission' => $commission,
                //         'net_amount' => $item->total - $commission,
                //         'payment_id' => $paymentId,
                //         'status' => 'completed',
                //     ]
                // );

                // $payout = EducatorPayout::firstOrCreate(
                //     [
                //         'payment_id' => $educatorPayment->id,
                //         'educator_id' => $itemEducatorId,
                //     ],
                //     [
                //         'amount' => $educatorPayment->net_amount,
                //         'status' => 'pending',
                //         'description' => "Payout for {$item->model} #{$item->item_id} (Order #{$order->id})",
                //     ]
                // );
                // $payoutIds[] = $payout->id;

                // Earning::firstOrCreate(
                //     [
                //         'educator_id' => $itemEducatorId,
                //         'payment_id' => $educatorPayment->id,
                //     ],
                //     [
                //         'gross_amount' => $item->total,
                //         'source_type' => 'order',
                //         'platform_commission' => $commission,
                //         'net_amount' => $item->total - $commission,
                //         'currency' => setting('currency', 'USD'),
                //         'status' => 'approved',
                //         'description' => "Earning generated from payment #{$paymentId}",
                //         'earned_at' => now(),
                //     ]
                // );

                //updated method , add each payment to Payment model
                Payment::firstOrCreate([
                    'educator_id' => $itemEducatorId,
                    'student_id' => $order->user_id,
                    'course_id' => $item->item_id,
                    'gross_amount' => $item->total,
                    'tax_amount' => 0,
                    'platform_commission' => $commission,
                    'net_amount' => $item->total - $commission,
                    'currency' => setting('currency', 'USD'),
                    'status' => 'approved',
                    'description' => "Payment for {$item->model} #{$item->item_id} (Order #{$order->id})",
                    'earned_at' => now(),
                    
                ]);

               
            }

            return $order->fresh(['items', 'user']);
        });

        $this->sendPostPaymentEmails($order);

        return $order;
    }

    private function resolveEducatorId(OrderItem $item): ?int
    {
        if ($item->model === 'App\Models\Lesson') {
            $lesson = Lesson::with('course')->find($item->item_id);

            return $lesson?->course?->user_id;
        }

        if ($item->model === 'App\Models\Course') {
            return Course::find($item->item_id)?->user_id;
        }

        return null;
    }

    private function encodePaymentDetails(mixed $details): ?string
    {
        if ($details === null) {
            return null;
        }

        if (is_string($details)) {
            return $details;
        }

        if (is_object($details) && method_exists($details, 'toArray')) {
            return json_encode($details->toArray());
        }

        return json_encode($details);
    }

    private function sendPostPaymentEmails(Order $order): void
    {
        EmailService::send(
            $order->user->email,
            new OrderInvoiceMail($order),
            'emails'
        );

        try {
            EmailService::send(
                $order->user->email,
                new StudentCourseEnrollmentMail($order),
                'emails'
            );
        } catch (\Exception $e) {
            Log::error('Failed to send course enrollment email: ' . $e->getMessage());
        }
    }
}
