<?php

namespace App\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $user)
    {
        return $user->hasPermission('admins.view');
    }

    public function view(Admin $user, Admin $admin)
    {
        return $user->hasPermission('admins.view');
    }

    public function create(Admin $user)
    {
        return $user->hasPermission('admins.create');
    }

    public function update(Admin $user, Admin $admin)
    {
        return $user->hasPermission('admins.update');
    }

    public function delete(Admin $user, Admin $admin)
    {
        return $user->hasPermission('admins.delete');
    }

    public function restore(Admin $user, Admin $admin)
    {
        return $user->hasPermission('admins.restore');
    }

    public function forceDelete(Admin $user, Admin $admin)
    {
        return $user->hasPermission('admins.forceDelete');
    }

    public function manageRoles(Admin $user)
    {
        return $user->hasPermission('roles.manage');
    }

    public function managePermissions(Admin $user)
    {
        return $user->hasPermission('permissions.manage');
    }
}