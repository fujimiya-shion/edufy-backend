<?php

namespace App\Providers;

use App\Infra\Payment\PaymentGatewayFactory;
use App\Infra\Payment\StripePaymentGateway;
use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;

class PaymentGatewayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->instance(StripeClient::class, new StripeClient(config("services.stripe.secret")));
        $this->app->singleton(StripePaymentGateway::class);
        $this->app->singleton(PaymentGatewayFactory::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
