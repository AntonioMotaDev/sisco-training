<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'name',
        'description',
        'minimum_approved_grade',
    ];

    protected $casts = [
        'minimum_approved_grade' => 'decimal:2',
    ];

    /**
     * Get the topic that owns the test.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the questions for the test.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the attempts for the test.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    /**
     * Get attempts for a specific user.
     */
    public function attemptsForUser($userId): HasMany
    {
        return $this->hasMany(Attempt::class)->where('user_id', $userId);
    }

    /**
     * Get the total possible score for the test.
     */
    public function getTotalScoreAttribute(): float
    {
        return $this->questions()->sum('score_value') ?? 0;
    }

    /**
     * Get the total number of questions in the test.
     */
    public function getQuestionsCountAttribute(): int
    {
        return $this->questions()->count();
    }

    /**
     * Check if a user has passed this test.
     */
    public function hasUserPassed($userId): bool
    {
        $bestAttempt = $this->attemptsForUser($userId)
            ->orderByDesc('score')
            ->first();

        if (!$bestAttempt) {
            return false;
        }

        return $bestAttempt->score >= $this->minimum_approved_grade;
    }

    /**
     * Get the best attempt for a user.
     */
    public function getBestAttemptForUser($userId): ?Attempt
    {
        return $this->attemptsForUser($userId)
            ->orderByDesc('score')
            ->first();
    }

    /**
     * Check if the test is available (has questions).
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->questions()->count() > 0;
    }
}
