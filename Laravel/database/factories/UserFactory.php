<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'sNumber' => $this->faker->unique()->regexify('s[0-9]{6}'),
            'role' => $this->faker->randomElement(['student', 'teacher']),
            'password' => bcrypt('defaultPassword'), // Default password for seeding
            'created_at' => now(),
            'updated_at' => now(),
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
