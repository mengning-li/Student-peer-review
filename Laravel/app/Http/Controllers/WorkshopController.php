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



class WorkshopController extends Controller
{


    public function index($assessment_id)
    {
        // Get the assessment
        $assessment = Assessment::find($assessment_id);
        
        // Get all workshops for the given assessment
        $workshops = Workshop::where('assessment_id', $assessment_id)->get();

        return view('course.assessment_detail', compact('assessment', 'workshops'));
    }

    public function showAssessmentDetails($assessmentId)
{
    // Fetch the assessment
    $assessment = Assessment::find($assessmentId);

    // Fetch the workshops related to this assessment
    $workshops = Workshop::where('assessment_id', $assessmentId)->get();

    // Fetch the course (assuming assessment has a 'course' relationship)
    $course = $assessment->course;

    // Fetch the current logged-in user (assuming students are users)
    $user = Auth::user();

    // Fetch the student's related data (you'll need to adjust this based on your relationships)
    $student = User::find($user->id); // Assuming student is a user

    return view('course.assessment_detail', compact('assessment', 'workshops', 'course', 'student', 'user'));
}

    

    public function joinWorkshop(Request $request, $workshopId, $assessmentId, $userId)
{
    $workshop = Workshop::find($workshopId);

    // Check if the workshop is active before allowing the student to join
    if ($workshop && $workshop->active_status) {
        // Add the student to the workshop (assuming you have a pivot table 'workshop_student')
        $workshop->students()->attach($userId);

        return redirect()->back()->with('success', 'You have successfully joined the workshop.');
    }

    return redirect()->back()->with('error', 'This workshop is currently inactive.');
}



}
