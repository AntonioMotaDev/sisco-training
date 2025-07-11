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
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('attempts');
            $table->foreignId('question_id')->constrained('questions');
            $table->foreignId('selected_answer_id')->nullable()->constrained('answers');
            $table->text('free_text_answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('score_awarded', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};
