<?php

namespace Database\Factories;

use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalRecordFactory extends Factory
{
    protected $model = MedicalRecord::class;

    public function definition()
    {
        return [
            'patient_id' => function () {
                return \App\Models\Patient::factory()->create()->id;
            },
            'doctor_id' => function () {
                return \App\Models\Doctor::factory()->create()->id;
            },
            'facility_id' => function () {
                return \App\Models\Facility::factory()->create()->id;
            },
            'record_type' => $this->faker->randomElement(['consultation', 'examination', 'test', 'prescription']),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'diagnosis' => $this->faker->paragraph,
            'treatment_plan' => $this->faker->paragraph,
            'record_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['active', 'completed', 'cancelled']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
