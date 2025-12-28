<?php
namespace App\Services\Implementations\Account;

use App\Repositories\Contracts\Account\IUserRepository;
use App\Services\Contracts\Account\IUserService;
use App\Services\Implementations\Service;
class UserService extends Service implements IUserService {
    public function __construct(IUserRepository $repository) {
        parent::__construct($repository);
    }
}