<?php
namespace App\Infra\Payment;

use App\Http\Dtos\Payment\PaymentCreateIntentRequest;
use App\Http\Dtos\Payment\PaymentCreateIntentResponse;
interface PaymentGatewayInterface {
    public function createIntent(PaymentCreateIntentRequest $intent): PaymentCreateIntentResponse;
}