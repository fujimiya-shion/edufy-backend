<?php
namespace App\Repositories\Implementations\Cart;

use App\Models\Cart;
use App\Repositories\Contracts\Cart\ICartRepository;
use App\Repositories\Implementations\Repository;
class CartRepository extends Repository implements ICartRepository {
    public function __construct(Cart $model) {
        parent::__construct($model);
    }
}