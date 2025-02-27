<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function index()
    {
        // Fetch courses with lessons (with video embed code) to display
        $courses = Course::with('lessons')->get();
        
        // Log the courses data
        Log::info('Courses fetched: ', $courses->toArray());

        return response()->json($courses);
    }

    public function fetchLessons($courseId)
    {
        // Fetch the course by ID
        $course = Course::find($courseId);

        // Check if the course exists
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        // Fetch all lessons associated with this course
        $lessons = $course->lessons;  // Assuming a relationship exists

        // Return lessons data
        return response()->json($lessons);
    }


    public function show($id)
    {
        // Fetch a single course with lessons based on course ID
        $course = Course::with('lessons')->findOrFail($id);
        
        // Log the course data
        Log::info('Course fetched: ', $course->toArray());

        return response()->json($course);
    }

    public function showLesson($courseId, $lessonId)
    {
        // Fetch a specific lesson for a specific course
        $lesson = Lesson::where('course_id', $courseId)->findOrFail($lessonId);

        // Log the lesson data
        Log::info('Lesson fetched for course ' . $courseId . ': ', $lesson->toArray());

        return response()->json($lesson);
    }
}
