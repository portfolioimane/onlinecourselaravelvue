<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('slug')->unique();
            $table->string('image')->nullable(); // Add image column (nullable in case not every course has an image)
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('duration')->nullable(); // Add duration column (in minutes, for example)
            $table->boolean('featured')->default(false);  // Add 'featured' column

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
