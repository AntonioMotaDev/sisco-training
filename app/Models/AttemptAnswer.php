<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer_id',
        'selected_answer_id',
        'free_text_answer',
        'is_correct',
        'score_awarded',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'score_awarded' => 'decimal:2',
    ];

    /**
     * Get the attempt that owns the attempt answer.
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class);
    }

    /**
     * Get the question that owns the attempt answer.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the selected answer.
     */
    public function selectedAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'selected_answer_id');
    }

    /**
     * Scope for correct attempt answers.
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    /**
     * Scope for incorrect attempt answers.
     */
    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    /**
     * Scope for multiple choice answers.
     */
    public function scopeMultipleChoice($query)
    {
        return $query->whereNotNull('selected_answer_id');
    }

    /**
     * Scope for free text answers.
     */
    public function scopeFreeText($query)
    {
        return $query->whereNotNull('free_text_answer');
    }

    /**
     * Get the display value of the answer.
     */
    public function getAnswerDisplayAttribute(): string
    {
        if ($this->selected_answer_id && $this->selectedAnswer) {
            return $this->selectedAnswer->answer_text;
        }

        if ($this->free_text_answer) {
            return $this->free_text_answer;
        }

        return 'Sin respuesta';
    }

    /**
     * Check if this is a multiple choice answer.
     */
    public function getIsMultipleChoiceAttribute(): bool
    {
        return !is_null($this->selected_answer_id);
    }

    /**
     * Check if this is a free text answer.
     */
    public function getIsFreeTextAttribute(): bool
    {
        return !is_null($this->free_text_answer);
    }

    /**
     * Get the percentage score for this answer.
     */
    public function getPercentageAttribute(): float
    {
        $maxScore = $this->question->score_value ?? 1;
        return round(($this->score_awarded / $maxScore) * 100, 2);
    }

    /**
     * Automatically calculate if the answer is correct and assign score.
     */
    public function evaluateAnswer(): void
    {
        $question = $this->question;
        
        if ($question->is_multiple_choice || $question->is_single_choice) {
            // Para preguntas de opción múltiple o única
            if ($this->selectedAnswer && $this->selectedAnswer->is_correct) {
                $this->is_correct = true;
                $this->score_awarded = $question->score_value;
            } else {
                $this->is_correct = false;
                $this->score_awarded = 0.0;
            }
        } else {
            // Para preguntas de texto libre, requiere evaluación manual
            // Por defecto se marca como incorrecta hasta evaluación manual
            $this->is_correct = false;
            $this->score_awarded = 0.00;
        }

        $this->save();
    }
}
