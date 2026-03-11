<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+0 days', '+1 month');
        $end = (clone $start)->modify('+'.rand(30, 120).' minutes');

        return [
            'customer_id' => Customer::inRandomOrder()->value('id') ?? Customer::factory(),
            'start_at' => $start,
            'end_at' => $end,
            'subject' => $this->faker->sentence(3),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}
