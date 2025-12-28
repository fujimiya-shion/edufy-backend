<?php
namespace App\Repositories\Implementations\Payment;

use App\Models\Payment;
use App\Repositories\Contracts\Payment\IPaymentRepository;
use App\Repositories\Implementations\Repository;

class PaymentRepository extends Repository implements IPaymentRepository
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }
}
