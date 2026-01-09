<?php
namespace App\Infra\Payment;
use App\Http\Dtos\Payment\PaymentCreateIntentRequest;
use App\Http\Dtos\Payment\PaymentCreateIntentResponse;
use App\Infra\Payment\PaymentGatewayInterface;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
class StripePaymentGateway implements PaymentGatewayInterface {
    private StripeClient $client;

    public function __construct(?StripeClient $client = null) {
        $this->client = $client ?? new StripeClient(config("services.stripe.secret"));
    }

    public function createIntent(PaymentCreateIntentRequest $intent): PaymentCreateIntentResponse {
        $amount = $this->toStripeAmount($intent->amount, $intent->currency);
        $currency = strtolower($intent->currency);

        try {
            $pi = $this->client->paymentIntents->create([
                'amount' => $amount,
                'currency' => $currency,
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'order_id' => (string) $intent->orderId,
                    'customer_id' => (string) $intent->customerId,
                    'provider' => 'stripe',
                ],
    
                'description' => $intent->description,
            ]);

            return PaymentCreateIntentResponse::fromStripe($pi);

        } catch (ApiErrorException $e) {
            throw new \RuntimeException(
                "Stripe createIntent failed: " . $e->getMessage(),
                previous: $e,
            );
        }
    }

    private function toStripeAmount(float|int $amount, string $currency): int {
        $currency = strtoupper($currency);
        $zeroDecimal = [
            'BIF','CLP','DJF','GNF','JPY','KMF','KRW','MGA','PYG','RWF','UGX','VND','VUV','XAF','XOF','XPF'
        ];

        if (in_array($currency, $zeroDecimal, true)) {
            return (int) round($amount, 0);
        }

        return (int) round($amount * 100, 0);
    }
}