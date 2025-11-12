<?php
namespace App\Services\Implementations\Order;

use App\Repositories\Contracts\Order\IOrderRepository;
use App\Services\Contracts\Order\IOrderService;
use App\Services\Implementations\Service;
class OrderService extends Service implements IOrderService {
    public function __construct(IOrderRepository $repository) {
        parent::__construct($repository);
    }
    
}