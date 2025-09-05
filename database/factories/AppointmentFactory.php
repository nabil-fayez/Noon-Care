<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\InsuranceCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        return [
            'doctor_id' => function () {
                return \App\Models\Doctor::factory()->create()->id;
            },
            'facility_id' => function () {
                return \App\Models\Facility::factory()->create()->id;
            },
            'patient_id' => function () {
                return \App\Models\Patient::factory()->create()->id;
            },
            'service_id' => function () {
                return \App\Models\Service::factory()->create()->id;
            },
            'doctor_facility_id' => function () {
                return \App\Models\DoctorFacility::factory()->create()->id;
            },
            'insurance_company_id' => function () {
                $insuranceCompany = InsuranceCompany::inRandomOrder()->first();
                return $insuranceCompany ? $insuranceCompany->id : null;
            },
            'appointment_datetime' => $this->faker->dateTimeBetween('now', '+1 month'),
            'duration' => $this->faker->numberBetween(15, 60),
            'status' => $this->faker->randomElement(['new', 'confirmed', 'cancelled', 'done']),
            'notes' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 50, 500),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
