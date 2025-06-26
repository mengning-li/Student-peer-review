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
        // First: Create demo accounts with proper relationships
        $this->call(UsersTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(EnrollmentsTableSeeder::class);
        $this->call(AssessmentsTableSeeder::class);
        
        // Then: Create realistic factory data with proper relationships
        $this->seedRealisticFactoryData();
    }
    
    private function seedRealisticFactoryData()
    {
        // Create additional courses
        $courses = Course::factory()->count(15)->create();
        
        // Create additional users
        $students = User::factory()->count(80)->state(['role' => 'student'])->create();
        $teachers = User::factory()->count(20)->state(['role' => 'teacher'])->create();
        
        // Create realistic enrollments (students enrolled in multiple courses)
        foreach ($students as $student) {
            // Each student enrolls in 2-4 courses
            $coursesToEnroll = $courses->random(rand(2, 4));
            foreach ($coursesToEnroll as $course) {
                Enrollment::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        // Create assessments for existing courses (both demo and factory courses)
        $allCourses = Course::all();
        foreach ($allCourses as $course) {
            // Each course gets 2-3 assessments
            Assessment::factory()->count(rand(2, 3))->create([
                'course_id' => $course->id
            ]);
        }
        
        // Create meaningful reviews between enrolled students
        $allAssessments = Assessment::all();
        foreach ($allAssessments as $assessment) {
            // Get students enrolled in this assessment's course
            $enrolledStudents = User::whereHas('enrollments', function($query) use ($assessment) {
                $query->where('course_id', $assessment->course_id);
            })->where('role', 'student')->get();
            
            if ($enrolledStudents->count() >= 2) {
                // Create 3-8 reviews per assessment between enrolled students
                $reviewCount = min(8, $enrolledStudents->count() * 2);
                for ($i = 0; $i < $reviewCount; $i++) {
                    $reviewer = $enrolledStudents->random();
                    $reviewee = $enrolledStudents->where('id', '!=', $reviewer->id)->random();
                    
                    // Avoid duplicate reviews
                    $existingReview = Review::where([
                        'assessment_id' => $assessment->id,
                        'reviewer_id' => $reviewer->id,
                        'reviewee_id' => $reviewee->id,
                    ])->first();
                    
                    if (!$existingReview) {
                        Review::factory()->create([
                            'assessment_id' => $assessment->id,
                            'reviewer_id' => $reviewer->id,
                            'reviewee_id' => $reviewee->id,
                        ]);
                    }
                }
            }
        }
    }
}
