<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->regexify('ICT[0-9]{5}'), // Generate unique course codes
            'name' => $this->faker->sentence(3),
        ];
    }
}
