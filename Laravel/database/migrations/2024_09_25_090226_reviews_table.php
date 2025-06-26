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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('reviewer_id');  // The student who is reviewing
            $table->unsignedBigInteger('reviewee_id');  // The student being reviewed
            $table->text('review_content');
            $table->boolean('is_useful')->default(false);
            $table->integer('rating')->nullable(); // 1-5 star rating for the review
            $table->text('feedback')->nullable(); // feedback for the review
            $table->integer('score')->nullable(); // score given by reviewer to reviewee
            $table->timestamps();
        
            // Foreign keys
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewee_id')->references('id')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
