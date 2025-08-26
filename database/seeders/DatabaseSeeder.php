<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::create(['role_name' => 'Super Admin', 'description' => 'Has full access to all system features and settings.']);
        Permission::create(['permission_name' => 'full_access', 'description' => 'Can do any thing.']);
        DB::table('role_permissions')->insert([
            'role_id' => 1,
            'permission_id' => 1,
        ]);
        Admin::create(['name' => 'Nabil Fayez', 'email' => 'nabilfayez@mail.com', 'password' => Hash::make('12345678'), 'role_id' => 1]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
