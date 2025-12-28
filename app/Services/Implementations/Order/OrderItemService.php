<?php
namespace App\Services\Implementations\Order;

use App\Repositories\Contracts\Order\IOrderItemRepository;
use App\Services\Contracts\Order\IOrderItemService;
use App\Services\Implementations\Service;
class OrderItemService extends Service implements IOrderItemService {
    public function __construct(IOrderItemRepository $repository) {
        parent::__construct($repository);
    }
}