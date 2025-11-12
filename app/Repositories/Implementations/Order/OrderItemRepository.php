<?php
namespace App\Repositories\Implementations\Order;

use App\Models\OrderItem;
use App\Repositories\Contracts\Order\IOrderItemRepository;
use App\Repositories\Implementations\Repository;

class OrderItemRepository extends Repository implements IOrderItemRepository
{
    public function __construct(OrderItem $model)
    {
        parent::__construct($model);
    }
}
