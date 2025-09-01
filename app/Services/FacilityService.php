<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Facility;
use App\Models\Service;
use App\Models\InsuranceCompany;
use App\Models\WorkingHour;
use Illuminate\Support\Facades\DB;

class FacilityService
{
    public function createFacility(array $data): Facility
    {
        return DB::transaction(function () use ($data) {
            $facility = Facility::create($data);

            // إضافة الخدمات الافتراضية للمنشأة
            $this->addDefaultServices($facility);

            return $facility;
        });
    }

    public function updateServicePricing(Facility $facility, array $pricingData): void
    {
        DB::transaction(function () use ($facility, $pricingData) {
            foreach ($pricingData as $servicePricing) {
                $facility->servicePricings()->updateOrCreate(
                    [
                        'service_id' => $servicePricing['service_id'],
                        'insurance_company_id' => $servicePricing['insurance_company_id'] ?? null
                    ],
                    [
                        'price' => $servicePricing['price'],
                        'is_active' => $servicePricing['is_active'] ?? true
                    ]
                );
            }
        });
    }

    private function addDefaultServices(Facility $facility): void
    {
        $defaultServices = Service::where('is_default', true)->get();

        foreach ($defaultServices as $service) {
            $facility->services()->attach($service->id, ['is_available' => true]);
        }
    }

    public function getAvailableTimeSlots(Facility $facility, $doctorId, $date): array
    {
        // الحصول على أوقات العمل للطبيب في المنشأة
        $workingHours = WorkingHour::where('facility_id', $facility->id)
            ->where('doctor_id', $doctorId)
            ->where('day_of_week', strtoupper(substr($date->format('D'), 0, 3)))
            ->where('is_available', true)
            ->first();

        if (!$workingHours) {
            return [];
        }

        // الحصول على المواعيد المحجوزة
        $bookedAppointments = Appointment::where('facility_id', $facility->id)
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_datetime', $date)
            ->whereIn('status', ['new', 'confirmed'])
            ->pluck('appointment_datetime')
            ->map(function ($datetime) {
                return $datetime->format('H:i');
            })
            ->toArray();

        // توليد الأوقات المتاحة
        $availableSlots = [];
        $startTime = \Carbon\Carbon::parse($workingHours->start_time);
        $endTime = \Carbon\Carbon::parse($workingHours->end_time);
        $slotDuration = $workingHours->slot_duration;

        while ($startTime->addMinutes($slotDuration) <= $endTime) {
            $timeSlot = $startTime->format('H:i');

            if (!in_array($timeSlot, $bookedAppointments)) {
                $availableSlots[] = $timeSlot;
            }
        }

        return $availableSlots;
    }
}