<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\ErrorLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class ErrorLogPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin)
    {
        return $admin->hasPermission('error_logs.view');
    }

    public function view(Admin $admin, ErrorLog $errorLog)
    {
        return $admin->hasPermission('error_logs.view');
    }

    public function delete(Admin $admin, ErrorLog $errorLog)
    {
        return $admin->hasPermission('error_logs.delete');
    }

    public function clear(Admin $admin)
    {
        return $admin->hasPermission('error_logs.clear');
    }
}