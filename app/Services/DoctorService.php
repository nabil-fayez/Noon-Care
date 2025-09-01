<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DoctorService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * الحصول على الأطباء مع التصفية والترتيب
     */
    public function getDoctors(array $filters = [], $perPage = 10)
    {
        $query = Doctor::with(['specialties', 'facilities']);

        // التصفية حسب التخصص
        if (!empty($filters['specialty_id'])) {
            $query->whereHas('specialties', function ($q) use ($filters) {
                $q->where('specialties.id', $filters['specialty_id']);
            });
        }

        // التصفية حسب الحالة
        if (isset($filters['is_verified']) && $filters['is_verified'] !== '') {
            $query->where('is_verified', $filters['is_verified']);
        }

        // البحث بالاسم
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // الترتيب
        $orderBy = $filters['order_by'] ?? 'created_at';
        $orderDir = $filters['order_dir'] ?? 'desc';
        $query->orderBy($orderBy, $orderDir);

        return $query->paginate($perPage);
    }

    /**
     * إنشاء طبيب جديد
     */
    public function createDoctor(array $data): Doctor
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

            // إنشاء الطبيب
            $doctor = Doctor::create($data);

            // ربط التخصصات
            if (!empty($data['specializations'])) {
                $doctor->specialties()->sync($data['specializations']);
            }
            return $doctor;
        });
    }

    /**
     * تحديث بيانات الطبيب
     */
    public function updateDoctor(Doctor $doctor, array $data): Doctor
    {
        return DB::transaction(function () use ($doctor, $data) {
            // معالجة حذف الصورة إذا طُلب
            if (isset($data['remove_image']) && $data['remove_image']) {
                if ($doctor->profile_image) {
                    Storage::delete($doctor->profile_image);
                    $data['profile_image'] = null;
                }
                unset($data['remove_image']);
            }

            // معالجة صورة الملف الشخصي إذا وجدت
            if (isset($data['profile_image'])) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($doctor->profile_image) {
                    Storage::delete($doctor->profile_image);
                }

                $data['profile_image'] = $this->uploadProfileImage($data['profile_image']);
            }

            // تحديث كلمة المرور إذا تم提供ها
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // تحديث بيانات الطبيب
            $doctor->update($data);

            // تحديث التخصصات
            if (isset($data['specializations'])) {
                $doctor->specialties()->sync($data['specializations']);
            }

            return $doctor;
        });
    }


    public function deleteDoctor(Doctor $doctor): bool
    {
        return DB::transaction(function () use ($doctor) {
            try {
                // إلغاء جميع المواعيد المستقبلية للطبيب
                $this->cancelFutureAppointments($doctor);

                // فصل العلاقات قبل الحذف
                $doctor->specialties()->detach();
                $doctor->facilities()->detach();

                // Soft Delete
                return $doctor->delete();
            } catch (\Exception $e) {
                throw new \Exception('فشل في حذف الطبيب: ' . $e->getMessage());
            }
        });
    }

    /**
     * إلغاء المواعيد المستقبلية للطبيب
     */
    private function cancelFutureAppointments(Doctor $doctor): void
    {
        $futureAppointments = $doctor->appointments()
            ->where('appointment_datetime', '>', now())
            ->whereIn('status', ['new', 'confirmed'])
            ->get();

        foreach ($futureAppointments as $appointment) {
            $appointment->update([
                'status' => 'cancelled',
                'cancellation_reason' => 'تم إلغاء الموعد بسبب حذف الطبيب من النظام'
            ]);

            // إرسال إشعارات للمرضى
            $this->notificationService->sendNotification(
                $appointment->patient_id,
                'patient',
                'إلغاء موعد',
                'تم إلغاء موعدك مع الدكتور ' . $doctor->full_name . ' بسبب حذف الطبيب من النظام.'
            );
        }
    }

    /**
     * استعادة طبيب محذوف
     */
    public function restoreDoctor(Doctor $doctor): bool
    {
        return $doctor->restore();
    }

    /**
     * حذف طبيب نهائيًا
     */
    public function forceDeleteDoctor(Doctor $doctor): bool
    {
        return DB::transaction(function () use ($doctor) {
            // حذف صورة الملف الشخصي إذا كانت موجودة
            if ($doctor->profile_image) {
                Storage::delete($doctor->profile_image);
            }

            // فصل جميع العلاقات
            $doctor->specialties()->detach();
            $doctor->facilities()->detach();

            return $doctor->forceDelete();
        });
    }

    /**
     * تغيير حالة توثيق الطبيب
     */
    public function toggleVerification(Doctor $doctor): Doctor
    {
        $doctor->update(['is_verified' => !$doctor->is_verified]);
        return $doctor;
    }

    /**
     * الحصول على الأطباء المحذوفين
     */
    public function getTrashedDoctors($perPage = 10)
    {
        return Doctor::onlyTrashed()
            ->with(['specialties', 'facilities'])
            ->orderBy('deleted_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على الأطباء حسب التخصص
     */
    public function getDoctorsBySpecialty($specialtyId)
    {
        return Doctor::whereHas('specialties', function ($query) use ($specialtyId) {
            $query->where('specialties.id', $specialtyId);
        })
            ->where('is_verified', true)
            ->get(['id', 'first_name', 'last_name', 'profile_image']);
    }

    /**
     * رفع صورة الملف الشخصي
     */
    private function uploadProfileImage($image): string
    {
        return $image->store('profile_images', 'public');
    }
}