<?php

namespace App\Providers;

use App\Models\Payment;
use App\Observers\PaymentObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Payment::observe(PaymentObserver::class);
        Paginator::useBootstrapFive();


    }
}
