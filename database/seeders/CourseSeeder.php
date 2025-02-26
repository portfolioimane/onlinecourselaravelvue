<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Seed the application's database with courses.
     *
     * @return void
     */
    public function run()
    {

        // Create specific courses manually with images
        Course::create([
            'title' => 'Laravel for Beginners',
            'description' => 'A beginner course on Laravel, the popular PHP framework.',
            'slug' => 'laravel-for-beginners',
            'image' => 'images/courses/laravel-for-beginners.jpg', // Image path
        ]);

        Course::create([
            'title' => 'Advanced Vue.js',
            'description' => 'An advanced course on Vue.js for experienced developers.',
            'slug' => 'advanced-vuejs',
            'image' => 'images/courses/advanced-vuejs.jpg', // Image path
        ]);
    }
}
