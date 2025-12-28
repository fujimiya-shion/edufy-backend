<?php
namespace App\Repositories\Implementations\Account;

use App\Models\User;
use App\Repositories\Contracts\Account\IUserRepository;
use App\Repositories\Implementations\Repository;
class UserRepository extends Repository implements IUserRepository {
    public function __construct(User $model) {
        parent::__construct($model);
    }
}