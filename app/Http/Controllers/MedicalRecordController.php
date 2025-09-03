<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Doctor;
use App\Services\MedicalRecordService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class MedicalRecordController extends Controller
{
    protected $medicalRecordService;

    public function __construct(MedicalRecordService $medicalRecordService)
    {
        $this->medicalRecordService = $medicalRecordService;

        $this->middleware('auth:admin,doctor,patient');
    }

    /**
     * عرض قائمة السجلات الطبية
     */
    public function index(Request $request)
    {
        try {
            $user = request()->user();
            $medicalRecords = [];

            if (Gate::allows('viewAny', MedicalRecord::class)) {
                if ($user->guard(['admin'])->check()) {
                    // مسؤول - عرض جميع السجلات
                    $medicalRecords = MedicalRecord::with(['patient', 'doctor', 'facility'])
                        ->orderBy('record_date', 'desc')
                        ->paginate(10);
                } elseif ($user->guard(['doctor'])->check()) {
                    // طبيب - عرض سجلاته فقط
                    $medicalRecords = $this->medicalRecordService->getDoctorRecords(
                        $user,
                        $request->all()
                    );
                } elseif ($user->guard(['patient'])->check()) {
                    // مريض - عرض سجلاته فقط
                    $medicalRecords = $this->medicalRecordService->getPatientRecords(
                        $user,
                        $request->all()
                    );
                }
            }

            return view('medical_record.index', compact('medicalRecords'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب السجلات الطبية: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج إنشاء سجل طبي
     */
    public function create()
    {
        if (!Gate::allows('create', MedicalRecord::class)) {
            abort(403, 'Unauthorized');
        }

        $patients = Patient::active()->get();
        $doctors = Doctor::active()->get();

        return view('medical_record.create', compact('patients', 'doctors'));
    }

    /**
     * حفظ سجل طبي جديد
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create', MedicalRecord::class)) {
            abort(403, 'Unauthorized');
        }
        try {
            $validated = $this->validateMedicalRecordData($request);

            $medicalRecord = $this->medicalRecordService->createMedicalRecord($validated);
            if (request()->user()->gaurd == 'admin') {
                return redirect()->route('admin.medical_record.show', $medicalRecord);
            }
            return redirect()->route('medical_record.show', $medicalRecord)
                ->with('success', 'تم إنشاء السجل الطبي بنجاح.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء السجل الطبي: ' . $e->getMessage());
        }
    }

    /**
     * عرض سجل طبي
     */
    public function show(MedicalRecord $medicalRecord)
    {
        if (!Gate::allows('view', $medicalRecord)) {
            abort(403, 'Unauthorized');
        }

        try {
            $medicalRecord->load(['patient', 'doctor', 'facility', 'appointment']);
            if (request()->user()->gaurd == 'admin') {
                return view('admin.medical_records.show', compact('medicalRecord'));
            }
            return view('medical_record.show', compact('medicalRecord'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب بيانات السجل الطبي: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج تعديل سجل طبي
     */
    public function edit(MedicalRecord $medicalRecord)
    {
        if (!Gate::allows('update', $medicalRecord)) {
            abort(403, 'Unauthorized');
        }

        try {
            $patients = Patient::active()->get();
            $doctors = Doctor::active()->get();

            return view('medical_record.edit', compact('medicalRecord', 'patients', 'doctors'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل صفحة التعديل: ' . $e->getMessage());
        }
    }

    /**
     * تحديث سجل طبي
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        if (!Gate::allows('update', $medicalRecord)) {
            abort(403, 'Unauthorized');
        }

        try {
            $validated = $this->validateMedicalRecordData($request, $medicalRecord->id);

            $this->medicalRecordService->updateMedicalRecord($medicalRecord, $validated);
            if (request()->user()->gaurd == 'admin') {
                return redirect()->route('admin.medical_record.show', $medicalRecord);
            }
            return redirect()->route('medical_record.show', $medicalRecord)
                ->with('success', 'تم تحديث السجل الطبي بنجاح.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث السجل الطبي: ' . $e->getMessage());
        }
    }

    /**
     * حذف سجل طبي
     */
    public function destroy(MedicalRecord $medicalRecord)
    {
        if (!Gate::allows('delete', $medicalRecord)) {
            abort(403, 'Unauthorized');
        }

        try {
            $this->medicalRecordService->deleteMedicalRecord($medicalRecord);

            return redirect()->route('medical_records.index')
                ->with('success', 'تم حذف السجل الطبي بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف السجل الطبي: ' . $e->getMessage());
        }
    }

    /**
     * التحقق من صحة بيانات السجل الطبي
     */
    private function validateMedicalRecordData(Request $request, $medicalRecordId = null)
    {
        $rules = [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'facility_id' => 'required|exists:facilities,id',
            'record_type' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'symptoms' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'prescription' => 'nullable|string',
            'test_results' => 'nullable|string',
            'notes' => 'nullable|string',
            'record_date' => 'required|date',
            'follow_up_date' => 'nullable|date|after:record_date',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            'status' => 'required|in:active,completed,cancelled',
            'is_urgent' => 'boolean',
            'requires_follow_up' => 'boolean'
        ];

        return $request->validate($rules);
    }

    /**
     * عرض سجلات مريض معين
     */
    public function patientRecords(Patient $patient)
    {
        if (!Gate::allows('viewPatientRecords', $patient->id)) {
            abort(403, 'Unauthorized');
        }

        try {
            $medicalRecords = $this->medicalRecordService->getPatientRecords($patient);

            return view('medical_record.patient', compact('patient', 'medicalRecords'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب سجلات المريض: ' . $e->getMessage());
        }
    }
}