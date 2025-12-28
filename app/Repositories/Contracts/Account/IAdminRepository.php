<?php

namespace App\Services\Contracts\Account;

use App\Models\Admin;
use App\Services\Contracts\IService;
use Illuminate\Contracts\Cache\Repository;

interface IAdminRepository extends Repository
{
    public function resetPassword(Admin $admin): void;
}
