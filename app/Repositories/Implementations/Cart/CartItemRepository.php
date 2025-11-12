<?php
namespace App\Repositories\Implementations\Cart;

use App\Models\CartItem;
use App\Repositories\Contracts\Cart\ICartItemRepository;
use App\Repositories\Implementations\Repository;
class CartItemRepository extends Repository implements ICartItemRepository {
    public function __construct(CartItem $model) {
        parent::__construct($model);
    }
}