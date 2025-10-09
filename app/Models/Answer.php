<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'answer_text',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Get the question that owns the answer.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the attempt answers that reference this answer.
     */
    public function attemptAnswers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class, 'selected_answer_id');
    }

    /**
     * Get the questions that have this as the correct answer.
     */
    public function questionsAsCorrect(): HasMany
    {
        return $this->hasMany(Question::class, 'correct_answer_id');
    }

    /**
     * Scope for correct answers.
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    /**
     * Scope for incorrect answers.
     */
    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    /**
     * Get the number of times this answer was selected in attempts.
     */
    public function getSelectionCountAttribute(): int
    {
        return $this->attemptAnswers()->count();
    }

    /**
     * Get the percentage of times this answer was selected.
     */
    public function getSelectionPercentageAttribute(): float
    {
        $totalAttempts = AttemptAnswer::where('question_id', $this->question_id)->count();
        
        if ($totalAttempts === 0) {
            return 0;
        }

        return round(($this->selection_count / $totalAttempts) * 100, 2);
    }
}
