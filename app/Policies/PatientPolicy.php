<?php

namespace App\Policies;

use App\Models\Patient;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PatientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any patients.
     */
    public function viewAny($user): bool
    {
        // المسؤول فقط يمكنه عرض قائمة المرضى
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can view the patient.
     */
    public function view($user, Patient $patient): bool
    {
        // المسؤول أو المريض نفسه يمكنه عرض البيانات
        if (Auth::guard('admin')->check()) {
            return true;
        }

        if (Auth::guard('patient')->check()) {
            return Auth::guard('patient')->id() == $patient->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create patients.
     */
    public function create($user): bool
    {
        // فقط المسؤول يمكنه إنشاء مرضى
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can update the patient.
     */
    public function update($user, Patient $patient): bool
    {
        // المسؤول أو المريض نفسه يمكنه التعديل
        if (Auth::guard('admin')->check()) {
            return true;
        }

        if (Auth::guard('patient')->check()) {
            return Auth::guard('patient')->id() == $patient->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the patient.
     */
    public function delete($user, Patient $patient): bool
    {
        // فقط المسؤول يمكنه الحذف
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can restore the patient.
     */
    public function restore($user, Patient $patient): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Determine whether the user can permanently delete the patient.
     */
    public function forceDelete($user, Patient $patient): bool
    {
        return Auth::guard('admin')->check();
    }
}
