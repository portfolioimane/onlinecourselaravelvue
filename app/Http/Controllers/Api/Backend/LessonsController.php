<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class LessonsController extends Controller
{
    // Fetch lessons for a specific course
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        return response()->json($course->lessons);
    }
    public function getLesson($lessonId)
    {

        // Fetch a single course by ID along with its category
        $lesson = Lesson::findOrFail($lessonId);
        Log::info('Fetched course by ID', ['lesson' => $lesson]);
        return response()->json($lesson);

    }


    // Add a new lesson to a course
    public function store(Request $request, $courseId)
    {
        // Validate the incoming request data, including slug
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:lessons,slug',
            'content' => 'required|string',
            'video_embed_code' => 'nullable|string',
        ]);

        $course = Course::findOrFail($courseId);

        // Create the lesson with the validated data, including slug
        $lesson = new Lesson($validated);
        $lesson->course_id = $course->id;
        $lesson->save();

        return response()->json($lesson, 201);
    }

    // Edit a lesson
    public function update(Request $request, $lessonId)
{
    // Validate the incoming request data, conditionally requiring fields
    $validator = Validator::make($request->all(), [
        'title' => 'sometimes|required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:lessons,slug,' . $lessonId,
        'content' => 'sometimes|required|string',
        'video_embed_code' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed on update', ['errors' => $validator->errors()]);
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $lesson = Lesson::findOrFail($lessonId);

    // Update the lesson with the validated data
    $lesson->update($request->all());

    return response()->json($lesson);
}


    // Delete a lesson
    public function destroy($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $lesson->delete();

        return response()->json(null, 204);
    }
}
