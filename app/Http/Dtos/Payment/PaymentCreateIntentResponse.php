<?php

namespace App\Http\Dtos\Payment;

final class PaymentCreateIntentResponse
{
    public function __construct(
        public string $provider,
        public string $payment_intent_id,
        public string $client_secret,
        public int $amount,
        public string $currency,
        public string $status,
    ) {}

    public static function fromStripe(\Stripe\PaymentIntent $pi): self
    {
        return new self(
            provider: 'stripe',
            payment_intent_id: $pi->id,
            client_secret: $pi->client_secret,
            amount: $pi->amount,
            currency: $pi->currency,
            status: $pi->status,
        );
    }

    public function toArray(): array
    {
        return [
            'provider' => $this->provider,
            'payment_intent_id' => $this->payment_intent_id,
            'client_secret' => $this->client_secret,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
        ];
    }
}
