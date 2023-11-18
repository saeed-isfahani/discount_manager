<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'full_name' => $firstName . ' ' . $lastName,
            'email' => fake()->email(),
            'email_verified_at' => now(),
            'password' => fake()->password(),
            'remember_token' => Str::random(10),
            'mobile' => fake()->numerify('98910#######'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
