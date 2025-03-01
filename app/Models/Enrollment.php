<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    // Define fillable fields to protect mass assignment
    protected $fillable = [
        'user_id',
        'course_id',
        'name',
        'email',
        'phone',
        'address',
        'payment_method',
        'total',
        'status',
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
