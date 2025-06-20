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
        
        // Check if user is teacher or student and route accordingly
        if (auth()->user()->role === 'teacher') {
            return $this->showTeacherView($course, $assessment);
        } else {
            return $this->showStudentView($course, $assessment);
        }
    }
    
    // Method to display assessment for teachers
    private function showTeacherView($course, $assessment)
    {
        // Get enrolled students with their review counts and scores using Eloquent
        $enrolledStudents = User::whereHas('enrollments', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->where('role', 'student')
            ->with(['assessmentScores' => function($query) use ($assessment) {
                $query->where('assessment_id', $assessment->id);
            }])
            ->withCount([
                'submittedReviews as submitted_num' => function($query) use ($assessment) {
                    $query->where('assessment_id', $assessment->id);
                },
                'receivedReviews as received_num' => function($query) use ($assessment) {
                    $query->where('assessment_id', $assessment->id);
                }
            ])
            ->get()
            ->map(function($student) {
                // Add the score from assessment_scores relationship
                $student->score = $student->assessmentScores->first()->score ?? null;
                return $student;
            });

        return view('course.assessment_detail_teacher', compact('course', 'assessment', 'enrolledStudents'));
    }
    
    // Method to display assessment for students
    private function showStudentView($course, $assessment)
    {
        $workshops = Workshop::where('assessment_id', $assessment->id)->first();
        $currentStudentId = auth()->id();

        // Get reviews received by current student using Eloquent relationships
        $reviewReceived = Review::with(['reviewer:id,name', 'reviewee:id,name'])
            ->where('reviewee_id', $currentStudentId)
            ->where('assessment_id', $assessment->id)
            ->get();

        // Get students to review using Eloquent relationships
        $studentsToReview = User::whereHas('enrollments', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->whereDoesntHave('receivedReviews', function($query) use ($currentStudentId, $assessment) {
                $query->where('reviewer_id', $currentStudentId)
                      ->where('assessment_id', $assessment->id);
            })
            ->where('role', 'student')
            ->where('id', '!=', $currentStudentId)
            ->get();

        // Get enrolled students with their review counts and scores using Eloquent (for reference)
        $enrolledStudents = User::whereHas('enrollments', function($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->where('role', 'student')
            ->with(['assessmentScores' => function($query) use ($assessment) {
                $query->where('assessment_id', $assessment->id);
            }])
            ->withCount([
                'submittedReviews as submitted_num' => function($query) use ($assessment) {
                    $query->where('assessment_id', $assessment->id);
                },
                'receivedReviews as received_num' => function($query) use ($assessment) {
                    $query->where('assessment_id', $assessment->id);
                }
            ])
            ->paginate(10);

        $reviewSubmitted = \App\Models\Review::with('reviewee')
            ->where('reviewer_id', auth()->id())
            ->where('assessment_id', $assessment->id)
            ->get();

        return view('course.assessment_detail', compact('course', 'assessment', 'studentsToReview', 'reviewReceived', 'enrolledStudents', 'workshops', 'reviewSubmitted'));
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
        
        // Fetch student details using Eloquent
        $student = User::findOrFail($student_id);

        // Fetch assessment score using Eloquent
        $assessment_score = AssessmentScore::where('assessment_id', $assessment_id)
            ->where('user_id', $student_id)
            ->first();

        // Fetch the assessment details using Eloquent
        $assessment = Assessment::findOrFail($assessment_id);

        // Fetch the reviews submitted by the student using Eloquent relationships
        $reviewSubmitted = Review::with('reviewee:id,name')
            ->where('reviewer_id', $student_id)
            ->where('assessment_id', $assessment_id)
            ->get();

        // Fetch the reviews received by the student using Eloquent relationships
        $reviewReceived = Review::with('reviewer:id,name')
            ->where('reviewee_id', $student_id)
            ->where('assessment_id', $assessment_id)
            ->get();

        // Return the view with the fetched data
        return view('course.student_detail', compact('student', 'course', 'assessment', 'reviewSubmitted', 'reviewReceived', 'assessment_score'));
    }

    // Method to assign score to a student
    public function assignScore(Request $request, $course_id, $assessment_id, $student_id)
    {   
        // Fetch the assessment using Eloquent
        $assessment = Assessment::findOrFail($assessment_id);

        // Validate the score input to ensure it's not greater than the max score from the assessment
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:' . $assessment->max_score
        ]);

        // Insert or update the score for the student in this assessment using Eloquent
        AssessmentScore::updateOrCreate(
            ['assessment_id' => $assessment_id, 'user_id' => $student_id],
            ['score' => $validated['score']]
        );

        // Redirect back to the student's detail page with a success message
        return redirect()->route('student.detail', [$course_id, $assessment_id, $student_id]);
    }
}
