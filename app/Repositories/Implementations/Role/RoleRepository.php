<?php

namespace App\Repositories\Implementations\Role;

use App\Models\Role;
use App\Repositories\Implementations\Repository;
use App\Repositories\Contracts\Role\IRoleRepository;

class RoleRepository extends Repository implements IRoleRepository
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}
