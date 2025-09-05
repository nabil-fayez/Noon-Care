<?php

namespace Database\Factories;

use App\Models\ErrorLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class ErrorLogFactory extends Factory
{
    protected $model = ErrorLog::class;

    public function definition()
    {
        $levels = ['error', 'warning', 'info', 'debug'];

        return [
            'level' => $this->faker->randomElement($levels),
            'message' => $this->faker->sentence,
            'details' => json_encode([
                'exception' => $this->faker->sentence,
                'trace' => $this->faker->paragraphs(3, true)
            ]),
            'file' => $this->faker->filePath(),
            'line' => $this->faker->numberBetween(1, 500),
            'url' => $this->faker->url,
            'ip' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'user_id' => $this->faker->randomDigitNotNull(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
