<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    public function definition(): array
    {
        return [
            // 'name' => fake()->name(),
            // 'email' => fake()->unique()->safeEmail(),
            'name' => "admin",
            'email' => "admin@gamil.com",
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'remember_token' => Str::random(10),
        ];
    }
}
