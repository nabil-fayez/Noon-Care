<?php

namespace App\Services;

use App\Models\Facility;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class FacilityService
{
    /**
     * إنشاء منشأة جديدة
     */
    public function createFacility(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = isset($data['is_active']);

        if (isset($data['logo'])) {
            $data['logo'] = $data['logo']->store('facilities/logos', 'public');
        }

        return Facility::create($data);
    }

    /**
     * تحديث بيانات المنشأة
     */
    public function updateFacility(Facility $facility, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = isset($data['is_active']);

        if (isset($data['remove_logo']) && $data['remove_logo'] && $facility->logo) {
            Storage::disk('public')->delete($facility->logo);
            $data['logo'] = null;
        }

        if (isset($data['logo'])) {
            // حذف الشعار القديم إذا موجود
            if ($facility->logo) {
                Storage::disk('public')->delete($facility->logo);
            }
            $data['logo'] = $data['logo']->store('facilities/logos', 'public');
        }

        return $facility->update($data);
    }

    /**
     * حذف المنشأة
     */
    public function deleteFacility(Facility $facility)
    {
        if ($facility->logo) {
            Storage::disk('public')->delete($facility->logo);
        }

        return $facility->delete();
    }

    /**
     * البحث عن المنشآت
     */
    public function searchFacilities($searchTerm, $status = null)
    {
        $query = Facility::withCount(['doctors', 'services', 'appointments'])
            ->search($searchTerm);

        if ($status) {
            if ($status == 'active') {
                $query->active();
            } elseif ($status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }
}
