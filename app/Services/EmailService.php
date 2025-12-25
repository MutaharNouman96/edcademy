<?php

namespace App\Services;

use App\Jobs\SendGenericEmailJob;
use Illuminate\Mail\Mailable;

class EmailService
{
    /**
     * Dispatch any email to queue
     */
    public static function send(
        string $to,
        Mailable $mailable,
        ?string $queue = null,
        int $delaySeconds = 0
    ): void {
        $job = new SendGenericEmailJob($to, $mailable);

        if ($queue) {
            $job->onQueue($queue);
        }

        if ($delaySeconds > 0) {
            $job->delay(now()->addSeconds($delaySeconds));
        }

        dispatch($job);
    }
}
