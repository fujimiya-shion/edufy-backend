<?php

namespace App\Services\Contracts\Account;

use App\Models\Admin;
use App\Services\Contracts\IService;

interface IAdminService extends IService
{
    public function resetPassword(Admin $admin): void;
}
