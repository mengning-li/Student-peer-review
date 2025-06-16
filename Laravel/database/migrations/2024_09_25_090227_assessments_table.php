<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id(); 
            $table->string('title'); 
            $table->text('instruction'); 
            $table->text('review')->nullable(); 
            $table->dateTime('due_date'); 
            $table->integer('max_score')->unsigned(); 
            $table->enum('type', ['student-select', 'teacher-assign']);
            $table->integer('required_reviews')->unsigned(); 
            $table->unsignedInteger('course_id'); 
            $table->timestamps(); 

            // Foreign key constraint
            $table->foreign('course_id')->references('id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
