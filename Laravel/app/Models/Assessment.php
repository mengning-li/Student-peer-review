<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'instruction',
        'required_reviews',
        'max_score',
        'due_date',
        'type',
        'course_id',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Removed workshop functionality - teacher-assign mode not implemented

    public function assessmentScores()
    {
        return $this->hasMany(AssessmentScore::class);
    }
}
