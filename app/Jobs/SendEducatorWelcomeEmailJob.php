<?php

namespace App\Jobs;

use App\Mail\EducatorWelcomeMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Sends the educator their welcome / "verification pending" email after signup.
 */
class SendEducatorWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(public int $userId)
    {
    }

    public function handle(): void
    {
        $user = User::find($this->userId);

        if (!$user) {
            Log::warning('SendEducatorWelcomeEmailJob: user not found', ['user' => $this->userId]);

            return;
        }

        $dashboardLink = route('educator.dashboard');

        // sendNow ensures the mail is delivered within this job rather than being
        // re-queued (EducatorWelcomeMail itself implements ShouldQueue).
        Mail::to($user->email)->sendNow(
            new EducatorWelcomeMail($user->full_name, $dashboardLink)
        );
    }
}
