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
        // إنشاء الأدوار والصلاحيات الأساسية
        $this->call(RolesAndPermissionsSeeder::class);

        // إنشاء بيانات تجريبية
        $this->call(DemoDataSeeder::class);
    }
}
