<?php

namespace Database\Factories;

use App\Models\DoctorFacility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoctorFacility>
 */
class DoctorFacilityFactory extends Factory
{
    protected $model = DoctorFacility::class;

    public function definition()
    {
        return [
            'doctor_id' => function () {
                return \App\Models\Doctor::factory()->create()->id;
            },
            'facility_id' => function () {
                return \App\Models\Facility::factory()->create()->id;
            },
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']),
            'available_for_appointments' => $this->faker->boolean(80),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
