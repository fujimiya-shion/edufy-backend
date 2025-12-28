<?php

namespace App\Repositories\Implementations\Account;

use App\Models\Admin;
use App\Repositories\Implementations\Repository;
use App\Repositories\Contracts\Account\IAdminRepository;

class AdminRepository extends Repository implements IAdminRepository
{
    public function __construct(Admin $model)
    {
        parent::__construct($model);
    }
}
