<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Attempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'score',
        'passed',
        'attempt_date',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'passed' => 'boolean',
        'attempt_date' => 'datetime',
    ];

    /**
     * Get the user that owns the attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the test that owns the attempt.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get the attempt answers for the attempt.
     */
    public function attemptAnswers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    /**
     * Scope for passed attempts.
     */
    public function scopePassed($query)
    {
        return $query->where('passed', true);
    }

    /**
     * Scope for failed attempts.
     */
    public function scopeFailed($query)
    {
        return $query->where('passed', false);
    }

    /**
     * Scope for attempts by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for attempts by test.
     */
    public function scopeByTest($query, $testId)
    {
        return $query->where('test_id', $testId);
    }

    /**
     * Get the percentage score.
     */
    public function getPercentageAttribute(): float
    {
        $totalScore = $this->test->total_score ?? 1;
        return round(($this->score / $totalScore) * 100, 2);
    }

    /**
     * Get formatted score display.
     */
    public function getScoreDisplayAttribute(): string
    {
        return "{$this->score}/{$this->test->total_score} ({$this->percentage}%)";
    }

    /**
     * Get the grade letter based on percentage.
     */
    public function getGradeLetterAttribute(): string
    {
        $percentage = $this->percentage;
        
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }

    /**
     * Get the status of the attempt.
     */
    public function getStatusAttribute(): string
    {
        return $this->passed ? 'Aprobado' : 'No Aprobado';
    }

    /**
     * Get the number of correct answers.
     */
    public function getCorrectAnswersCountAttribute(): int
    {
        return $this->attemptAnswers()->where('is_correct', true)->count();
    }

    /**
     * Get the number of incorrect answers.
     */
    public function getIncorrectAnswersCountAttribute(): int
    {
        return $this->attemptAnswers()->where('is_correct', false)->count();
    }

    /**
     * Get the total number of questions answered.
     */
    public function getAnsweredQuestionsCountAttribute(): int
    {
        return $this->attemptAnswers()->count();
    }

    /**
     * Check if the attempt is complete.
     */
    public function getIsCompleteAttribute(): bool
    {
        return $this->answered_questions_count === $this->test->questions_count;
    }

    /**
     * Calculate and update the score.
     */
    public function calculateScore(): void
    {
        $totalScore = $this->attemptAnswers()->sum('score_awarded') ?? 0;
        $this->score = $totalScore;
        $this->passed = $totalScore >= $this->test->minimum_approved_grade;
        $this->save();
    }
}
