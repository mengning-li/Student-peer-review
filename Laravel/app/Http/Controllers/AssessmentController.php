<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Course;
use App\Models\Workshop;
use App\Models\Review;
use App\Models\User;
use App\Models\AssessmentScore;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AssessmentController extends Controller
{
    
    // Method to display a form to create a new assessment   
    public function create($course_id)
    {
        $course = Course::findOrFail($course_id);
        return view('course.add_assessment_form', compact('course'));
    }

    // Method to display assessment both for teacher and student   
    public function show($course_id, $assessment_id)
    {
        $course = Course::findOrFail($course_id);

        $assessment = Assessment::where('course_id', $course_id)->findOrFail($assessment_id);


        $workshops = Workshop::where('assessment_id', $assessment_id)
                        ->first();

        // Items per page
        $perPage = 10;

        $currentPage = request()->input('page', 1);

        $offset = ($currentPage - 1) * $perPage;

        $currentStudentId = auth()->id();

        $reviewReceived = Review::select('reviews.*', 'reviewer.name as reviewer_name', 'reviewee.name as reviewee_name')
            ->join('users as reviewer', 'reviews.reviewer_id', '=', 'reviewer.id')
            ->join('users as reviewee', 'reviews.reviewee_id', '=', 'reviewee.id')
            ->where('reviews.reviewee_id', '=', $currentStudentId)
            ->where('reviews.assessment_id', '=', $assessment_id) 
            ->get();


        $studentsToReview = User::select('users.*')
            ->leftJoin('reviews', function ($join) use ($currentStudentId, $assessment_id) {
                $join->on('users.id', '=', 'reviews.reviewee_id')
                    ->where('reviews.reviewer_id', '=', $currentStudentId)
                    ->where('reviews.assessment_id', '=', $assessment_id);
            })
            ->join('enrollments', 'enrollments.user_id', '=', 'users.id')
            ->where('enrollments.course_id', '=', $course_id)
            ->where('users.role', '=', 'student')
            ->whereNull('reviews.reviewee_id')
            ->where('users.id', '!=', $currentStudentId)
            ->get();


        
        // The students who enrolled the specific course
        $enrolledStudents = DB::table('users')
            ->join('enrollments', 'users.id', '=', 'enrollments.user_id') 
            ->leftJoin('assessment_scores', function($join) use ($assessment_id) {
                $join->on('users.id', '=', 'assessment_scores.user_id')
                    ->where('assessment_scores.assessment_id', '=', $assessment_id);
            })
            ->select('users.id', 'users.name',
                DB::raw('(SELECT COUNT(*) FROM reviews WHERE reviews.reviewer_id = users.id AND reviews.assessment_id = ' . $assessment_id . ') as submitted_num'),
                DB::raw('(SELECT COUNT(*) FROM reviews WHERE reviews.reviewee_id = users.id AND reviews.assessment_id = ' . $assessment_id . ') as received_num'),
                'assessment_scores.score'
            )
            ->where('enrollments.course_id', $course_id)
            ->where('users.role', 'student')
            ->paginate(10);

        return view('course.assessment_detail', compact('course', 'assessment', 'studentsToReview', 'reviewReceived', 'enrolledStudents', 'workshops'));
    }

    
    // Method to store a new assessment in the database
    public function store(Request $request, $course_id)
    {
        $validation = $request->validate([
            'title' => 'required|max:20',
            'instruction' => 'required',
            'required_reviews' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'due_date' => 'required|date_format:Y-m-d H:i',
            'type' => 'required|in:student-select,teacher-assign',
        ]);

        // Create the assessment
        Assessment::create([
            'title' => $validation['title'],
            'instruction' => $validation['instruction'],
            'required_reviews' => $validation['required_reviews'],
            'max_score' => $validation['max_score'],
            'due_date' => $validation['due_date'], 
            'type' => $validation['type'],
            'course_id' => $course_id,
        ]);

        return redirect()->route('course.detail', $course_id);
    }


    // Method to get edit assessment form
    public function edit($course_id, $assessment_id)
    {
        // Find the assessment by ID
        $assessment = Assessment::findOrFail($assessment_id);
        $course = Course::findOrFail($course_id);
    
        return view('course.edit_assessment_form', compact('assessment', 'course'));
    }

    // Method to update assessment
        public function update(Request $request, $course_id, $assessment_id)
    {
        // Fetch the assessment by its ID
        $assessment = Assessment::findOrFail($assessment_id);

        // Validate the incoming request
        $validation = $request->validate([
            'title' => 'required|max:20',
            'instruction' => 'required',
            'required_reviews' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'due_date' => 'required|date_format:Y-m-d H:i',
            'type' => 'required|in:student-select,teacher-assign',
        ]);

        // Update the assessment with the validated data
        $assessment->update($validation);

        // Redirect back with a success message
        return redirect()->route('course.detail', $course_id);
    }

    // Function to show details of a specific student (reviews submitted and received)
    public function showStudent($course_id, $assessment_id, $student_id)
    {
        $course = Course::findOrFail($course_id);
        // Fetch student details
        $student = DB::table('users')->where('id', $student_id)->first();

        $assessment_score = AssessmentScore::where('assessment_id', $assessment_id)
            ->where('user_id', $student_id)
            ->first();


        $assessment_score = !empty($assessment_score) ? $assessment_score[0] : null;
        // Fetch the assessment details
        $assessment = DB::table('assessments')->where('id', $assessment_id)->first();

        // Fetch the reviews submitted by the student
        $reviewSubmitted = User::select('users.*', 'reviews.review_content')
            ->leftJoin('reviews', function($join) use ($assessment_id) {
                $join->on('users.id', '=', 'reviews.reviewer_id')
                    ->where('reviews.assessment_id', '=', $assessment_id);
            })
            ->where('reviews.reviewer_id', '=', $student_id)
            ->get();

        

            $reviewReceived = User::select('users.*', 'users.name as reviewer_name', 'reviews.review_content')
            ->leftJoin('reviews', function($join) use ($assessment_id) {
                $join->on('users.id', '=', 'reviews.reviewer_id')
                     ->where('reviews.assessment_id', '=', $assessment_id);
            })
            ->where('reviews.reviewee_id', '=', $student_id)
            ->get();
        


        // Return the view with the fetched data
        return view('course.student_detail', compact('student', 'course', 'assessment', 'reviewSubmitted', 'reviewReceived', 'assessment_score'));
    }

    // Method to assign score to a student
    public function assignScore(Request $request, $course_id, $assessment_id, $student_id)
    {   
        // Fetch the max score from the assessment table
        $assessment = DB::table('assessments')->where('id', $assessment_id)->first();

        // Validate the score input to ensure it's not greater than the max score from the assessment
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:' . $assessment->max_score
        ]);

        // Insert or update the score for the student in this assessment
        DB::table('assessment_scores')->updateOrInsert(
            ['assessment_id' => $assessment_id, 'user_id' => $student_id],
            ['score' => $validated['score']]
        );

        // Redirect back to the student's detail page with a success message
        return redirect()->route('student.detail', [$course_id, $assessment_id, $student_id]);
    }





}
