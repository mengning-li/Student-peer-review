<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Course extends Model
{
    use HasFactory;
    
    protected $fillable = ['code', 'name'];  
    // Define the relationship between Course and User (many-to-many)
    public function users()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')
                    ->where('role', 'student');
    }
    
    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'course_id');
    }
}
