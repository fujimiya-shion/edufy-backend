<?php
namespace App\Repositories\Implementations\Payment;

use App\Models\PaymentLog;
use App\Repositories\Contracts\Payment\IPaymentLogRepository;
use App\Repositories\Implementations\Repository;

class PaymentLogRepository extends Repository implements IPaymentLogRepository
{
    public function __construct(PaymentLog $model)
    {
        parent::__construct($model);
    }
}
