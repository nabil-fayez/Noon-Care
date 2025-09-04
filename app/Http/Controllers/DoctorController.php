<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Services\DoctorService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Services\ErrorLogService;

class DoctorController extends Controller
{
    protected $doctorService;
    protected $notificationService;

    public function __construct(DoctorService $doctorService, NotificationService $notificationService)
    {
        $this->doctorService = $doctorService;
        $this->notificationService = $notificationService;
    }

    /**
     * عرض قائمة الأطباء
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $doctors = $this->doctorService->getDoctors($request->all(), $perPage);

            return view('admin.doctors.index', [
                'doctors' => $doctors,
                'specialties' => Specialty::all(),
                'filters' => $request->all()
            ]);
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات الأطباء: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج إنشاء طبيب جديد
     */
    public function create()
    {
        return view('admin.doctors.create', [
            'specialties' => Specialty::all(),
            'doctor' => new Doctor() // نموذج فارغ للاستخدام في الفورم
        ]);
    }

    /**
     * حفظ طبيب جديد
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validateDoctorData($request);

            // إنشاء الطبيب باستخدام الخدمة
            $doctor = $this->doctorService->createDoctor($validated);
            // إرسال إشعار للمسؤول
            $this->notificationService->sendNotification(
                1,
                'admin',
                'طبيب جديد',
                'تم إضافة طبيب جديد: ' . $doctor->full_name
            );

            return redirect()->route('admin.doctors.index')
                ->with('success', 'تم إنشاء الطبيب بنجاح وإرسال بيانات التسجيل.');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء الطبيب: ' . $e->getMessage());
        }
    }

    /**
     * عرض بيانات طبيب
     */
    public function show(Request $request, Doctor $doctor)
    {
        try {
            $doctor->load(['specialties', 'facilities', 'appointments', 'reviews']);

            $allSpecialties = Specialty::all();

            return view('admin.doctors.show', compact('doctor', 'allSpecialties'));
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات الطبيب: ' . $e->getMessage());
        }
    }


    /**
     * عرض نموذج تعديل طبيب
     */
    public function edit(Request $request, Doctor $doctor)
    {
        try {
            $doctor->load('specialties');
            $specialties = Specialty::all();
            $selectedSpecialties = $doctor->specialties->pluck('id')->toArray();

            return view('admin.doctors.update', compact('doctor', 'specialties', 'selectedSpecialties'));
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل صفحة التعديل: ' . $e->getMessage());
        }
    }

    /**
     * تحديث بيانات الطبيب
     */
    public function update(Request $request, Doctor $doctor)
    {
        try {
            $validated = $this->validateDoctorData($request, $doctor->id);

            // تحديث الطبيب باستخدام الخدمة
            $this->doctorService->updateDoctor($doctor, $validated);

            return redirect()->route('admin.doctor.show', $doctor)
                ->with('success', 'تم تحديث بيانات الطبيب بنجاح.');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الطبيب: ' . $e->getMessage());
        }
    }

    /**
     * تحديث تخصصات الطبيب
     */
    public function updateSpecialties(Request $request, Doctor $doctor)
    {
        try {
            $request->validate([
                'specialties' => 'required|array|min:1',
                'specialties.*' => 'exists:specialties,id'
            ]);

            $doctor->specialties()->sync($request->specialties);

            return redirect()->route('admin.doctor.show', $doctor)
                ->with('success', 'تم تحديث تخصصات الطبيب بنجاح');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث التخصصات: ' . $e->getMessage());
        }
    }
    /**
     * عرض نموذج تأكيد الحذف
     */
    public function delete(Request $request, Doctor $doctor)
    {
        try {
            // تحميل العلاقات لحساب الإحصائيات
            $doctor->loadCount(['appointments', 'reviews', 'facilities']);

            return view('admin.doctors.delete', compact('doctor'));
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل صفحة الحذف: ' . $e->getMessage());
        }
    }

    /**
     * حذف طبيب (Soft Delete)
     */
    public function destroy(Request $request, Doctor $doctor)
    {
        try {
            $request->validate([
                'reason' => 'nullable|string|max:500'
            ]);

            // تسجيل سبب الحذف إذا كان موجوداً
            $reason = $request->input('reason');
            if ($reason) {
                // يمكنك حفظ سبب الحذف في حقل مخصص أو في جدول منفصل للسجلات
                // مثال: $doctor->delete_reason = $reason;
            }

            // حذف الطبيب باستخدام الخدمة (Soft Delete)
            $this->doctorService->deleteDoctor($doctor);

            return redirect()->route('admin.doctors.index')
                ->with('success', 'تم حذف الطبيب بنجاح. يمكنك استعادته من سلة المحذوفات.');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الطبيب: ' . $e->getMessage());
        }
    }

    /**
     * استعادة طبيب محذوف
     */
    public function restore(Request $request, $id)
    {
        try {
            $doctor = Doctor::withTrashed()->findOrFail($id);
            $this->doctorService->restoreDoctor($doctor);

            return redirect()->route('admin.doctors.index')
                ->with('success', 'تم استعادة الطبيب بنجاح.');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء استعادة الطبيب: ' . $e->getMessage());
        }
    }

    /**
     * حذف طبيب نهائيًا (Force Delete)
     */
    public function forceDestroy(Request $request, $id)
    {
        try {
            $doctor = Doctor::withTrashed()->findOrFail($id);
            $this->doctorService->forceDeleteDoctor($doctor);

            return redirect()->route('admin.doctors.index')
                ->with('success', 'تم حذف الطبيب نهائيًا بنجاح.');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحذف النهائي للطبيب: ' . $e->getMessage());
        }
    }

    /**
     * التحقق من صحة بيانات الطبيب
     */
    private function validateDoctorData(Request $request, $doctorId = null)
    {
        $rules = [
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('doctors')->ignore($doctorId)
            ],
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                Rule::unique('doctors')->ignore($doctorId)
            ],
            'phone' => 'nullable|string|max:15',
            'bio' => 'nullable|string|max:1000',
            'specializations' => 'required|array|min:1',
            'specializations.*' => 'exists:specialties,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // إضافة قاعدة كلمة المرور فقط عند الإنشاء
        if (!$doctorId) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }
        return $request->validate($rules);
    }

    /**
     * تغيير حالة توثيق الطبيب
     */
    public function toggleVerification(Request $request, Doctor $doctor)
    {
        try {
            $this->doctorService->toggleVerification($doctor);

            $status = $doctor->is_verified ? 'موثق' : 'غير موثق';
            $message = "تم تغيير حالة توثيق الطبيب إلى: $status";

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء تغيير حالة التوثيق: ' . $e->getMessage());
        }
    }


    /**
     * عرض قائمة الأطباء المحذوفين
     */
    public function trashed(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $doctors = Doctor::onlyTrashed()
                ->with(['specialties'])
                ->orderBy('deleted_at', 'desc')
                ->paginate($perPage);

            return view('admin.doctors.trashed', [
                'doctors' => $doctors
            ]);
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب الأطباء المحذوفين: ' . $e->getMessage());
        }
    }
}