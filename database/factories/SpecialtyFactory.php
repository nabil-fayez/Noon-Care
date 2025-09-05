<?php

namespace Database\Factories;

use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecialtyFactory extends Factory
{
    protected $model = Specialty::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->paragraph,
            'icon' => $this->faker->imageUrl(50, 50),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
