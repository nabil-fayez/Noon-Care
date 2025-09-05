<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Doctor;
use Illuminate\Auth\Access\HandlesAuthorization;

class DoctorPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin)
    {
        return $admin->hasPermission('doctors.view');
    }

    public function view(Admin $admin, Doctor $doctor)
    {
        return $admin->hasPermission('doctors.view');
    }

    public function create(Admin $admin)
    {
        return $admin->hasPermission('doctors.create');
    }

    public function update(Admin $admin, Doctor $doctor)
    {
        return $admin->hasPermission('doctors.update');
    }

    public function delete(Admin $admin, Doctor $doctor)
    {
        return $admin->hasPermission('doctors.delete');
    }

    public function restore(Admin $admin, Doctor $doctor)
    {
        return $admin->hasPermission('doctors.restore');
    }

    public function forceDelete(Admin $admin, Doctor $doctor)
    {
        return $admin->hasPermission('doctors.forceDelete');
    }

    public function toggleVerification(Admin $admin, Doctor $doctor)
    {
        return $admin->hasPermission('doctors.verify');
    }
}
