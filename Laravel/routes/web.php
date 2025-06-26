<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController;
use Illuminate\Support\Facades\Auth;

// Home route that requires authentication
Route::get('/home', function () {return view('course.home');})->middleware(['auth'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('home'); 
    Route::get('/course_detail/{id}', [CourseController::class, 'show'])->name('course.detail');
    Route::post('/course/{id}/enroll', [CourseController::class, 'enrollStudent'])->name('course.enroll');
    Route::get('/add_student', [CourseController::class, 'index']);


    
});

// Protected routes for authenticated users
Route::middleware(['auth'])->group(function () {
    // Assessment routes
    Route::get('/course/{course_id}/add_assessment', [AssessmentController::class, 'create'])->name('course.add_assessment');
    Route::post('/course/{id}/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
    Route::get('/course/{course}/assessment/{assessment}/edit', [AssessmentController::class, 'edit'])->name('assessment.edit');
    Route::put('/course/{course_id}/assessments/{assessment_id}', [AssessmentController::class, 'update'])->name('assessment.update');
    Route::get('/course/{course_id}/assessment/{assessment_id}', [AssessmentController::class, 'show'])->name('assessment.detail');
    
    // Student management routes
    Route::get('/course/{course_id}/add_student', [CourseController::class, 'showAddStudentForm'])->name('course.add_student');
    Route::post('/course/{course_id}/enroll', [CourseController::class, 'enrollStudent'])->name('course.enroll');
    
    // Course management routes
    Route::post('/course/create', [CourseController::class, 'uploadCourseFile'])->name('course.upload');
    Route::get('/course/template/download', [CourseController::class, 'downloadTemplate'])->name('course.template.download');
    
    // Review routes
    Route::post('/course/{course_id}/assessment/{assessment_id}/review', [ReviewController::class, 'store'])->name('review.store');
    Route::post('/review/{review}/rate-feedback', [ReviewController::class, 'rateFeedback'])->name('review.rate_feedback');
    
    // Student detail and scoring routes
    Route::get('/course/{course_id}/assessment/{assessment_id}/student/{student_id}', [AssessmentController::class, 'showStudent'])->name('student.detail');
    Route::post('/course/{course_id}/assessment/{assessment_id}/student/{student_id}', [AssessmentController::class, 'assignScore'])->name('student.score');
});



// Authentication routes
require __DIR__.'/auth.php';



