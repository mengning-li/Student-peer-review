<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assessment>
 */
class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    public function definition()
{
    return [
        'title' => $this->faker->regexify('[A-Za-z0-9]{1,20}'), // Max 20 chars
        'instruction' => $this->faker->paragraph(),
        'max_score' => $this->faker->numberBetween(1, 100), // 1-100 range
        'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        'type' => $this->faker->randomElement(['student-select', 'teacher-assign']),
        'required_reviews' => $this->faker->numberBetween(1, 5),
        'course_id' => Course::factory(),
    ];
}
}
