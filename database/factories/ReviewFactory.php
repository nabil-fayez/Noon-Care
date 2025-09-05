<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'appointment_id' => function () {
                return \App\Models\Appointment::factory()->create()->id;
            },
            'patient_id' => function () {
                return \App\Models\Patient::factory()->create()->id;
            },
            'doctor_id' => function () {
                return \App\Models\Doctor::factory()->create()->id;
            },
            'facility_id' => function () {
                return \App\Models\Facility::factory()->create()->id;
            },
            'rating_for_doctor' => $this->faker->numberBetween(1, 5),
            'rating_for_facility' => $this->faker->numberBetween(1, 5),
            'comment_for_doctor' => $this->faker->paragraph,
            'comment_for_facility' => $this->faker->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
