<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Assessment;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Review;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(50)->create();
        $this->call(UsersTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(EnrollmentsTableSeeder::class);
        $this->call(AssessmentsTableSeeder::class);
        $this->call(WorkshopsTableSeeder::class);
        // Seed 20 courses
        Course::factory()->count(20)->create();

        // Seed 50 assessments
        Assessment::factory()->count(50)->create();

        // Seed 100 users (both students and teachers)
        User::factory()->count(100)->create();

        // Seed 100 enrollments
        Enrollment::factory()->count(100)->create();

        // Seed 200 reviews
        Review::factory()->count(200)->create();
    }
    
}
