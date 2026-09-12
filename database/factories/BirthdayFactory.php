<?php

namespace Database\Factories;

use App\Models\Birthday;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Birthday>
 */
class BirthdayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'birth_date' => fake()->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
            'photo' => fake()->imageUrl(),
            'notes' => fake()->sentence(),
        ];
    }
}
