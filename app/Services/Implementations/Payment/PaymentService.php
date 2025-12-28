<?php
namespace App\Services\Implementations\Payment;

use App\Repositories\Contracts\Payment\IPaymentRepository;
use App\Services\Contracts\Payment\IPaymentService;
use App\Services\Implementations\Service;

class PaymentService extends Service implements IPaymentService
{
    public function __construct(IPaymentRepository $repository)
    {
        parent::__construct($repository);
    }
}
