<?php

namespace App\Providers;

use App\Contracts\Cart;
use App\Contracts\PaymentGateway;
use App\Services\Cart\SessionCart;
use App\Services\Payment\DemoPaymentGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Cart::class, SessionCart::class);
        $this->app->bind(PaymentGateway::class, DemoPaymentGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
