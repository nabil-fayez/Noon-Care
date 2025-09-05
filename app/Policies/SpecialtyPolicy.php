<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Specialty;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecialtyPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin)
    {
        return $admin->hasPermission('specialties.view');
    }

    public function view(Admin $admin, Specialty $specialty)
    {
        return $admin->hasPermission('specialties.view');
    }

    public function create(Admin $admin)
    {
        return $admin->hasPermission('specialties.create');
    }

    public function update(Admin $admin, Specialty $specialty)
    {
        return $admin->hasPermission('specialties.update');
    }

    public function delete(Admin $admin, Specialty $specialty)
    {
        return $admin->hasPermission('specialties.delete');
    }
}
