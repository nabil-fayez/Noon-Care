<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Facility;
use App\Models\Patient;
use App\Services\PatientService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Policies\PatientPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\ErrorLogService;

class PatientController extends Controller
{
    protected $patientService;
    protected $notificationService;

    public function __construct(PatientService $patientService, NotificationService $notificationService)
    {
        $this->patientService = $patientService;
        $this->notificationService = $notificationService;

        // استخدام الـ guards للتحقق من المصادقة
        $this->middleware('auth:admin,patient');
    }

    /**
     * عرض قائمة المرضى
     */
    public function index(Request $request)
    {
        // التحقق من الصلاحية باستخدام السياسة
        if (!Gate::allows('viewAny', Patient::class)) {
            abort(403, 'Unauthorized');
        }

        try {
            $perPage = $request->get('per_page', 10);
            $patients = $this->patientService->getPatients($request->all(), $perPage);

            return view('admin.patients.index', compact('patients'));
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );

            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات المرضى: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج إنشاء مريض جديد
     */
    public function create()
    {
        if (!Gate::allows('create', Patient::class)) {
            abort(403, 'Unauthorized');
        }

        return view('admin.patients.create');
    }

    /**
     * حفظ مريض جديد
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create', Patient::class)) {
            abort(403, 'Unauthorized');
        }

        try {
            $validated = $this->validatePatientData($request);

            $patient = $this->patientService->createPatient($validated);

            // إرسال إشعار للمسؤول
            $this->notificationService->sendNotification(
                1, // افتراضيًا للمسؤول الأول
                'admin',
                'مريض جديد',
                'تم إضافة مريض جديد: ' . $patient->full_name
            );

            return redirect()->route('admin.patients.index')
                ->with('success', 'تم إنشاء المريض بنجاح.');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء المريض: ' . $e->getMessage());
        }
    }


    /**
     * عرض بيانات مريض
     */
    public function show(Request $request, Patient $patient)
    {
        if (!Gate::allows('view', $patient)) {
            abort(403, 'Unauthorized');
        }

        try {
            $patient->load([
                'appointments.doctor',
                'appointments.facility',
                'medicalRecords'
            ]);
            // تحديد الـ view بناءً على نوع المستخدم
            if (Auth::guard('admin')->check()) {
                return view('admin.patients.show', compact('patient'));
            } else {
                return view('patient.show', compact('patient'));
            }
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );

            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات المريض: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج تعديل مريض
     */
    public function edit(Request $request, Patient $patient)
    {
        if (!Gate::allows('update', $patient)) {
            abort(403, 'Unauthorized');
        }

        try {
            if (request()->user()->gaurd == 'admin') {
                return view("admin.patients.update", compact('patient'));
            } else {
                return view("patient.update", compact('patient'));
            }
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
     * تحديث بيانات مريض
     */
    public function update(Request $request, Patient $patient)
    {
        if (!Gate::allows('update', $patient)) {
            abort(403, 'Unauthorized');
        }

        try {
            $validated = $this->validatePatientData($request, $patient->id);

            $this->patientService->updatePatient($patient, $validated);

            // تحديد route ال redirect بناءً على نوع المستخدم
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.patient.show', $patient)
                    ->with('success', 'تم تحديث بيانات المريض بنجاح.');
            } else {
                return redirect()->route('patient.profile')
                    ->with('success', 'تم تحديث بياناتك بنجاح.');
            }
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث المريض: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج تأكيد الحذف
     */
    public function delete(Request $request, Patient $patient)
    {
        try {
            return view('admin.patients.delete', compact('patient'));
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
     * حذف مريض
     */
    public function destroy(Request $request, Patient $patient)
    {
        try {
            $this->patientService->deletePatient($patient);

            return redirect()->route('admin.patients.index')
                ->with('success', 'تم حذف المريض بنجاح.');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );

            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المريض: ' . $e->getMessage());
        }
    }

    /**
     * تبديل حالة المريض
     */
    public function toggleStatus(Request $request, Patient $patient)
    {
        try {
            $this->patientService->toggleStatus($patient);

            $status = $patient->is_active ? 'مفعل' : 'معطل';
            return redirect()->back()->with('success', "تم تغيير حالة المريض إلى: $status");
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );

            return redirect()->back()->with('error', 'حدث خطأ أثناء تغيير حالة المريض: ' . $e->getMessage());
        }
    }

    /**
     * التحقق من صحة بيانات المريض
     */
    private function validatePatientData(Request $request, $patientId = null)
    {
        $rules = [
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('patients')->ignore($patientId)
            ],
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                Rule::unique('patients')->ignore($patientId)
            ],
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:15',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if (!$patientId) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $request->validate($rules);
    }

    public function dashboard(Request $request)
    {
        try {
            $patient = auth()->guard('patient')->user();

            $upcomingAppointments = $patient->appointments()
                ->where('status', 'confirmed')
                ->where('appointment_datetime', '>', now())
                ->count();

            $completedAppointments = $patient->appointments()
                ->where('status', 'done')
                ->count();

            $medicalRecordsCount = $patient->medicalRecords()->count();

            $recentAppointments = $patient->appointments()
                ->with('doctor.specialties')
                ->where('appointment_datetime', '>', now())
                ->orderBy('appointment_datetime')
                ->take(5)
                ->get();

            $recentMedicalRecords = $patient->medicalRecords()
                ->with('doctor')
                ->orderBy('record_date', 'desc')
                ->take(4)
                ->get();

            return view('patient.dashboard', compact(
                'upcomingAppointments',
                'completedAppointments',
                'medicalRecordsCount',
                'recentAppointments',
                'recentMedicalRecords'
            ));
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );

            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل لوحة التحكم: ' . $e->getMessage());
        }
    }

    /**
     * عرض السجل الطبي للمريض
     */
    public function medicalHistory(Request $request, Patient $patient)
    {
        try {
            $medicalRecords = $patient->medicalRecords()
                ->with('doctor')
                ->when($request->has('record_type'), function ($query) use ($request) {
                    return $query->where('record_type', $request->record_type);
                })
                ->when($request->has('start_date'), function ($query) use ($request) {
                    return $query->where('record_date', '>=', $request->start_date);
                })
                ->orderBy('record_date', 'desc')
                ->paginate(10);

            // جلب قائمة الأطباء لإضافتها في الـ modal
            $doctors = Doctor::where('is_verified', true)->get();
            $facilities = Facility::where('is_active', true)->get();
            if (request()->user()->gaurd == 'admin') {
                return view('admin.patients.medical-history', compact('medicalRecords', 'patient', 'doctors', 'facilities'));
            } else {
                return view('patient.medical-history', compact('medicalRecords'));
            }
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );

            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب السجل الطبي: ' . $e->getMessage());
        }
    }
}
