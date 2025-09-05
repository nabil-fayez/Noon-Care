<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Facility;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;

class MedicalRecordPolicy
{
    use HandlesAuthorization;

    public function viewAny(Authenticatable $user)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('medical_records.view');
        }

        return $user instanceof Patient || $user instanceof Doctor || $user instanceof Facility;
    }

    public function view(Authenticatable $user, MedicalRecord $medicalRecord)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('medical_records.view');
        }

        if ($user instanceof Patient) {
            return $user->id === $medicalRecord->patient_id;
        }

        if ($user instanceof Doctor) {
            return $user->id === $medicalRecord->doctor_id;
        }

        if ($user instanceof Facility) {
            return $user->id === $medicalRecord->facility_id;
        }

        return false;
    }

    public function create(Authenticatable $user)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('medical_records.create');
        }

        return $user instanceof Doctor;
    }

    public function update(Authenticatable $user, MedicalRecord $medicalRecord)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('medical_records.update');
        }

        if ($user instanceof Doctor) {
            return $user->id === $medicalRecord->doctor_id;
        }

        return false;
    }

    public function delete(Authenticatable $user, MedicalRecord $medicalRecord)
    {
        return $user instanceof Admin && $user->hasPermission('medical_records.delete');
    }

    public function restore(Authenticatable $user, MedicalRecord $medicalRecord)
    {
        return $user instanceof Admin && $user->hasPermission('medical_records.restore');
    }

    public function forceDelete(Authenticatable $user, MedicalRecord $medicalRecord)
    {
        return $user instanceof Admin && $user->hasPermission('medical_records.forceDelete');
    }
}