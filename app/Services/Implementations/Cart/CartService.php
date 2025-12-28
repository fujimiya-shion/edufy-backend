<?php
namespace App\Services\Implementations\Cart;

use App\Repositories\Contracts\Cart\ICartRepository;
use App\Services\Contracts\Cart\ICartService;
use App\Services\Implementations\Service;
class CartService extends Service implements ICartService {
    public function __construct(ICartRepository $repository) {
        parent::__construct($repository);
    }
}