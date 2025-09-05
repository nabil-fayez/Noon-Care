<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Doctor;
use App\Models\Service;
use App\Services\ErrorLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FacilityController extends Controller
{
    /**
     * عرض قائمة المنشآت الطبية
     */
    public function index(Request $request)
    {
        $facilities = Facility::withCount(['doctors', 'services', 'appointments'])
            ->when($request->has('search'), function ($query) use ($request) {
                return $query->search($request->search);
            })
            ->when($request->has('status'), function ($query) use ($request) {
                if ($request->status == 'active') {
                    return $query->active();
                } elseif ($request->status == 'inactive') {
                    return $query->where('is_active', false);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * عرض نموذج إنشاء منشأة جديدة
     */
    public function create()
    {
        return view('admin.facilities.create');
    }

    /**
     * حفظ المنشأة الجديدة
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:facilities,username|max:50',
            'email' => 'nullable|email|unique:facilities,email',
            'password' => 'required|min:8|confirmed',
            'business_name' => 'required|max:100',
            'phone' => 'nullable|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except('password_confirmation', 'logo');
            $data['password'] = Hash::make($request->password);
            $data['is_active'] = $request->has('is_active');

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('facilities/logos', 'public');
                $data['logo'] = $logoPath;
            }

            Facility::create($data);

            return redirect()->route('admin.facilities.index')
                ->with('success', 'تم إنشاء المنشأة بنجاح.');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المنشأة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * عرض تفاصيل المنشأة
     */
    public function show(Facility $facility)
    {
        $facility->loadCount(['doctors', 'services', 'appointments'])
            ->load('doctors.specialties');

        return view('admin.facilities.show', compact('facility'));
    }

    /**
     * عرض نموذج تعديل المنشأة
     */
    public function edit(Facility $facility)
    {
        return view('admin.facilities.update', compact('facility'));
    }

    /**
     * تحديث بيانات المنشأة
     */
    public function update(Request $request, Facility $facility)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:50|unique:facilities,username,' . $facility->id,
            'email' => 'nullable|email|unique:facilities,email,' . $facility->id,
            'password' => 'nullable|min:8|confirmed',
            'business_name' => 'required|max:100',
            'phone' => 'nullable|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'boolean',
            'remove_logo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except('password_confirmation', 'logo', 'remove_logo');
            $data['is_active'] = $request->has('is_active');

            if ($request->has('remove_logo') && $facility->logo) {
                Storage::disk('public')->delete($facility->logo);
                $data['logo'] = null;
            }

            if ($request->hasFile('logo')) {
                // حذف الشعار القديم إذا موجود
                if ($facility->logo) {
                    Storage::disk('public')->delete($facility->logo);
                }
                $logoPath = $request->file('logo')->store('facilities/logos', 'public');
                $data['logo'] = $logoPath;
            }

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }

            $facility->update($data);

            return redirect()->route('admin.facility.show', $facility)
                ->with('success', 'تم تحديث بيانات المنشأة بنجاح.');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث المنشأة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * عرض نموذج تأكيد الحذف
     */
    public function delete(Facility $facility)
    {
        return view('admin.facilities.delete', compact('facility'));
    }

    /**
     * حذف المنشأة (Soft Delete)
     */
    public function destroy(Request $request, Facility $facility)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // يمكنك هنا تسجيل سبب الحذف إذا أردت
            $facility->delete();

            return redirect()->route('admin.facilities.index')
                ->with('success', 'تم حذف المنشأة بنجاح.');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف المنشأة: ' . $e->getMessage());
        }
    }

    /**
     * تغيير حالة المنشأة (تفعيل/تعطيل)
     */
    public function toggleStatus(Request $request, Facility $facility)
    {
        try {
            $facility->update(['is_active' => !$facility->is_active]);

            $message = $facility->is_active
                ? 'تم تفعيل المنشأة بنجاح.'
                : 'تم تعطيل المنشأة بنجاح.';

            return redirect()->back()
                ->with('success', $message);
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تغيير حالة المنشأة: ' . $e->getMessage());
        }
    }

    /**
     * عرض قائمة الأطباء في المنشأة
     */
    public function doctors(Facility $facility)
    {
        $doctors = $facility->doctors()
            ->with('specialties')
            ->withPivot('status', 'available_for_appointments')
            ->paginate(10);

        return view('admin.facilities.doctors', compact('facility', 'doctors'));
    }

    /**
     * عرض قائمة الخدمات في المنشأة
     */
    public function services(Facility $facility)
    {
        $services = $facility->services()
            ->withPivot('is_available')
            ->paginate(10);

        return view('admin.facilities.services', compact('facility', 'services'));
    }

    /**
     * عرض مواعيد المنشأة
     */
    public function appointments(Facility $facility)
    {
        $appointments = $facility->appointments()
            ->with(['patient', 'doctor', 'service'])
            ->orderBy('appointment_datetime', 'desc')
            ->paginate(10);

        return view('admin.facilities.appointments', compact('facility', 'appointments'));
    }

    /**
     * عرض صفحة إضافة خدمة للمنشأة
     */
    public function addService(Facility $facility)
    {
        $this->authorize('update', $facility);

        // جلب الخدمات التي لم يتم إضافتها بعد للمنشأة
        $existingServiceIds = $facility->services()->pluck('services.id')->toArray();
        $services = Service::whereNotIn('id', $existingServiceIds)->get();

        return view('admin.facilities.add-service', compact('facility', 'services'));
    }

    /**
     * معالجة إضافة خدمة للمنشأة
     */
    public function storeService(Request $request, Facility $facility)
    {
        $this->authorize('update', $facility);

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'price' => 'required|numeric|min:0',
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

            return redirect()->route('admin.facility.services', $facility)
                ->with('success', 'تم إضافة الخدمة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة الخدمة: ' . $e->getMessage());
        }
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
                'price' => $validated['price'],
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

    public function showAddDoctorForm(Facility $facility)
    {
        $this->authorize('update', $facility);

        // جلب الأطباء غير المضافين لهذه المنشأة
        $existingDoctorIds = $facility->doctors()->pluck('doctors.id')->toArray();
        $availableDoctors = Doctor::whereNotIn('id', $existingDoctorIds)->get();

        return view('admin.facilities.add-doctor', compact('facility', 'availableDoctors'));
    }


    /**
     * معالجة إضافة طبيب للمنشأة
     */
    public function addDoctor(Request $request, Facility $facility)
    {
        $this->authorize('update', $facility);

        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'status' => 'required|in:active,pending,inactive',
            'available_for_appointments' => 'sometimes|boolean'
        ]);

        // التحقق إذا كان الطبيب مضافاً مسبقاً للمنشأة
        if ($facility->doctors()->where('doctor_id', $validated['doctor_id'])->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'هذا الطبيب مضاف مسبقاً للمنشأة');
        }

        try {
            DB::beginTransaction();

            // إضافة الطبيب للمنشأة
            $facility->doctors()->attach($validated['doctor_id'], [
                'status' => $validated['status'],
                'available_for_appointments' => $validated['available_for_appointments'] ?? false,
            ]);

            DB::commit();

            return redirect()->route('admin.facility.doctors', $facility)
                ->with('success', 'تم إضافة الطبيب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة الطبيب: ' . $e->getMessage());
        }
    }

    /**
     * إزالة طبيب من المنشأة
     */
    public function removeDoctor(Request $request, Facility $facility, Doctor $doctor)
    {
        $this->authorize('update', $facility);

        try {
            DB::beginTransaction();

            $facility->doctors()->detach($doctor->id);

            DB::commit();

            return redirect()->back()
                ->with('success', 'تم إزالة الطبيب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إزالة الطبيب: ' . $e->getMessage());
        }
    }
}