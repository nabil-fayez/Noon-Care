<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Patient as PatientModel;
use App\Models\Doctor;
use App\Models\Facility;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;

class PatientPolicy
{
    use HandlesAuthorization;

    public function viewAny(Authenticatable $user)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('patients.view');
        }

        return $user instanceof Doctor || $user instanceof Facility;
    }

    public function view(Authenticatable $user, PatientModel $patient)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('patients.view');
        }

        if ($user instanceof PatientModel) {
            return $user->id === $patient->id;
        }

        if ($user instanceof Doctor) {
            // يمكن للطبيب رؤية مرضاه
            return $patient->appointments()->where('doctor_id', $user->id)->exists();
        }

        if ($user instanceof Facility) {
            // يمكن للمنشأة رؤية المرضى المرتبطين بها
            return $patient->appointments()->where('facility_id', $user->id)->exists();
        }

        return false;
    }

    public function create(Authenticatable $user)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('patients.create');
        }

        // يسمح للمرضى بالتسجيل بأنفسهم
        return $user instanceof PatientModel;
    }

    public function update(Authenticatable $user, PatientModel $patient)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('patients.update');
        }

        if ($user instanceof PatientModel) {
            return $user->id === $patient->id;
        }

        return false;
    }

    public function delete(Authenticatable $user, PatientModel $patient)
    {
        return $user instanceof Admin && $user->hasPermission('patients.delete');
    }

    public function restore(Authenticatable $user, PatientModel $patient)
    {
        return $user instanceof Admin && $user->hasPermission('patients.restore');
    }

    public function forceDelete(Authenticatable $user, PatientModel $patient)
    {
        return $user instanceof Admin && $user->hasPermission('patients.forceDelete');
    }

    public function toggleStatus(Authenticatable $user, PatientModel $patient)
    {
        return $user instanceof Admin && $user->hasPermission('patients.manage_status');
    }
}