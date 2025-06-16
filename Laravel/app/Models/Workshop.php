<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Workshop extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'assessment_id',
        'active_status',
    ];

    // Relationship to groups
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    // Relationship to assessment
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }


}