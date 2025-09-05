<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // DB::beginTransaction();

        // try {
        //     // إنشاء الصلاحيات الأساسية
        //     $permissions = [
        //         // إدارة الأدوار
        //         ['permission_name' => 'roles.view', 'description' => 'عرض الأدوار', 'module' => 'roles'],
        //         ['permission_name' => 'roles.create', 'description' => 'إنشاء أدوار', 'module' => 'roles'],
        //         ['permission_name' => 'roles.update', 'description' => 'تعديل الأدوار', 'module' => 'roles'],
        //         ['permission_name' => 'roles.delete', 'description' => 'حذف الأدوار', 'module' => 'roles'],

        //         // إدارة الصلاحيات
        //         ['permission_name' => 'permissions.view', 'description' => 'عرض الصلاحيات', 'module' => 'permissions'],
        //         ['permission_name' => 'permissions.create', 'description' => 'إنشاء صلاحيات', 'module' => 'permissions'],
        //         ['permission_name' => 'permissions.update', 'description' => 'تعديل الصلاحيات', 'module' => 'permissions'],
        //         ['permission_name' => 'permissions.delete', 'description' => 'حذف الصلاحيات', 'module' => 'permissions'],

        //         // ... أضف بقية الصلاحيات هنا
        //     ];

        //     foreach ($permissions as $permission) {
        //         Permission::firstOrCreate(
        //             ['permission_name' => $permission['permission_name']],
        //             $permission
        //         );
        //     }

        //     // إنشاء دور مدير النظام
        //     $adminRole = Role::firstOrCreate(
        //         ['role_name' => 'مدير النظام'],
        //         [
        //             'description' => 'يمتلك جميع الصلاحيات في النظام',
        //             'is_default' => false
        //         ]
        //     );

        //     // إرفاق جميع الصلاحيات بدور مدير النظام
        //     $allPermissions = Permission::all();
        //     $adminRole->permissions()->sync($allPermissions->pluck('id'));

        //     // تحديث مسؤول النظام ليكون له دور مدير النظام
        //     $admin = Admin::where('email', 'nabilfayez@mail.com')->first();
        //     if ($admin) {
        //         $admin->update(['role_id' => $adminRole->id]);
        //     }

        //     DB::commit();

        //     $this->command->info('تم إنشاء الصلاحيات والأدوار بنجاح!');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     $this->command->error('حدث خطأ أثناء إنشاء البيانات: ' . $e->getMessage());
        // }
    }
}
