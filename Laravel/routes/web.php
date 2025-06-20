<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkshopController;
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

// Route to show the form for creating a new assessment
Route::get('/course/{course_id}/add_assessment', [AssessmentController::class, 'create'])->name('course.add_assessment');


// Show form to enroll a student
Route::get('/course/{course_id}/add_student', [CourseController::class, 'showAddStudentForm'])->name('course.add_student');

// Handle the enrollment of a student
Route::post('/course/{course_id}/enroll', [CourseController::class, 'enrollStudent'])->name('course.enroll');
Route::post('/course/create', [CourseController::class, 'uploadCourseFile'])->name('course.upload');

// Download JSON template
Route::get('/course/template/download', [CourseController::class, 'downloadTemplate'])->name('course.template.download');

// Route to store the new assessment
Route::post('/course/{id}/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
// Route to display the edit form for an assessment
Route::get('/course/{course}/assessment/{assessment}/edit', [AssessmentController::class, 'edit'])->name('assessment.edit');
// Route to handle the update request
Route::put('/course/{course_id}/assessments/{assessment_id}', [AssessmentController::class, 'update'])->name('assessment.update');


// Route to submit a peer review on the same assessment detail page
Route::post('/course/{course_id}/assessment/{assessment_id}/review', [ReviewController::class, 'store'])->name('review.store');


Route::get('/course/{course_id}/assessment/{assessment_id}', [ReviewController::class, 'show']);
// Route to display the assessment details (using AssessmentController)

Route::get('/course/{course_id}/assessment/{assessment_id}', [AssessmentController::class, 'show'])->name('assessment.detail');

    // Route for viewing student details
Route::get('/course/{course_id}/assessment/{assessment_id}/student/{student_id}', [AssessmentController::class, 'showStudent'])->name('student.detail');

    // Route for submitting score
Route::post('/course/{course_id}/assessment/{assessment_id}/student/{student_id}', [AssessmentController::class, 'assignScore'])->name('student.score');



Route::get('assessment/{assessment_id}/workshops', [WorkshopController::class, 'index'])->name('workshops.list');

// Route::get('/assessment/{assessment_id}/workshops', [WorkshopController::class, 'showWorkshops'])->name('workshops.list');
Route::post('/workshop/{workshop_id}/join/{assessment_id}', [WorkshopController::class, 'joinWorkshop'])->name('workshop.join');


// Logout route
Route::post('/logout', [Auth\LoginController::class, 'logout'])->name('logout');

// Workshop Routes
Route::middleware('auth')->group(function () {
    Route::get('/course/{course_id}/assessment/{assessment_id}/workshop/create', [AssessmentController::class, 'createWorkshop'])->name('workshop.create');
    Route::post('/course/{course_id}/assessment/{assessment_id}/workshop/{workshop_id}/assign-groups', [AssessmentController::class, 'assignGroups'])->name('workshop.assign_groups');
    Route::get('/course/{course_id}/assessment/{assessment_id}/workshop/{workshop_id}/join', [AssessmentController::class, 'joinWorkshop'])->name('workshop.join');
});

// Authentication routes
require __DIR__.'/auth.php';

Route::post('/review/{review}/rate-feedback', [App\Http\Controllers\ReviewController::class, 'rateFeedback'])->name('review.rate_feedback');



