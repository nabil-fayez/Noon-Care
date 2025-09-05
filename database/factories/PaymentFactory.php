<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'appointment_id' => function () {
                return \App\Models\Appointment::factory()->create()->id;
            },
            'amount' => $this->faker->randomFloat(2, 50, 500),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'payment_method' => $this->faker->randomElement(['credit_card', 'cash', 'bank_transfer']),
            'transaction_id' => $this->faker->unique()->uuid,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
