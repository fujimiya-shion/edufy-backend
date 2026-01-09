<?php
namespace App\Http\Dtos\Payment;

use Illuminate\Http\Request;

class PaymentCreateIntentRequest {
    public string $provider;
    public int $orderId;
    public float $amount;
    public string $currency;
    public int $customerId;
    public ?string $description;

    public function __construct(
        string $provider,
        int $orderId,
        float $amount,
        string $currency,
        int $customerId,
        ?string $description = null,
    ) {
        $this->provider = $provider;
        $this->orderId = $orderId;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->customerId = $customerId;
        $this->description = $description;
    }

    public static function fromRequest(Request $request): PaymentCreateIntentRequest {
        $provider = $request->post("provider");
        $orderId = $request->post("order_id");
        $amount = $request->post("amount");
        $currency = $request->post("currency");
        $userId = $request->post("customer")["id"] ?? null;
        $description = $request->post("description");

        $intent = new PaymentCreateIntentRequest(
            $provider,
            $orderId,
            $amount,
            $currency,
            $userId,
            $description,
        );
        return $intent;
    }
}