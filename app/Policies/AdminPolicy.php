<?php

namespace App\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin)
    {
        return $admin->hasPermission('admins.view');
    }

    public function view(Admin $admin, Admin $targetAdmin)
    {
        return $admin->hasPermission('admins.view');
    }

    public function create(Admin $admin)
    {
        return $admin->hasPermission('admins.create');
    }

    public function update(Admin $admin, Admin $targetAdmin)
    {
        // يمكن للمسؤول تعديل حسابه الخاص حتى لو لم يكن لديه الصلاحية
        if ($admin->id === $targetAdmin->id) {
            return true;
        }

        return $admin->hasPermission('admins.update');
    }

    public function delete(Admin $admin, Admin $targetAdmin)
    {
        // لا يمكن للمسؤول حذف حسابه الخاص
        if ($admin->id === $targetAdmin->id) {
            return false;
        }

        return $admin->hasPermission('admins.delete');
    }

    public function restore(Admin $admin, Admin $targetAdmin)
    {
        return $admin->hasPermission('admins.delete');
    }

    public function forceDelete(Admin $admin, Admin $targetAdmin)
    {
        return $admin->hasPermission('admins.delete');
    }

    public function toggleStatus(Admin $admin, Admin $targetAdmin)
    {
        // لا يمكن للمسؤول تعطيل حسابه الخاص
        if ($admin->id === $targetAdmin->id) {
            return false;
        }

        return $admin->hasPermission('admins.update');
    }
}