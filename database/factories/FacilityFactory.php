<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class FacilityFactory extends Factory
{
    protected $model = Facility::class;

    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName,
            'business_name' => $this->faker->company,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'website' => $this->faker->url,
            'description' => $this->faker->paragraph,
            'logo' => $this->faker->imageUrl(200, 200, 'business'),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'is_active' => $this->faker->boolean(90),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
