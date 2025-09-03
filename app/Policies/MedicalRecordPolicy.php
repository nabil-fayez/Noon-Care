<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class MedicalRecordPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return Auth::guard('admin')->check() ||
            Auth::guard('doctor')->check() ||
            Auth::guard('patient')->check();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, MedicalRecord $medicalRecord): bool
    {
        // المسؤول يمكنه رؤية جميع السجلات
        if (Auth::guard('admin')->check()) {
            return true;
        }

        // الطبيب يمكنه رؤية سجلاته فقط
        if (Auth::guard('doctor')->check()) {
            return Auth::guard('doctor')->id() == $medicalRecord->doctor_id;
        }

        // المريض يمكنه رؤية سجلاته فقط
        if (Auth::guard('patient')->check()) {
            return Auth::guard('patient')->id() == $medicalRecord->patient_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return Auth::guard('admin')->check() ||
            Auth::guard('doctor')->check();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, MedicalRecord $medicalRecord): bool
    {
        // المسؤول يمكنه تعديل جميع السجلات
        if (Auth::guard('admin')->check()) {
            return true;
        }

        // الطبيب يمكنه تعديل سجلاته فقط
        if (Auth::guard('doctor')->check()) {
            return Auth::guard('doctor')->id() == $medicalRecord->doctor_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, MedicalRecord $medicalRecord): bool
    {
        // فقط المسؤول يمكنه الحذف
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, MedicalRecord $medicalRecord): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, MedicalRecord $medicalRecord): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can view patient records.
     */
    public function viewPatientRecords($user, $patientId): bool
    {
        // المسؤول يمكنه رؤية جميع السجلات
        if (Auth::guard('admin')->check()) {
            return true;
        }

        // الطبيب يمكنه رؤية سجلات مرضاه
        if (Auth::guard('doctor')->check()) {
            // هنا يمكن إضافة منطق للتحقق إذا كان الطبيب يعالج هذا المريض
            return true;
        }

        // المريض يمكنه رؤية سجلاته فقط
        if (Auth::guard('patient')->check()) {
            return Auth::guard('patient')->id() == $patientId;
        }

        return false;
    }
}
