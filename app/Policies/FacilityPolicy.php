<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Facility as FacilityModel;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;

class FacilityPolicy
{
    use HandlesAuthorization;

    public function viewAny(Authenticatable $user)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('facilities.view');
        }

        return $user instanceof Patient || $user instanceof Doctor;
    }

    public function view(Authenticatable $user, FacilityModel $facility)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('facilities.view');
        }

        if ($user instanceof Patient) {
            // يمكن للمريض رؤية المنشآت المرتبطة بمواعيده
            return $user->appointments()->where('facility_id', $facility->id)->exists();
        }

        if ($user instanceof Doctor) {
            // يمكن للطبيب رؤية المنشآت المرتبطة به
            return $user->facilities()->where('facility_id', $facility->id)->exists();
        }

        return false;
    }

    public function create(Authenticatable $user)
    {
        return $user instanceof Admin && $user->hasPermission('facilities.create');
    }

    public function update(Authenticatable $user, FacilityModel $facility)
    {
        if ($user instanceof Admin) {
            return $user->hasPermission('facilities.update');
        }

        // يمكن للمنشأة تحديث بياناتها الخاصة
        if ($user instanceof FacilityModel) {
            return $user->id === $facility->id;
        }

        return false;
    }

    public function delete(Authenticatable $user, FacilityModel $facility)
    {
        return $user instanceof Admin && $user->hasPermission('facilities.delete');
    }

    public function restore(Authenticatable $user, FacilityModel $facility)
    {
        return $user instanceof Admin && $user->hasPermission('facilities.restore');
    }

    public function forceDelete(Authenticatable $user, FacilityModel $facility)
    {
        return $user instanceof Admin && $user->hasPermission('facilities.forceDelete');
    }

    public function toggleStatus(Authenticatable $user, FacilityModel $facility)
    {
        return $user instanceof Admin && $user->hasPermission('facilities.manage_status');
    }

    public function viewServices(Admin $admin, FacilityModel $facility)
    {
        return $admin->hasPermission('facilities.view');
    }

    public function manageServices(Admin $admin, FacilityModel $facility)
    {
        return $admin->hasPermission('facilities.update');
    }
}