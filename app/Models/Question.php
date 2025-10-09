<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    const TYPE_FREE_TEXT = 'free_text';
    const TYPE_MULTIPLE_CHOICE = 'multiple_choice';
    const TYPE_SINGLE_CHOICE = 'single_choice';

    protected $fillable = [
        'test_id',
        'question_text',
        'type',
        'score_value',
        'correct_answer_id',
    ];

    protected $casts = [
        'score_value' => 'decimal:2',
    ];

    /**
     * Get the test that owns the question.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get the answers for the question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the correct answer for the question.
     */
    public function correctAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'correct_answer_id');
    }

    /**
     * Get the attempt answers for this question.
     */
    public function attemptAnswers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    /**
     * Get only correct answers.
     */
    public function correctAnswers(): HasMany
    {
        return $this->hasMany(Answer::class)->where('is_correct', true);
    }

    /**
     * Get only incorrect answers.
     */
    public function incorrectAnswers(): HasMany
    {
        return $this->hasMany(Answer::class)->where('is_correct', false);
    }

    /**
     * Scope for multiple choice questions.
     */
    public function scopeMultipleChoice($query)
    {
        return $query->where('type', self::TYPE_MULTIPLE_CHOICE);
    }

    /**
     * Scope for single choice questions.
     */
    public function scopeSingleChoice($query)
    {
        return $query->where('type', self::TYPE_SINGLE_CHOICE);
    }

    /**
     * Scope for free text questions.
     */
    public function scopeFreeText($query)
    {
        return $query->where('type', self::TYPE_FREE_TEXT);
    }

    /**
     * Check if question is multiple choice.
     */
    public function getIsMultipleChoiceAttribute(): bool
    {
        return $this->type === self::TYPE_MULTIPLE_CHOICE;
    }

    /**
     * Check if question is single choice.
     */
    public function getIsSingleChoiceAttribute(): bool
    {
        return $this->type === self::TYPE_SINGLE_CHOICE;
    }

    /**
     * Check if question is free text.
     */
    public function getIsFreeTextAttribute(): bool
    {
        return $this->type === self::TYPE_FREE_TEXT;
    }

    /**
     * Get available question types.
     */
    public static function getAvailableTypes(): array
    {
        return [
            self::TYPE_FREE_TEXT => 'Respuesta libre',
            self::TYPE_MULTIPLE_CHOICE => 'Opción múltiple',
            self::TYPE_SINGLE_CHOICE => 'Opción única',
        ];
    }
}
