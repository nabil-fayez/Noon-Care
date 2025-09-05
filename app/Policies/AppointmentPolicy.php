<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Facility;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;

class AppointmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(Authenticatable $user)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('appointments.view');
        }

        return $user instanceof Patient || $user instanceof Doctor || $user instanceof Facility;
    }

    public function view(Authenticatable $user, Appointment $appointment)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('appointments.view');
        }

        if ($user instanceof Patient) {
            return $user->id === $appointment->patient_id;
        }

        if ($user instanceof Doctor) {
            return $user->id === $appointment->doctor_id;
        }

        if ($user instanceof Facility) {
            return $user->id === $appointment->facility_id;
        }

        return false;
    }

    public function create(Authenticatable $user)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('appointments.create');
        }

        return $user instanceof Patient;
    }

    public function update(Authenticatable $user, Appointment $appointment)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('appointments.update');
        }

        if ($user instanceof Doctor) {
            return $user->id === $appointment->doctor_id;
        }

        if ($user instanceof Facility) {
            return $user->id === $appointment->facility_id;
        }

        return false;
    }

    public function delete(Authenticatable $user, Appointment $appointment)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('appointments.delete');
        }

        if ($user instanceof Patient) {
            return $user->id === $appointment->patient_id;
        }

        return false;
    }

    public function restore(Authenticatable $user, Appointment $appointment)
    {
        return $user instanceof Admin && $user->hasPermission('appointments.restore');
    }

    public function forceDelete(Authenticatable $user, Appointment $appointment)
    {
        return $user instanceof Admin && $user->hasPermission('appointments.forceDelete');
    }
}
