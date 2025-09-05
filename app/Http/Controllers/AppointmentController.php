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
        if (!Gate::allows('create', Appointment::class)) {
            abort(403, 'Unauthorized');
        }

        $patients = Patient::all();
        $doctors = Doctor::all();
        $facilities = Facility::all();
        $services = Service::all();
        $insuranceCompanies = InsuranceCompany::all();

        return view('admin.appointments.create', compact('patients', 'doctors', 'facilities', 'services', 'insuranceCompanies'));
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

            // الحصول على doctor_facility_id
            $doctorFacility = DB::table('doctor_facility')
                ->where('doctor_id', $validated['doctor_id'])
                ->where('facility_id', $validated['facility_id'])
                ->first();

            if (!$doctorFacility) {
                return back()->withErrors(['msg' => 'الطبيب غير متاح في هذه المنشأة'])->withInput();
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
                return back()->withErrors(['msg' => 'الطبيب غير متاح في هذه المنشأة'])->withInput();
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
}