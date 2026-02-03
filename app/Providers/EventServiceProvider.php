<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use Illuminate\Auth\Events\Login;
use App\Services\OrderService;
use App\Services\EmailService;
use App\Services\ActivityNotificationService;
use App\Mail\LoginNotificationMail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\EducatorRegistered::class => [
            \App\Listeners\SendEducatorWelcomeEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //

        Event::listen(Login::class, function (Login $event) {
            app(OrderService::class)->migrateSessionCartToUser();

            $user = $event->user;

            // Log login activity
            ActivityNotificationService::logAndNotify(
                $user,
                'login',
                'User',
                $user->id,
                $user->full_name,
                null,
                [
                    'login_at' => now(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'role' => $user->role
                ],
                "Logged into Ed-Cademy account",
                [
                    'device_info' => request()->userAgent(),
                    'ip_address' => request()->ip()
                ]
            );

            // Send login notification email
            try {
                $ipAddress = request()->ip();
                $userAgent = request()->userAgent();

                // Get location info (you might want to use a geolocation service)
                $location = null; // Could be enhanced with IP geolocation service

                EmailService::send(
                    $user->email,
                    new LoginNotificationMail($user, $ipAddress, $userAgent, $location),
                    'emails'
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send login notification email: ' . $e->getMessage());
            }
        });

        Event::listen(Registered::class, function (Registered $event) {
            app(OrderService::class)->migrateSessionCartToUser();
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
