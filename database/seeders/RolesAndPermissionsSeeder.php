<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();

        try {
            // مسح البيانات الحالية (اختياري)
            DB::table('role_permissions')->delete();
            DB::table('permissions')->delete();
            DB::table('roles')->delete();

            // إنشاء الصلاحيات الأساسية
            $permissionsData = [
                ['permission_name' => 'admins.view', 'description' => 'عرض المسؤولين', 'module' => 'admins'],
                ['permission_name' => 'admins.create', 'description' => 'إنشاء مسؤول', 'module' => 'admins'],
                ['permission_name' => 'admins.update', 'description' => 'تعديل مسؤول', 'module' => 'admins'],
                ['permission_name' => 'admins.delete', 'description' => 'حذف مسؤول', 'module' => 'admins'],

                ['permission_name' => 'doctors.view', 'description' => 'عرض الأطباء', 'module' => 'doctors'],
                ['permission_name' => 'doctors.create', 'description' => 'إنشاء طبيب', 'module' => 'doctors'],
                ['permission_name' => 'doctors.update', 'description' => 'تعديل طبيب', 'module' => 'doctors'],
                ['permission_name' => 'doctors.delete', 'description' => 'حذف طبيب', 'module' => 'doctors'],
                ['permission_name' => 'doctors.restore', 'description' => 'استعادة طبيب', 'module' => 'doctors'],
                ['permission_name' => 'doctors.forceDelete', 'description' => 'حذف طبيب نهائي', 'module' => 'doctors'],
                ['permission_name' => 'doctors.verify', 'description' => 'توثيق طبيب', 'module' => 'doctors'],

                ['permission_name' => 'patients.view', 'description' => 'عرض المرضى', 'module' => 'patients'],
                ['permission_name' => 'patients.create', 'description' => 'إنشاء مريض', 'module' => 'patients'],
                ['permission_name' => 'patients.update', 'description' => 'تعديل مريض', 'module' => 'patients'],
                ['permission_name' => 'patients.delete', 'description' => 'حذف مريض', 'module' => 'patients'],
                ['permission_name' => 'patients.restore', 'description' => 'استعادة مريض', 'module' => 'patients'],
                ['permission_name' => 'patients.forceDelete', 'description' => 'حذف مريض نهائي', 'module' => 'patients'],
                ['permission_name' => 'patients.manage_status', 'description' => 'إدارة حالة المريض', 'module' => 'patients'],

                ['permission_name' => 'facilities.view', 'description' => 'عرض المنشآت', 'module' => 'facilities'],
                ['permission_name' => 'facilities.create', 'description' => 'إنشاء منشأة', 'module' => 'facilities'],
                ['permission_name' => 'facilities.update', 'description' => 'تعديل منشأة', 'module' => 'facilities'],
                ['permission_name' => 'facilities.delete', 'description' => 'حذف منشأة', 'module' => 'facilities'],
                ['permission_name' => 'facilities.restore', 'description' => 'استعادة منشأة', 'module' => 'facilities'],
                ['permission_name' => 'facilities.forceDelete', 'description' => 'حذف منشأة نهائي', 'module' => 'facilities'],
                ['permission_name' => 'facilities.manage_status', 'description' => 'إدارة حالة المنشأة', 'module' => 'facilities'],

                ['permission_name' => 'appointments.view', 'description' => 'عرض المواعيد', 'module' => 'appointments'],
                ['permission_name' => 'appointments.create', 'description' => 'إنشاء موعد', 'module' => 'appointments'],
                ['permission_name' => 'appointments.update', 'description' => 'تعديل موعد', 'module' => 'appointments'],
                ['permission_name' => 'appointments.delete', 'description' => 'حذف موعد', 'module' => 'appointments'],
                ['permission_name' => 'appointments.restore', 'description' => 'استعادة موعد', 'module' => 'appointments'],
                ['permission_name' => 'appointments.forceDelete', 'description' => 'حذف موعد نهائي', 'module' => 'appointments'],

                ['permission_name' => 'medical_records.view', 'description' => 'عرض السجلات الطبية', 'module' => 'medical_records'],
                ['permission_name' => 'medical_records.create', 'description' => 'إنشاء سجل طبي', 'module' => 'medical_records'],
                ['permission_name' => 'medical_records.update', 'description' => 'تعديل سجل طبي', 'module' => 'medical_records'],
                ['permission_name' => 'medical_records.delete', 'description' => 'حذف سجل طبي', 'module' => 'medical_records'],
                ['permission_name' => 'medical_records.restore', 'description' => 'استعادة سجل طبي', 'module' => 'medical_records'],
                ['permission_name' => 'medical_records.forceDelete', 'description' => 'حذف سجل طبي نهائي', 'module' => 'medical_records'],

                ['permission_name' => 'specialties.view', 'description' => 'عرض التخصصات', 'module' => 'specialties'],
                ['permission_name' => 'specialties.create', 'description' => 'إنشاء تخصص', 'module' => 'specialties'],
                ['permission_name' => 'specialties.update', 'description' => 'تعديل تخصص', 'module' => 'specialties'],
                ['permission_name' => 'specialties.delete', 'description' => 'حذف تخصص', 'module' => 'specialties'],

                ['permission_name' => 'error_logs.view', 'description' => 'عرض سجلات الأخطاء', 'module' => 'error_logs'],
                ['permission_name' => 'error_logs.delete', 'description' => 'حذف سجل خطأ', 'module' => 'error_logs'],
                ['permission_name' => 'error_logs.clear', 'description' => 'مسح سجلات الأخطاء', 'module' => 'error_logs'],

                ['permission_name' => 'reports.view', 'description' => 'عرض التقارير', 'module' => 'reports'],

                ['permission_name' => 'settings.view', 'description' => 'عرض الإعدادات', 'module' => 'settings'],
            ];

            // إدخال الصلاحيات
            foreach ($permissionsData as $permission) {
                Permission::firstOrCreate(
                    ['permission_name' => $permission['permission_name']],
                    $permission
                );
            }

            // إنشاء دور مدير النظام
            $adminRole = Role::firstOrCreate(
                ['role_name' => 'مدير النظام'],
                [
                    'description' => 'يمتلك جميع الصلاحيات في النظام',
                    'is_default' => false
                ]
            );

            // إرفاق جميع الصلاحيات بدور مدير النظام
            $permissionIds = Permission::pluck('id')->toArray();
            $adminRole->permissions()->sync($permissionIds);

            // إنشاء مسؤول افتراضي
            $admin = Admin::firstOrCreate(
                ['email' => 'nabilfayez@mail.com'],
                [
                    'name' => 'نبيل فايز',
                    'password' => Hash::make('12345678'),
                    'role_id' => $adminRole->id,
                    'is_active' => true
                ]
            );

            // إنشاء 10 أدوار إضافية
            \App\Models\Role::factory(10)->create();

            DB::commit();

            $this->command->info('تم إنشاء الأدوار والصلاحيات بنجاح! عدد الصلاحيات: ' . count($permissionsData));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('حدث خطأ أثناء إنشاء البيانات: ' . $e->getMessage());
        }
    }
}
