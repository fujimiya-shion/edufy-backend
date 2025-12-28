<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;

class SuperAdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        Log::info("Role 'super-admin' ensured.");

        $admin = Admin::where('email', config('const.admin_email'))->first();

        if (!$admin) {

            Log::error("ERROR: Admin with email ". config('const.admin_email') . " not found!");
            return;
        }

        $admin->syncRoles([$superAdminRole]);
    }
}
