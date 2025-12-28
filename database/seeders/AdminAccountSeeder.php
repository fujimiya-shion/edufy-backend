<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin',
            'role' => Admin::ROLE_ADMIN,
            'status' => Admin::STATUS_PUBLIC,
            'email' => 'admin@rada360.com',
            'password' => '123456',
        ]);
    }
}
