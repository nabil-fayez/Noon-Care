<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PatientService
{


    /**
     * الحصول على المرضى مع التصفية والترتيب
     */
    public function getPatients(array $filters = [], $perPage = 10)
    {
        $query = Patient::withCount(['appointments', 'medicalRecords']);

        // التصفية حسب الحالة
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        // التصفية حسب الجنس
        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        // البحث
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // الترتيب
        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDir = $filters['order_dir'] ?? 'desc';
        $query->orderBy($orderBy, $orderDir);

        return $query->paginate($perPage);
    }

    /**
     * إنشاء مريض جديد
     */
    public function createPatient(array $data): Patient
    {
        return DB::transaction(function () use ($data) {
            // معالجة صورة الملف الشخصي إذا وجدت
            if (isset($data['profile_image'])) {
                $data['profile_image'] = $this->uploadProfileImage($data['profile_image']);
            }

            // تشفير كلمة المرور
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            return Patient::create($data);
        });
    }

    /**
     * تحديث بيانات المريض
     */
    public function updatePatient(Patient $patient, array $data): Patient
    {
        return DB::transaction(function () use ($patient, $data) {
            // معالجة صورة الملف الشخصي إذا وجدت
            if (isset($data['profile_image'])) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($patient->profile_image) {
                    Storage::delete($patient->profile_image);
                }

                $data['profile_image'] = $this->uploadProfileImage($data['profile_image']);
            }

            // تحديث كلمة المرور إذا تم提供ها
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $patient->update($data);

            return $patient;
        });
    }

    /**
     * حذف مريض
     */
    public function deletePatient(Patient $patient): bool
    {
        return DB::transaction(function () use ($patient) {
            // حذف صورة الملف الشخصي إذا كانت موجودة
            if ($patient->profile_image) {
                Storage::delete($patient->profile_image);
            }

            return $patient->delete();
        });
    }

    /**
     * تبديل حالة المريض
     */
    public function toggleStatus(Patient $patient): Patient
    {
        $patient->update(['is_active' => !$patient->is_active]);
        return $patient;
    }

    /**
     * رفع صورة الملف الشخصي
     */
    private function uploadProfileImage($image): string
    {
        return $image->store('patients/profile_images', 'public');
    }

    /**
     * الحصول على إحصائيات المرضى
     */
    public function getStatistics(): array
    {
        return [
            'total' => Patient::count(),
            'active' => Patient::active()->count(),
            'with_appointments' => Patient::has('appointments')->count(),
            'new_this_month' => Patient::whereMonth('created_at', now()->month)->count()
        ];
    }
}