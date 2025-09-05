<?php

namespace Database\Factories;

use App\Models\InsuranceCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

class InsuranceCompanyFactory extends Factory
{
    protected $model = InsuranceCompany::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'contact_email' => $this->faker->email,
            'contact_phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
