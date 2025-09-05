<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\WorkingHour;
use App\Models\DoctorFacility;
use App\Notifications\AppointmentConfirmed;
use App\Notifications\AppointmentCancelled;
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentService
{
    public function getAppointments($filters = [], $perPage = 10)
    {
        $query = Appointment::with(['patient', 'doctor', 'facility', 'service']);

        if (isset($filters['search'])) {
            $query->whereHas('patient', function ($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('last_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('phone', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['doctor_id'])) {
            $query->where('doctor_id', $filters['doctor_id']);
        }

        if (isset($filters['facility_id'])) {
            $query->where('facility_id', $filters['facility_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['date'])) {
            $query->whereDate('appointment_datetime', $filters['date']);
        }

        return $query->orderBy('appointment_datetime', 'desc')->paginate($perPage);
    }

    public function createAppointment($data)
    {
        return Appointment::create($data);
    }

    public function updateAppointment(Appointment $appointment, $data)
    {
        return $appointment->update($data);
    }

    public function deleteAppointment(Appointment $appointment)
    {
        return $appointment->delete();
    }

    public function restoreAppointment(Appointment $appointment)
    {
        return $appointment->restore();
    }

    public function forceDeleteAppointment(Appointment $appointment)
    {
        return $appointment->forceDelete();
    }

    public function updateAppointmentStatus(Appointment $appointment, $status)
    {
        return $appointment->update(['status' => $status]);
    }

    public function bookAppointment($patientId, $doctorId, $facilityId, $datetime, $serviceId = null, $notes = null)
    {
        try {
            DB::beginTransaction();

            // التحقق من أن الطبيب يعمل في المنشأة
            $doctorFacility = DoctorFacility::where('doctor_id', $doctorId)
                ->where('facility_id', $facilityId)
                ->where('status', 'active')
                ->where('available_for_appointments', true)
                ->first();

            if (!$doctorFacility) {
                throw new \Exception('الطبيب غير متاح في هذه المنشأة');
            }

            // التحقق من أن الوقت ضمن أوقات العمل
            $dayOfWeek = Carbon::parse($datetime)->format('D');
            $time = Carbon::parse($datetime)->format('H:i:s');

            $workingHour = WorkingHour::where('doctor_id', $doctorId)
                ->where('facility_id', $facilityId)
                ->where('day_of_week', $dayOfWeek)
                ->where('is_available', true)
                ->where('start_time', '<=', $time)
                ->where('end_time', '>=', $time)
                ->first();

            if (!$workingHour) {
                throw new \Exception('الوقت المحدد خارج أوقات العمل');
            }

            // التحقق من عدم وجود موعد متعارض
            $existingAppointment = Appointment::where('doctor_id', $doctorId)
                ->where('facility_id', $facilityId)
                ->where('appointment_datetime', $datetime)
                ->whereIn('status', ['new', 'confirmed'])
                ->exists();

            if ($existingAppointment) {
                throw new \Exception('هذا الموعد محجوز مسبقاً');
            }

            // الحصول على سعر الخدمة إذا كانت متوفرة
            $price = null;
            if ($serviceId) {
                $pricing = DB::table('facility_service_pricing')
                    ->where('facility_id', $facilityId)
                    ->where('service_id', $serviceId)
                    ->first();

                if ($pricing) {
                    $price = $pricing->price;
                }
            }

            // إنشاء الموعد
            $appointment = Appointment::create([
                'doctor_id' => $doctorId,
                'facility_id' => $facilityId,
                'doctor_facility_id' => $doctorFacility->id,
                'patient_id' => $patientId,
                'service_id' => $serviceId,
                'appointment_datetime' => $datetime,
                'duration' => $workingHour->slot_duration,
                'notes' => $notes,
                'price' => $price,
                'status' => 'new'
            ]);

            // إرسال إشعار التأكيد
            $patient = \App\Models\Patient::find($patientId);
            $patient->notify(new AppointmentConfirmed($appointment));

            DB::commit();

            return $appointment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('فشل في حجز الموعد: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getAvailableSlots($doctorId, $facilityId, $date)
    {
        $dayOfWeek = Carbon::parse($date)->format('D');

        // الحصول على أوقات العمل لهذا اليوم
        $workingHours = WorkingHour::where('doctor_id', $doctorId)
            ->where('facility_id', $facilityId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$workingHours) {
            return [];
        }

        // الحصول على المواعيد المحجوزة مسبقاً
        $bookedAppointments = Appointment::where('doctor_id', $doctorId)
            ->where('facility_id', $facilityId)
            ->whereDate('appointment_datetime', $date)
            ->whereIn('status', ['new', 'confirmed'])
            ->pluck('appointment_datetime')
            ->map(function ($datetime) {
                return Carbon::parse($datetime)->format('H:i');
            })
            ->toArray();

        // إنشاء قائمة بالأوقات المتاحة
        $availableSlots = [];
        $startTime = Carbon::parse($workingHours->start_time);
        $endTime = Carbon::parse($workingHours->end_time);
        $slotDuration = $workingHours->slot_duration;

        while ($startTime->lessThan($endTime)) {
            $slotTime = $startTime->format('H:i');

            if (!in_array($slotTime, $bookedAppointments)) {
                $availableSlots[] = $slotTime;
            }

            $startTime->addMinutes($slotDuration);
        }

        return $availableSlots;
    }

    public function confirmAppointment($appointmentId)
    {
        try {
            $appointment = Appointment::findOrFail($appointmentId);
            $appointment->status = 'confirmed';
            $appointment->save();

            // إرسال إشعار التأكيد
            $patient = $appointment->patient;
            $patient->notify(new AppointmentConfirmed($appointment));

            return $appointment;
        } catch (\Exception $e) {
            Log::error('فشل في تأكيد الموعد: ' . $e->getMessage());
            throw $e;
        }
    }

    public function cancelAppointment($appointmentId, $reason = null)
    {
        try {
            $appointment = Appointment::findOrFail($appointmentId);
            $appointment->status = 'cancelled';
            $appointment->cancellation_reason = $reason;
            $appointment->save();

            // إرسال إشعار الإلغاء
            $patient = $appointment->patient;
            $patient->notify(new AppointmentCancelled($appointment, $reason));

            return $appointment;
        } catch (\Exception $e) {
            Log::error('فشل في إلغاء الموعد: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendReminders()
    {
        try {
            $appointments = Appointment::where('status', 'confirmed')
                ->whereDate('appointment_datetime', Carbon::today()->addDay())
                ->get();

            foreach ($appointments as $appointment) {
                $patient = $appointment->patient;
                $patient->notify(new AppointmentReminder($appointment));
            }

            return count($appointments);
        } catch (\Exception $e) {
            Log::error('فشل في إرسال التذكيرات: ' . $e->getMessage());
            throw $e;
        }
    }
}
