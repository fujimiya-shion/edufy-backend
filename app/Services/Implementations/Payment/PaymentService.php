<?php
namespace App\Services\Implementations\Payment;

use App\Http\Dtos\Payment\PaymentCreateIntentRequest;
use App\Http\Dtos\Payment\PaymentCreateIntentResponse;
use App\Infra\Payment\PaymentGatewayFactory;
use App\Repositories\Contracts\Payment\IPaymentRepository;
use App\Services\Contracts\Payment\IPaymentService;
use App\Services\Implementations\Service;

class PaymentService extends Service implements IPaymentService
{
    public function __construct(IPaymentRepository $repository)
    {
        parent::__construct($repository);
    }

    public function createIntent(PaymentCreateIntentRequest $intent): PaymentCreateIntentResponse {
        $provider = $intent->provider;
        $gateway = app(PaymentGatewayFactory::class)->make($provider);
        $intent = $gateway->createIntent($intent);
        return $intent;
    }
}
