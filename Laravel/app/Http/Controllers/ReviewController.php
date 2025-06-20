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
            ],
            'score' => 'nullable|integer|min:0|max:100',
        ]);
        

        // Create new review
        Review::create([
            'assessment_id' => $assessment_id,
            'reviewer_id' => auth()->id(),
            'reviewee_id' => $request->reviewee_id,
            'review_content' => $request->review_content,
            'score' => $request->score,
        ]);

        return redirect()->back();
    }

    // New: Store rating and feedback for a received review
    public function rateFeedback(Request $request, $review_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string',
        ]);

        $review = Review::findOrFail($review_id);
        // Only the reviewee can rate/feedback
        if (auth()->id() !== $review->reviewee_id) {
            abort(403);
        }
        $review->rating = $request->rating;
        $review->feedback = $request->feedback;
        $review->save();

        return redirect()->back();
    }

}
