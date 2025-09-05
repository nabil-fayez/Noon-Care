<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin)
    {
        return $admin->hasPermission('permissions.view');
    }

    public function view(Admin $admin, Permission $permission)
    {
        return $admin->hasPermission('permissions.view');
    }

    public function create(Admin $admin)
    {
        return $admin->hasPermission('permissions.create');
    }

    public function update(Admin $admin, Permission $permission)
    {
        return $admin->hasPermission('permissions.update');
    }

    public function delete(Admin $admin, Permission $permission)
    {
        return $admin->hasPermission('permissions.delete');
    }
}
