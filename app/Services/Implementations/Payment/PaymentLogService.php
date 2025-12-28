<?php
namespace App\Services\Implementations\Payment;

use App\Repositories\Contracts\Payment\IPaymentLogRepository;
use App\Services\Contracts\Payment\IPaymentLogService;
use App\Services\Implementations\Service;

class PaymentLogService extends Service implements IPaymentLogService
{
    public function __construct(IPaymentLogRepository $repository)
    {
        parent::__construct($repository);
    }
}
