<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define modules and their corresponding CRUD actions
        $modules = [
            'admin',
            'agency',
            'users',
            'booking',
            'shops',
            'promotion',
            'ribbon',
            'product-services',
            'master-slider',
            'metadata-shop-type',
            'metadata-category',
            'metadata-content',
            'metadata-price-range',
            'metadata-radius',
            'location',
        ];

        $crudActions = ['create', 'update', 'read', 'delete'];

        $permissions = [];
        foreach ($modules as $module) {
            foreach ($crudActions as $action) {
                $permissionName = "{$action}-{$module}";
                $permissions[] = Permission::firstOrCreate([
                    'name' => $permissionName,
                ]);
            }
        }

        $superAdminRole = Role::where('name', 'super-admin')->first();
        if ($superAdminRole) {
            $superAdminRole->syncPermissions($permissions);
            $this->command->info("Assigned all permissions to 'super-admin'.");
        } else {
            $this->command->error("ERROR: 'super-admin' role not found.");
        }
    }
}
