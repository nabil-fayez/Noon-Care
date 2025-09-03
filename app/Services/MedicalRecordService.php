<?php

namespace App\Services;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MedicalRecordService
{
    /**
     * إنشاء سجل طبي جديد
     */
    public function createMedicalRecord(array $data): MedicalRecord
    {
        return DB::transaction(function () use ($data) {
            // معالجة المرفق إذا وجد
            if (isset($data['attachment'])) {
                $attachmentInfo = $this->uploadAttachment($data['attachment']);
                $data['attachment_path'] = $attachmentInfo['path'];
                $data['attachment_name'] = $attachmentInfo['name'];
                unset($data['attachment']);
            }

            return MedicalRecord::create($data);
        });
    }

    /**
     * تحديث سجل طبي
     */
    public function updateMedicalRecord(MedicalRecord $medicalRecord, array $data): MedicalRecord
    {
        return DB::transaction(function () use ($medicalRecord, $data) {
            // معالجة المرفق إذا وجد
            if (isset($data['attachment'])) {
                // حذف المرفق القديم إذا كان موجوداً
                if ($medicalRecord->attachment_path) {
                    Storage::delete($medicalRecord->attachment_path);
                }

                $attachmentInfo = $this->uploadAttachment($data['attachment']);
                $data['attachment_path'] = $attachmentInfo['path'];
                $data['attachment_name'] = $attachmentInfo['name'];
                unset($data['attachment']);
            }

            $medicalRecord->update($data);

            return $medicalRecord;
        });
    }

    /**
     * حذف سجل طبي
     */
    public function deleteMedicalRecord(MedicalRecord $medicalRecord): bool
    {
        return DB::transaction(function () use ($medicalRecord) {
            // حذف المرفق إذا كان موجوداً
            if ($medicalRecord->attachment_path) {
                Storage::delete($medicalRecord->attachment_path);
            }

            return $medicalRecord->delete();
        });
    }

    /**
     * رفع المرفق
     */
    private function uploadAttachment($file): array
    {
        $path = $file->store('medical-records/attachments', 'public');
        $name = $file->getClientOriginalName();

        return [
            'path' => $path,
            'name' => $name
        ];
    }

    /**
     * الحصول على السجلات الطبية لمريض معين
     */
    public function getPatientRecords(Patient $patient, array $filters = [], $perPage = 10)
    {
        $query = $patient->medicalRecords()->with(['doctor', 'facility']);

        // التصفية حسب النوع
        if (!empty($filters['record_type'])) {
            $query->where('record_type', $filters['record_type']);
        }

        // التصفية حسب الحالة
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // التصفية حسب التاريخ
        if (!empty($filters['start_date'])) {
            $query->where('record_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('record_date', '<=', $filters['end_date']);
        }

        // الترتيب
        $orderBy = $filters['order_by'] ?? 'record_date';
        $orderDir = $filters['order_dir'] ?? 'desc';

        return $query->orderBy($orderBy, $orderDir)->paginate($perPage);
    }

    /**
     * الحصول على السجلات الطبية لطبيب معين
     */
    public function getDoctorRecords(Doctor $doctor, array $filters = [], $perPage = 10)
    {
        $query = $doctor->medicalRecords()->with(['patient', 'facility']);

        // نفس عوامل التصفية السابقة
        // ...

        return $query->orderBy('record_date', 'desc')->paginate($perPage);
    }

    /**
     * الحصول على إحصائيات السجلات الطبية
     */
    public function getStatistics(): array
    {
        return [
            'total' => MedicalRecord::count(),
            'active' => MedicalRecord::active()->count(),
            'urgent' => MedicalRecord::urgent()->count(),
            'requires_follow_up' => MedicalRecord::requiresFollowUp()->count(),
            'by_type' => MedicalRecord::select('record_type', DB::raw('count(*) as count'))
                ->groupBy('record_type')
                ->get()
                ->pluck('count', 'record_type')
        ];
    }
}