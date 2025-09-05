<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        $userTypes = ['patient', 'doctor', 'facility', 'admin'];
        $selectedType = $this->faker->randomElement($userTypes);

        return [
            'user_id' => function () use ($selectedType) {
                switch ($selectedType) {
                    case 'patient':
                        return \App\Models\Patient::factory()->create()->id;
                    case 'doctor':
                        return \App\Models\Doctor::factory()->create()->id;
                    case 'facility':
                        return \App\Models\Facility::factory()->create()->id;
                    case 'admin':
                        return \App\Models\Admin::factory()->create()->id;
                    default:
                        return 1;
                }
            },
            'user_type' => $selectedType,
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'is_read' => $this->faker->boolean(30), // 30% probability of being read
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
