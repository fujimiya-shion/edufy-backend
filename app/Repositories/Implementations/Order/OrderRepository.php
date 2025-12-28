<?php
namespace App\Repositories\Implementations\Order;

use App\Models\Order;
use App\Repositories\Contracts\Order\IOrderRepository;
use App\Repositories\Implementations\Repository;
class OrderRepository extends Repository implements IOrderRepository {
    public function __construct(Order $model) {
        parent::__construct($model);
    }
}