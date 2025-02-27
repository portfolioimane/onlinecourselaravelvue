<?php
namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CoursesController extends Controller
{
    public function index()
    {
        // Fetch all courses along with their category
        $courses = Course::all();
        Log::info('Fetched all courses', ['courses' => $courses]);
        return response()->json($courses);
    }

    public function store(Request $request)
    {
        // Validate and create a new course
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|alpha_dash|max:255|unique:courses,slug',  // Add validation for slug
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',  // Add validation for duration
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = str_slug($data['title']);
        }

        // Store image if uploaded
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/courses', 'public');
            $data['image'] = '/storage/' . $path;
            Log::info('Course image uploaded', ['path' => $path]);
        }

        // Create the course
        $course = Course::create($data);
        Log::info('New course created', ['course' => $course]);
        return response()->json($course, 201);
    }

    public function update(Request $request, $id)
    {
        // Validate and update a course
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|alpha_dash|max:255|unique:courses,slug,' . $id,  // Update slug validation
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'duration' => 'sometimes|required|numeric',  // Add validation for duration
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed on update', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $course = Course::findOrFail($id);
        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = str_slug($data['title']);
        }

        // Store new image if uploaded, and delete the old one
        if ($request->hasFile('image')) {
            if ($course->image) {
                Storage::delete('public/' . $course->image);
            }
            $path = $request->file('image')->store('images/courses', 'public');
            $data['image'] = '/storage/' . $path;
        }

        // Update the course
        $course->update($data);
        Log::info('Course updated', ['course' => $course]);
        return response()->json($course);
    }

    public function show($id)
    {
        // Fetch a single course by ID along with its category
        $course = Course::findOrFail($id);
        Log::info('Fetched course by ID', ['course' => $course]);
        return response()->json($course);
    }

    public function destroy($id)
    {
        // Delete course and image if exists
        $course = Course::findOrFail($id);
        if ($course->image) {
            Storage::delete('public/' . $course->image);
        }
        $course->delete();
        Log::info('Course deleted', ['course_id' => $id]);
        return response()->json(null, 204);
    }

    public function toggleFeatured($courseId)
    {
        // Find the course by ID
        $course = Course::findOrFail($courseId);

        // Toggle the featured status
        $course->featured = !$course->featured;

        // Save the course with the updated featured status
        $course->save();

        // Return the updated course as a JSON response
        return response()->json($course);
    }
}
