<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Assessment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'reviewer_id' => User::factory(), // Generate a user as reviewer
            'reviewee_id' => User::factory(), // Generate a user as reviewee
            'assessment_id' => Assessment::factory(), // Generate an assessment
            'review_content' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
