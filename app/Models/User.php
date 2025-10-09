<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Topic;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
        'access_token',
        'session_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'access_token',
        'session_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the JWT subject claim.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [
            'role_id' => $this->role_id,
            'username' => $this->username,
            'role_name' => $this->role->name ?? null,
        ];
    }

    /**
     * Get the user's role
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    /**
     * Check if user is technician
     */
    public function isTechnician(): bool
    {
        return $this->hasRole('Técnico');
    }

    /**
     * Check if user is client
     */
    public function isClient(): bool
    {
        return $this->hasRole('Cliente');
    }

    /**
     * Get the access tokens for the user.
     */
    public function accessTokens(): HasMany
    {
        return $this->hasMany(AccessToken::class);
    }

    /**
     * Get the attempts for the user.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    /**
     * Get the user's passed attempts.
     */
    public function passedAttempts(): HasMany
    {
        return $this->hasMany(Attempt::class)->where('passed', true);
    }

    /**
     * Get the user's failed attempts.
     */
    public function failedAttempts(): HasMany
    {
        return $this->hasMany(Attempt::class)->where('passed', false);
    }

    /**
     * Get courses the user has taken (has attempts).
     */
    public function takenCourses()
    {
        return Course::whereHas('topics.tests.attempts', function ($query) {
            $query->where('user_id', $this->id);
        })->distinct();
    }

    /**
     * Get the user's progress in a specific course.
     */
    public function getCourseProgress($courseId): array
    {
        $course = Course::find($courseId);
        if (!$course) {
            return ['total_tests' => 0, 'passed_tests' => 0, 'progress_percentage' => 0];
        }

        $totalTests = Test::whereHas('topic', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->count();

        $passedTests = $this->attempts()
            ->where('passed', true)
            ->whereHas('test.topic', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->distinct('test_id')
            ->count();

        $progressPercentage = $totalTests > 0 ? round(($passedTests / $totalTests) * 100, 2) : 0;

        return [
            'total_tests' => $totalTests,
            'passed_tests' => $passedTests,
            'progress_percentage' => $progressPercentage,
        ];
    }

    /**
     * Check if user has completed a specific course.
     */
    public function hasCompletedCourse($courseId): bool
    {
        $progress = $this->getCourseProgress($courseId);
        return $progress['progress_percentage'] >= 100;
    }

    /**
     * Get user's overall statistics.
     */
    public function getStatsAttribute(): array
    {
        return [
            'total_attempts' => $this->attempts()->count(),
            'passed_attempts' => $this->passedAttempts()->count(),
            'failed_attempts' => $this->failedAttempts()->count(),
            'success_rate' => $this->attempts()->count() > 0 
                ? round(($this->passedAttempts()->count() / $this->attempts()->count()) * 100, 2)
                : 0,
        ];
    }

    /**
     * Relación muchos a muchos: progreso del usuario en los topics.
     */
    public function topics()
    {
        return $this->belongsToMany(Topic::class)
            ->withPivot('status', 'approved_at', 'score')
            ->withTimestamps();
    }
}
