<?php

namespace App\Services\Implementations\Role;

use App\Models\Permission;
use App\Models\Role;
use App\Repositories\Contracts\Role\IRoleRepository;
use App\Services\Implementations\Service;
use App\Services\Contracts\Role\IRoleService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleService extends Service implements IRoleService
{
    public function __construct(IRoleRepository $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data): Role
    {
        Log::info(json_encode($data));
        return DB::transaction(function () use ($data) {
            return $this->_saveRole(new Role(), $data);
        });
    }

    private function _saveRole(Role $role, array $data): Role
    {
        // Save basic role information
        $role->fill([
            'name' => $data['name'],
            'display_name' => $data['display_name'],
            'description' => $data['description'] ?? null,
        ]);

        $role->save();

        // Get all permissions from the form data
        $permissions = $this->_extractPermissionsFromData($data);

        // Sync permissions
        $role->syncPermissions($permissions);

        return $role;
    }

   public function updateRole(Model $record, array $data)
   {
       $role = $record instanceof Role ? $record : throw new \InvalidArgumentException('Record must be a Role instance');
       return DB::transaction(function () use ($role, $data) {
           return $this->_saveRole($role, $data);
       });

   }

    private function _extractPermissionsFromData(array $data): array
    {
        $permissions = [];
        $allPermissions = Permission::all()->groupBy(function ($item) {
            $nameParts = explode('-', $item->name);
            return implode('-', array_slice($nameParts, 1));
        });

        // Process each module's permissions
        foreach ($allPermissions as $module => $modulePermissions) {
            $selectAllKey = 'select_all_' . $module;
            $permissionsKey = 'permissions_' . $module;

            // If "select all" is checked, add all permissions for this module
            if (isset($data[$selectAllKey]) && $data[$selectAllKey]) {
                $modulePermissionNames = $modulePermissions->pluck('name')->toArray();
                $permissions = array_merge($permissions, $modulePermissionNames);
            }
            // Otherwise, add only the selected permissions
            elseif (isset($data[$permissionsKey]) && is_array($data[$permissionsKey])) {
                $permissions = array_merge($permissions, $data[$permissionsKey]);
            }
        }

        return $permissions;
    }
}
