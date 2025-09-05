<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilityServiceController extends Controller
{
    /**
     * عرض صفحة إضافة خدمة للمنشأة
     */
    public function addService(Facility $facility)
    {
        $this->authorize('update', $facility);

        // جلب الخدمات التي لم يتم إضافتها بعد للمنشأة
        $existingServiceIds = $facility->services()->pluck('services.id')->toArray();
        $services = Service::whereNotIn('id', $existingServiceIds)->get();

        return view('admin.facilities.services', compact('facility', 'services'));
    }

    /**
     * معالجة إضافة خدمة للمنشأة
     */
    public function storeService(Request $request, Facility $facility)
    {
        $this->authorize('update', $facility);

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'duration' => 'required|integer|min:5',
            'is_available' => 'sometimes|boolean'
        ]);

        // التحقق إذا كانت الخدمة مضافة مسبقاً
        if ($facility->services()->where('service_id', $validated['service_id'])->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'هذه الخدمة مضافه مسبقاً للمنشأة');
        }

        try {
            DB::beginTransaction();

            // إضافة الخدمة للمنشأة
            $facility->services()->attach($validated['service_id'], [
                'duration' => $validated['duration'],
                'is_available' => $validated['is_available'] ?? true,
            ]);

            DB::commit();

            return redirect()->route('admin.facilities.services', $facility)
                ->with('success', 'تم إضافة الخدمة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة الخدمة: ' . $e->getMessage());
        }
    }

    /**
     * عرض خدمات المنشأة
     */
    public function services(Facility $facility)
    {
        $this->authorize('view', $facility);

        $facility->load('services');
        return view('admin.facilities.services', compact('facility'));
    }

    /**
     * إزالة خدمة من المنشأة
     */
    public function removeService(Facility $facility, Service $service)
    {
        $this->authorize('update', $facility);

        try {
            DB::beginTransaction();

            $facility->services()->detach($service->id);

            DB::commit();

            return redirect()->back()
                ->with('success', 'تم إزالة الخدمة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إزالة الخدمة: ' . $e->getMessage());
        }
    }

    /**
     * تحديث خدمة في المنشأة
     */
    public function updateService(Request $request, Facility $facility, Service $service)
    {
        $this->authorize('update', $facility);

        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:5',
            'is_available' => 'sometimes|boolean'
        ]);

        try {
            DB::beginTransaction();

            $facility->services()->updateExistingPivot($service->id, [
                'duration' => $validated['duration'],
                'is_available' => $validated['is_available'] ?? true,
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'تم تحديث الخدمة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الخدمة: ' . $e->getMessage());
        }
    }
}
