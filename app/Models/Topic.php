<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Course;
use App\Models\Video;
use App\Models\Test;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_approved',
        'code',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Relación muchos a muchos: usuarios que cursan este topic.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status', 'approved_at', 'score')
            ->withTimestamps();
    }

    /**
     * Relación muchos a muchos: los cursos que usan este topic.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class)
            ->withPivot('order_in_course')
            ->withTimestamps();
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

}
