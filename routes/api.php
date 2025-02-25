<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\CourseController;

Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/{course}', [CourseController::class, 'show']); // Fetch course by ID

// Lessons Routes
Route::get('courses/{course}/lessons/{lesson}', [CourseController::class, 'showLesson']); // Fetch lesson by ID for specific course