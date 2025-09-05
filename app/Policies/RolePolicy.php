<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin)
    {
        return $admin->hasPermission('roles.view');
    }

    public function view(Admin $admin, Role $role)
    {
        return $admin->hasPermission('roles.view');
    }

    public function create(Admin $admin)
    {
        return $admin->hasPermission('roles.create');
    }

    public function update(Admin $admin, Role $role)
    {
        return $admin->hasPermission('roles.update');
    }

    public function delete(Admin $admin, Role $role)
    {
        return $admin->hasPermission('roles.delete');
    }
}