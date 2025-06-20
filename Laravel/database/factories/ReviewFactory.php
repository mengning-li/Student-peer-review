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
            'assessment_id' => Assessment::factory(),
            'reviewer_id' => User::factory(),
            'reviewee_id' => User::factory(),
            'review_content' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
