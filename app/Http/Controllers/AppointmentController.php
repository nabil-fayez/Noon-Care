<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Facility;
use App\Models\Patient;
use App\Models\Service;
use App\Models\InsuranceCompany;
use App\Services\AppointmentService;
use App\Services\NotificationService;
use App\Services\ErrorLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AppointmentController extends Controller
{
    protected $appointmentService;
    protected $notificationService;

    public function __construct(AppointmentService $appointmentService, NotificationService $notificationService)
    {
        $this->appointmentService = $appointmentService;
        $this->notificationService = $notificationService;
    }
    public function patientIndex(Request $request)
    {
        $patient = auth()->guard('patient')->user();

        $appointments = $patient->appointments()
            ->with(['doctor', 'facility', 'service'])
            ->orderBy('appointment_datetime', 'desc')
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    /**
     * عرض قائمة الحجوزات
     */
    public function index(Request $request)
    {
        if (!Gate::allows('viewAny', Appointment::class)) {
            abort(403, 'Unauthorized');
        }

        try {
            $perPage = $request->get('per_page', 10);
            $appointments = $this->appointmentService->getAppointments($request->all(), $perPage);
            $doctors = Doctor::all();
            $facilities = Facility::all();

            return view('admin.appointments.index', compact('appointments', 'doctors', 'facilities'));
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات الحجوزات: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج إنشاء حجز جديد
     */
    public function create()
    {
        return view('admin.appointments.create');
    }
    public function patientCreate()
    {
        $doctors = Doctor::verified()->with('specialties')->get();
        $facilities = Facility::active()->get();
        $services = Service::all();
        $insuranceCompanies = InsuranceCompany::all();

        return view('patient.appointments.create', compact('doctors', 'facilities', 'services', 'insuranceCompanies'));
    }

    /**
     * حفظ حجز جديد
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create', Appointment::class)) {
            abort(403, 'Unauthorized');
        }

        try {
            $validated = $this->validateAppointmentData($request);

            $doctorFacility = DB::table('doctor_facility')
                ->where('doctor_id', $validated['doctor_id'])
                ->where('facility_id', $validated['facility_id'])
                ->first();

            dd('', $doctorFacility);
            if (!$doctorFacility) {

                return back()->withErrors('الطبيب غير متاح في هذه المنشأة')->withInput(['doctor_id', 'facility_id']);
            }

            $validated['doctor_facility_id'] = $doctorFacility->id;

            $appointment = $this->appointmentService->createAppointment($validated);

            // إرسال إشعار للمسؤول
            $this->notificationService->sendNotification(
                1,
                'admin',
                'حجز جديد',
                'تم إضافة حجز جديد للمريض: ' . $appointment->patient->full_name
            );

            return redirect()->route('admin.appointments.index')
                ->with('success', 'تم إنشاء الحجز بنجاح');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء الحجز: ' . $e->getMessage());
        }
    }
    public function patientShow(Appointment $appointment)
    {
        // التحقق من أن الموعد يخص المريض الحالي
        if ($appointment->patient_id !== auth()->guard('patient')->user()->id) {
            abort(403, 'غير مصرح لك بالوصول إلى هذا الموعد');
        }

        $appointment->load(['doctor', 'facility', 'service', 'insuranceCompany']);

        return view('patient.appointments.show', compact('appointment'));
    }

    /**
     * عرض بيانات حجز
     */
    public function show(Request $request, Appointment $appointment)
    {
        if (!Gate::allows('view', $appointment)) {
            abort(403, 'Unauthorized');
        }

        try {
            $appointment->load(['patient', 'doctor', 'facility', 'service', 'insuranceCompany', 'payment', 'review']);
            return view('admin.appointments.show', compact('appointment'));
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات الحجز: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج تعديل حجز
     */
    public function edit(Request $request, Appointment $appointment)
    {
        if (!Gate::allows('update', $appointment)) {
            abort(403, 'Unauthorized');
        }

        try {
            $patients = Patient::all();
            $doctors = Doctor::all();
            $facilities = Facility::all();
            $services = Service::all();
            $insuranceCompanies = InsuranceCompany::all();

            return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors', 'facilities', 'services', 'insuranceCompanies'));
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
     * تحديث بيانات الحجز
     */
    public function update(Request $request, Appointment $appointment)
    {
        if (!Gate::allows('update', $appointment)) {
            abort(403, 'Unauthorized');
        }

        try {
            $validated = $this->validateAppointmentData($request, $appointment->id);

            // الحصول على doctor_facility_id
            $doctorFacility = DB::table('doctor_facility')
                ->where('doctor_id', $validated['doctor_id'])
                ->where('facility_id', $validated['facility_id'])
                ->first();

            if (!$doctorFacility) {
                return back()->withErrors(['error' => 'الطبيب غير متاح في هذه المنشأة'])->withInput();
            }

            $validated['doctor_facility_id'] = $doctorFacility->id;

            $this->appointmentService->updateAppointment($appointment, $validated);

            return redirect()->route('admin.appointments.show', $appointment)
                ->with('success', 'تم تحديث الحجز بنجاح');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الحجز: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج تأكيد الحذف
     */
    public function delete(Request $request, Appointment $appointment)
    {
        if (!Gate::allows('delete', $appointment)) {
            abort(403, 'Unauthorized');
        }

        try {
            return view('admin.appointments.delete', compact('appointment'));
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
     * حذف حجز (Soft Delete)
     */
    public function destroy(Request $request, Appointment $appointment)
    {
        if (!Gate::allows('delete', $appointment)) {
            abort(403, 'Unauthorized');
        }

        try {
            $this->appointmentService->deleteAppointment($appointment);

            return redirect()->route('admin.appointments.index')
                ->with('success', 'تم حذف الحجز بنجاح');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الحجز: ' . $e->getMessage());
        }
    }

    /**
     * استعادة حجز محذوف
     */
    public function restore(Request $request, $id)
    {
        try {
            $appointment = Appointment::onlyTrashed()->findOrFail($id);
            $this->appointmentService->restoreAppointment($appointment);

            return redirect()->route('admin.appointments.index')
                ->with('success', 'تم استعادة الحجز بنجاح');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء استعادة الحجز: ' . $e->getMessage());
        }
    }

    /**
     * حذف حجز نهائيًا (Force Delete)
     */
    public function forceDestroy(Request $request, $id)
    {
        try {
            $appointment = Appointment::onlyTrashed()->findOrFail($id);
            $this->appointmentService->forceDeleteAppointment($appointment);

            return redirect()->route('admin.appointments.index')
                ->with('success', 'تم حذف الحجز نهائيًا بنجاح');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحذف النهائي للحجز: ' . $e->getMessage());
        }
    }
    public function patientCancel(Request $request, Appointment $appointment)
    {
        // التحقق من أن الموعد يخص المريض الحالي
        if ($appointment->patient_id !== auth()->guard('patient')->user()->id) {
            abort(403, 'غير مصرح لك بإلغاء هذا الموعد');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        try {
            $appointment->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason
            ]);

            // إرسال إشعار للإدارة والطبيب
            $this->notificationService->sendNotification(
                $appointment->doctor_id,
                'doctor',
                'إلغاء موعد',
                'تم إلغاء الموعد من قبل المريض: ' . $appointment->patient->full_name
            );

            $this->notificationService->sendNotification(
                1, // افتراضيًا للمسؤول الأول
                'admin',
                'إلغاء موعد',
                'تم إلغاء الموعد من قبل المريض: ' . $appointment->patient->full_name
            );

            return redirect()->route('patient.appointments.index')
                ->with('success', 'تم إلغاء الموعد بنجاح');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ أثناء إلغاء الموعد: " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء إلغاء الموعد: ' . $e->getMessage());
        }
    }
    /**
     * تحديث حالة الحجز
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        if (!Gate::allows('update', $appointment)) {
            abort(403, 'Unauthorized');
        }

        try {
            $request->validate([
                'status' => 'required|in:new,confirmed,cancelled,done'
            ]);

            $this->appointmentService->updateAppointmentStatus($appointment, $request->status);

            return back()->with('success', 'تم تحديث حالة الحجز بنجاح');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث حالة الحجز: ' . $e->getMessage());
        }
    }

    /**
     * عرض الحجوزات المحذوفة
     */
    public function trashed(Request $request)
    {
        if (!Gate::allows('viewTrashed', Appointment::class)) {
            abort(403, 'Unauthorized');
        }

        try {
            $perPage = $request->get('per_page', 10);
            $appointments = Appointment::onlyTrashed()
                ->with(['patient', 'doctor', 'facility'])
                ->orderBy('deleted_at', 'desc')
                ->paginate($perPage);

            return view('admin.appointments.trashed', compact('appointments'));
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ جديد! : " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب الحجوزات المحذوفة: ' . $e->getMessage());
        }
    }

    /**
     * التحقق من صحة بيانات الحجز
     */
    private function validateAppointmentData(Request $request, $appointmentId = null)
    {
        $rules = [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'facility_id' => 'required|exists:facilities,id',
            'service_id' => 'required|exists:services,id',
            'insurance_company_id' => 'nullable|exists:insurance_companies,id',
            'appointment_datetime' => 'required|date',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:new,confirmed,cancelled,done',
            'notes' => 'nullable|string',
            'price' => 'nullable|numeric'
        ];

        return $request->validate($rules);
    }
    /**
     * البحث عن المرضى عبر api
     */
    public function apiPatients(Request $request)
    {
        $search = $request->get('search');

        $patients = Patient::select('id', 'first_name', 'last_name', 'phone')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        $formattedPatients = $patients->map(function ($patient) {
            return [
                'id' => $patient->id,
                'text' => "{$patient->first_name} {$patient->last_name} - {$patient->phone}"
            ];
        });

        return response()->json([
            'data' => $formattedPatients,
            'next_page_url' => $patients->nextPageUrl()
        ]);
    }

    /**
     * البحث عن الأطباء عبر api
     */
    public function apiDoctors(Request $request)
    {
        $search = $request->get('search');

        $doctors = Doctor::select('id', 'first_name', 'last_name')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        $formattedDoctors = $doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'text' => "{$doctor->first_name} {$doctor->last_name}"
            ];
        });

        return response()->json([
            'data' => $formattedDoctors,
            'next_page_url' => $doctors->nextPageUrl()
        ]);
    }

    /**
     * البحث عن المنشآت عبر api
     */
    public function apiFacilities(Request $request)
    {
        $search = $request->get('search');

        $facilities = Facility::select('id', 'business_name')
            ->when($search, function ($query) use ($search) {
                return $query->where('business_name', 'like', "%{$search}%");
            })
            ->paginate(10);

        $formattedFacilities = $facilities->map(function ($facility) {
            return [
                'id' => $facility->id,
                'text' => $facility->business_name
            ];
        });

        return response()->json([
            'data' => $formattedFacilities,
            'next_page_url' => $facilities->nextPageUrl()
        ]);
    }

    /**
     * البحث عن الخدمات عبر api
     */
    public function apiServices(Request $request)
    {
        $search = $request->get('search');

        $services = Service::select('id', 'name')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10);

        $formattedServices = $services->map(function ($service) {
            return [
                'id' => $service->id,
                'text' => $service->name
            ];
        });

        return response()->json([
            'data' => $formattedServices,
            'next_page_url' => $services->nextPageUrl()
        ]);
    }

    /**
     * البحث عن شركات التأمين عبر api
     */
    public function apiInsuranceCompanies(Request $request)
    {
        $search = $request->get('search');

        $companies = InsuranceCompany::select('id', 'name')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10);

        $formattedCompanies = $companies->map(function ($company) {
            return [
                'id' => $company->id,
                'text' => $company->name
            ];
        });

        return response()->json([
            'data' => $formattedCompanies,
            'next_page_url' => $companies->nextPageUrl()
        ]);
    }

    /**
     * الحصول على الأوقات المتاحة للطبيب في منشأة معينة في تاريخ محدد
     */
    public function apiAvailableTimes(Request $request)
    {
        $doctorId = $request->get('doctor_id');
        $facilityId = $request->get('facility_id');
        $date = $request->get('date');

        if (!$doctorId || !$facilityId || !$date) {
            return response()->json(['available_times' => []]);
        }

        // الأوقات المتاحة من 8 صباحاً إلى 8 مساءاً بفاصل 30 دقيقة
        $startTime = strtotime('08:00');
        $endTime = strtotime('20:00');
        $interval = 30 * 60; // 30 دقيقة بالثواني

        $availableTimes = [];

        for ($time = $startTime; $time <= $endTime; $time += $interval) {
            $timeFormatted = date('H:i', $time);
            $datetime = $date . ' ' . $timeFormatted;

            // التحقق من وجود موعد في هذا الوقت
            $isAvailable = !Appointment::where('doctor_id', $doctorId)
                ->where('facility_id', $facilityId)
                ->where('appointment_datetime', 'like', "%{$datetime}%")
                ->whereNotIn('status', ['cancelled', 'done'])
                ->exists();

            $availableTimes[] = [
                'time' => $timeFormatted,
                'formatted_time' => date('h:i A', $time),
                'is_available' => $isAvailable
            ];
        }

        return response()->json(['available_times' => $availableTimes]);
    }
    /**
     * البحث عن المرضى عبر HTMX
     */
    public function ajaxPatients(Request $request)
    {
        $search = $request->get('search');

        $patients = Patient::select('id', 'first_name', 'last_name', 'phone')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->limit(10)
            ->get();

        $formattedPatients = $patients->map(function ($patient) {
            return [
                'id' => $patient->id,
                'text' => "{$patient->first_name} {$patient->last_name} - {$patient->phone}"
            ];
        });

        return response()->json($formattedPatients);
    }

    // كرر نفس النمط لباقي الدوال (doctors, facilities, services, insurance-companies)

    /**
     * الحصول على الأوقات المتاحة للطبيب في منشأة معينة في تاريخ محدد
     */
    public function ajaxAvailableTimes(Request $request)
    {
        $doctorId = $request->get('doctor_id');
        $facilityId = $request->get('facility_id');
        $date = $request->get('appointment_date');

        if (!$doctorId || !$facilityId || !$date) {
            return response()->json(['available_times' => []]);
        }

        // الأوقات المتاحة من 8 صباحاً إلى 8 مساءاً بفاصل 30 دقيقة
        $startTime = strtotime('08:00');
        $endTime = strtotime('20:00');
        $interval = 30 * 60; // 30 دقيقة بالثواني

        $availableTimes = [];

        for ($time = $startTime; $time <= $endTime; $time += $interval) {
            $timeFormatted = date('H:i', $time);
            $datetime = $date . ' ' . $timeFormatted;

            // التحقق من وجود موعد في هذا الوقت
            $isAvailable = !Appointment::where('doctor_id', $doctorId)
                ->where('facility_id', $facilityId)
                ->where('appointment_datetime', 'like', "%{$datetime}%")
                ->whereNotIn('status', ['cancelled', 'done'])
                ->exists();

            $availableTimes[] = [
                'time' => $timeFormatted,
                'formatted_time' => date('h:i A', $time),
                'is_available' => $isAvailable
            ];
        }

        return response()->json(['available_times' => $availableTimes]);
    }
}