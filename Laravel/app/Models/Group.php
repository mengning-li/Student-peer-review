<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'group_number',
        'members_count',
        'workshop_id',
        'user_id',
    ];

    /**
     * Relationship: A Group belongs to a Workshop.
     */
    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    /**
     * Relationship: A Group belongs to a User (group leader or related user).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
