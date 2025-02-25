<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    // Define fillable fields to protect mass assignment
    protected $fillable = [
        'title',
        'content',
        'video_embed_code',
        'course_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

