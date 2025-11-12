<?php
namespace App\Services\Implementations\Cart;

use App\Models\CartItem;
use App\Services\Contracts\Cart\ICartItemService;
use App\Services\Implementations\Service;
class CartItemService extends Service implements ICartItemService {
    public function __construct(CartItem $repository) {
        parent::__construct($repository);
    }
}