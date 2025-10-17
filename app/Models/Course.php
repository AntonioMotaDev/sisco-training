<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * Relación muchos a muchos: los topics de este curso.
     */
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'course_topic')
            ->withPivot('order_in_course')
            ->withTimestamps();
    }

    /**
     * Relación muchos a muchos: los topics de este curso ordenados.
     */
    public function topicsOrdered()
    {
        return $this->belongsToMany(Topic::class, 'course_topic')
            ->withPivot('order_in_course')
            ->withTimestamps()
            ->orderBy('course_topic.order_in_course', 'asc');
    }

    /**
     * Devuelve la duración total del curso (en segundos) sumando los videos de todos los topics.
     * Optimizado para uso con eager loading: with('topics.videos')
     */
    public function getDuration()
    {
        // Suma total en segundos
        $totalSeconds = 0;
        if ($this->relationLoaded('topics')) {
            $totalSeconds = $this->topics->sum(fn($topic) => $topic->relationLoaded('videos')
                ? $topic->videos->sum('length_seconds')
                : $topic->videos()->sum('length_seconds')
            );
        } else {
            $totalSeconds = Topic::whereHas('courses', fn($q) => $q->where('courses.id', $this->id))
                ->with('videos')
                ->get()
                ->sum(fn($topic) => $topic->videos->sum('length_seconds'));
        }
        // Contar tests asociados a los topics del curso
        $testCount = 0; 
        if ($this->relationLoaded('topics')) {
            $testCount = $this->topics->sum(fn($topic) => $topic->relationLoaded('tests')
                ? $topic->tests->count()
                : $topic->tests()->count()
            );
        } else {
            $testCount = Topic::whereHas('courses', fn($q) => $q->where('courses.id', $this->id))
                ->withCount('tests')
                ->get()
                ->sum('tests_count');
        }
        // Convertir a minutos (redondeando hacia arriba si hay segundos) y sumar 5 minutos por test
        return (int) ceil($totalSeconds / 60) + ($testCount * 5);
    }

    /**
     * Devuelve la cantidad de estudiantes únicos inscritos en el curso a través de sus topics.
     * Optimizado para uso con eager loading: with('topics.users')
     */
    public function getStudentsCount()
    {
        if ($this->relationLoaded('topics')) {
            return $this->topics->flatMap(fn($topic) => $topic->relationLoaded('users')
                ? $topic->users
                : $topic->users()->get()
            )->unique('id')->count();
        }
        // Consulta directa si no hay relaciones cargadas
        return Topic::whereHas('courses', fn($q) => $q->where('courses.id', $this->id))
            ->with('users')
            ->get()
            ->flatMap(fn($topic) => $topic->users)
            ->unique('id')
            ->count();
    }

    /**
     * Relación muchos a muchos: usuarios inscritos en este curso.
     */
    public function enrolledUsers()
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot('enrolled_at', 'completed_at', 'progress_percentage', 'status')
            ->withTimestamps();
    }

    /**
     * Obtener usuarios activos inscritos en el curso.
     */
    public function activeStudents()
    {
        return $this->enrolledUsers()->wherePivot('status', 'active');
    }

    /**
     * Obtener usuarios que completaron el curso.
     */
    public function completedStudents()
    {
        return $this->enrolledUsers()->wherePivot('status', 'completed');
    }

}
