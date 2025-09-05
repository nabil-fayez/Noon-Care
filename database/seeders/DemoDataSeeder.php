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
        // إنشاء 20 تخصص
        \App\Models\Specialty::factory(3)->create();

        // إنشاء 20 طبيب
        \App\Models\Doctor::factory(3)->create();

        // إنشاء 20 مريض
        \App\Models\Patient::factory(3)->create();

        // إنشاء 20 منشأة
        \App\Models\Facility::factory(3)->create();

        // إنشاء 20 موعد
        \App\Models\Appointment::factory(3)->create();

        // إنشاء 20 سجل طبي
        \App\Models\MedicalRecord::factory(3)->create();

        // إنشاء 20 خدمة
        \App\Models\Service::factory(3)->create();

        // إنشاء 20 شركة تأمين
        \App\Models\InsuranceCompany::factory(3)->create();

        // إنشاء 20 تقييم
        \App\Models\Review::factory(3)->create();

        // إنشاء 20 دفع
        \App\Models\Payment::factory(3)->create();

        // إنشاء 20 إشعار
        \App\Models\Notification::factory(3)->create();

        // إنشاء 20 سجل خطأ
        \App\Models\ErrorLog::factory(3)->create();

        // إنشاء 10 مسؤولين إضافيين
        \App\Models\Admin::factory(3)->create();
    }
}
