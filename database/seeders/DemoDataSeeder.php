<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // 1. جدول التخصصات
        $specialtyId = DB::table('specialties')->insertGetId([
            'name' => 'طب الأسرة',
            'description' => 'تخصص في رعاية جميع أفراد الأسرة',
            'icon' => 'family_medicine.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. جدول الأطباء
        $doctorId = DB::table('doctors')->insertGetId([
            'username' => 'nabilfayez',
            'first_name' => 'نبيل',
            'last_name' => 'فايز',
            'email' => 'nabilfayez@mail.com',
            'password' => Hash::make('12345678'),
            'phone' => '0512345678',
            'bio' => 'طبيب أسرة متميز مع سنوات من الخبرة',
            'profile_image' => 'doctor_profile.jpg',
            'is_verified' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. جدول المرضى
        $patientId = DB::table('patients')->insertGetId([
            'username' => 'nabilfayez',
            'first_name' => 'نبيل',
            'last_name' => 'فايز',
            'email' => 'nabilfayez@mail.com',
            'password' => Hash::make('12345678'),
            'phone' => '0512345678',
            'date_of_birth' => '1985-05-15',
            'gender' => 'male',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. جدول المنشآت الطبية
        $facilityId = DB::table('facilities')->insertGetId([
            'username' => 'nabilfayez',
            'business_name' => 'مركز نبيل فايز الطبي',
            'address' => 'الرياض، حي الملز، شارع الملك فهد',
            'phone' => '0112345678',
            'email' => 'nabilfayez@mail.com',
            'password' => Hash::make('12345678'),
            'website' => 'www.nabilclinic.com',
            'description' => 'مركز طبي متكامل يقدم خدمات طبية متنوعة',
            'logo' => 'clinic_logo.png',
            'latitude' => 24.7136,
            'longitude' => 46.6753,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. جدول شركات التأمين
        $insuranceCompanyId = DB::table('insurance_companies')->insertGetId([
            'name' => 'تأمين نبيل',
            'contact_email' => 'info@nabilinsurance.com',
            'contact_phone' => '0119876543',
            'address' => 'الرياض، حي العليا',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. جدول الخدمات
        $serviceId = DB::table('services')->insertGetId([
            'name' => 'كشف طبي عام',
            'description' => 'كشف طبي شامل للمرضى',
            'category' => 'العيادات الخارجية',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 7. جدول تخصصات الأطباء
        $doctorSpecialtyId = DB::table('doctor_specialty')->insertGetId([
            'doctor_id' => $doctorId,
            'specialty_id' => $specialtyId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 8. جدول علاقات الأطباء بالمنشآت
        $doctorFacilityId = DB::table('doctor_facility')->insertGetId([
            'doctor_id' => $doctorId,
            'facility_id' => $facilityId,
            'status' => 'active',
            'available_for_appointments' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 9. جدول أسعار الخدمات حسب شركات التأمين
        $pricingId = DB::table('facility_service_pricing')->insertGetId([
            'facility_id' => $facilityId,
            'service_id' => $serviceId,
            'insurance_company_id' => $insuranceCompanyId,
            'price' => 150.00,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 10. جدول أوقات العمل
        $workingHoursId = DB::table('working_hours')->insertGetId([
            'doctor_id' => $doctorId,
            'facility_id' => $facilityId,
            'day_of_week' => 'Mon',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'is_available' => true,
            'slot_duration' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 11. جدول الأدوار
        $roleId = DB::table('roles')->insertGetId([
            'role_name' => 'مدير النظام',
            'description' => 'يمتلك جميع الصلاحيات في النظام',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 12. جدول الصلاحيات
        $permissionId = DB::table('permissions')->insertGetId([
            'permission_name' => 'manage_all',
            'display_name' => 'إدارة الكل',
            'description' => 'صلاحية للتحكم بجميع أجزاء النظام',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 13. جدول المشرفين
        $adminId = DB::table('admins')->insertGetId([
            'name' => 'نبيل فايز',
            'email' => 'nabilfayez@mail.com',
            'password' => Hash::make('12345678'),
            'role_id' => $roleId,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 14. جدول صلاحيات الأدوار
        $rolePermissionId = DB::table('role_permissions')->insertGetId([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 15. جدول سجلات المشرفين
        $adminLogId = DB::table('admin_logs')->insertGetId([
            'admin_id' => $adminId,
            'action' => 'تم إنشاء بيانات تجريبية',
            'created_at' => now(),
        ]);

        // 16. جدول المواعيد
        $appointmentId = DB::table('appointments')->insertGetId([
            'doctor_id' => $doctorId,
            'facility_id' => $facilityId,
            'doctor_facility_id' => $doctorFacilityId,
            'patient_id' => $patientId,
            'service_id' => $serviceId,
            'insurance_company_id' => $insuranceCompanyId,
            'appointment_datetime' => now()->addDays(2),
            'duration' => 30,
            'status' => 'confirmed',
            'notes' => 'موعد تجريبي',
            'price' => 150.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 17. جدول التقييمات
        $reviewId = DB::table('reviews')->insertGetId([
            'appointment_id' => $appointmentId,
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'facility_id' => $facilityId,
            'rating_for_doctor' => 5,
            'rating_for_facility' => 5,
            'comment_for_doctor' => 'تجربة ممتازة، طبيب متميز وخدمة رائعة',
            'comment_for_facility' => 'تجربة ممتازة، مستشفي متميزة وخدمة رائعة',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 18. جدول المدفوعات
        $paymentId = DB::table('payments')->insertGetId([
            'appointment_id' => $appointmentId,
            'amount' => 150.00,
            'status' => 'completed',
            'payment_method' => 'بطاقة ائتمان',
            'transaction_id' => 'TXN_' . time(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 19. جدول الإشعارات
        $notificationId = DB::table('notifications')->insertGetId([
            'user_id' => $patientId,
            'user_type' => 'patient',
            'title' => 'موعدك مؤكد',
            'message' => 'تم تأكيد موعدك مع الدكتور نبيل فايز',
            'is_read' => false,
            'created_at' => now(),
        ]);

        // 20. جدول خدمات المنشأة
        $facilityServiceId = DB::table('facility_services')->insertGetId([
            'facility_id' => $facilityId,
            'service_id' => $serviceId,
            'is_available' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}