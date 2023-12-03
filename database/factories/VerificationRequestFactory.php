<?php

namespace Database\Factories;

use App\Enums\VerificationRequest\VerificationRequestProviderEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VerificationRequest>
 */
class VerificationRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->name();
        $lastName = fake()->name();
        return [
            'provider' => fake()->randomElement(VerificationRequestProviderEnum::values()),
            'code' => fake()->numerify('######'),
            'receiver' => fake()->numerify('0912#######'),
            'expire_at' => now()->addMinutes(config('settings.verification_request_timeout_in_minute')),
            'veriffication_at' => null
        ];
    }
}
