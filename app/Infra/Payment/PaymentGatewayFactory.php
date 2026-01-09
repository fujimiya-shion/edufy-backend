<?php
namespace App\Infra\Payment;

class PaymentGatewayFactory
{
    public function __construct(
        private StripePaymentGateway $stripe,
    ) {}

    public function make(string $provider): PaymentGatewayInterface
    {
        return match ($provider) {
            'stripe' => $this->stripe,
            default => throw new \InvalidArgumentException("Unsupported provider: $provider"),
        };
    }
}
