<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Course;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ReviewController extends Controller
{
    

    
    // Function to store new review
    public function store(Request $request, $course_id, $assessment_id)
    {
        $request->validate([
            'review_content' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (str_word_count($value) < 5) {
                        $fail('The review content must be at least 5 words.');
                    }
                }
            ]
        ]);
        

        // Create new review
        Review::create([
            'assessment_id' => $assessment_id,
            'reviewer_id' => auth()->id(),
            'reviewee_id' => $request->reviewee_id,
            'review_content' => $request->review_content,
        ]);

        return redirect()->back();
    }

    //Function to mark useful reviews
    public function markAsUseful($review_id)
    {
        $review = Review::findOrFail($review_id);
        
        if (auth()->id() == $review->reviewee_id) {
            Review::where('reviewee_id', auth()->id())
                ->where('assessment_id', $review->assessment_id)
                ->update(['is_useful' => false]);

            $review->is_useful = true;
            $review->save();
        }
        
        return redirect()->back();
    }

    // Function to display the useful review
    public function showUsefulReviews($course_id, $assessment_id)
    {
        $course = Course::findOrFail($course_id);
        $assessment = Assessment::findOrFail($assessment_id);

        // Use Eloquent to retrieve useful reviews and create anonymous names for reviewers
        $usefulReviews = Review::where('assessment_id', $assessment_id)
        ->where('is_useful', true)
        ->get();

    // Return the view with useful reviews
    return view('course.useful_review', compact('usefulReviews', 'course', 'assessment'));
    }

}
