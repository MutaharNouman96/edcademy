<?php

namespace App\Listeners;

use App\Events\EducatorRegistered;
use App\Mail\EducatorWelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEducatorWelcomeEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\EducatorRegistered  $event
     * @return void
     */
    public function handle(EducatorRegistered $event)
    {
        //
        $educator = $event->educator;

        Mail::to($educator->email)->queue(
            new EducatorWelcomeMail(
                $educator->name,
                route('educator.dashboard') // replace with actual route name
            )
        );
    }
}
