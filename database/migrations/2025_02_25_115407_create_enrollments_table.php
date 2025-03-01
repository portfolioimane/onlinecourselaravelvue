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
    Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Add name column
            $table->string('email'); // Add email column
            $table->string('phone'); // Add phone column
            $table->text('address'); // Add address column
            $table->string('payment_method'); // Add payment method column
            $table->decimal('total', 10, 2); // Add total amount column
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending'); // Order status            $table->timestamps();
            $table->timestamps();

        });

}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
