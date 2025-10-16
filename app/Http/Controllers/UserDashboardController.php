<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Video;
use App\Models\Test;
use App\Models\Attempt;
use App\Models\User;

class UserDashboardController extends Controller
{
    /**
     * Dashboard principal del usuario (técnico/cliente)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Verificar que no sea admin
        if ($user->isAdmin()) {
            return redirect()->route('dashboard-admin');
        }

        // Obtener cursos inscritos con progreso
        $enrolledCourses = $user->enrolledCourses()
            ->with(['topics.videos', 'topics.tests'])
            ->get()
            ->map(function ($course) use ($user) {
                $progress = $user->getCourseProgress($course->id);
                $course->progress = $progress;
                return $course;
            });

        // Estadísticas del usuario
        $stats = [
            'total_courses' => $enrolledCourses->count(),
            'active_courses' => $enrolledCourses->where('pivot.status', 'active')->count(),
            'completed_courses' => $enrolledCourses->where('pivot.status', 'completed')->count(),
            'total_attempts' => $user->attempts()->count(),
            'passed_attempts' => $user->passedAttempts()->count(),
            'success_rate' => $user->attempts()->count() > 0 
                ? round(($user->passedAttempts()->count() / $user->attempts()->count()) * 100, 2) 
                : 0,
        ];

        return view('user.dashboard.index', compact('user', 'enrolledCourses', 'stats'));
    }

    /**
     * Mostrar detalles de un curso específico
     */
    public function showCourse(Course $course)
    {
        $user = Auth::user();

        // Verificar que el usuario esté inscrito en el curso
        if (!$user->isEnrolledInCourse($course->id)) {
            abort(404, 'No estás inscrito en este curso');
        }

        // Cargar topics con videos y tests
        $course->load(['topics.videos', 'topics.tests.questions']);

        // Obtener progreso del usuario en el curso
        $progress = $user->getCourseProgress($course->id);

        // Obtener intentos del usuario en los tests del curso
        $userAttempts = $user->attempts()
            ->whereHas('test.topic.courses', function ($query) use ($course) {
                $query->where('courses.id', $course->id);
            })
            ->with('test')
            ->get()
            ->keyBy('test_id');

        return view('user.courses.show', compact('user', 'course', 'progress', 'userAttempts'));
    }

    /**
     * Mostrar un topic específico del curso
     */
    public function showTopic(Course $course, Topic $topic)
    {
        $user = Auth::user();

        // Verificar que el usuario esté inscrito en el curso
        if (!$user->isEnrolledInCourse($course->id)) {
            abort(404, 'No estás inscrito en este curso');
        }

        // Verificar que el topic pertenezca al curso
        if (!$course->topics->contains($topic)) {
            abort(404, 'Este topic no pertenece al curso');
        }

        // Cargar videos y tests del topic
        $topic->load(['videos', 'tests.questions']);

        // Obtener intentos del usuario en los tests del topic
        $userAttempts = $user->attempts()
            ->whereHas('test', function ($query) use ($topic) {
                $query->where('topic_id', $topic->id);
            })
            ->with('test')
            ->get()
            ->keyBy('test_id');

        return view('user.topics.show', compact('user', 'course', 'topic', 'userAttempts'));
    }

    /**
     * Mostrar un video específico
     */
    public function showVideo(Course $course, Topic $topic, Video $video)
    {
        $user = Auth::user();

        // Verificar permisos
        if (!$user->isEnrolledInCourse($course->id)) {
            abort(404, 'No estás inscrito en este curso');
        }

        if (!$course->topics->contains($topic) || $topic->videos->where('id', $video->id)->isEmpty()) {
            abort(404, 'Este video no pertenece al topic o curso');
        }

        return view('user.videos.show', compact('user', 'course', 'topic', 'video'));
    }

    /**
     * Mostrar un test específico
     */
    public function showTest(Course $course, Topic $topic, Test $test)
    {
        $user = Auth::user();

        // Verificar permisos
        if (!$user->isEnrolledInCourse($course->id)) {
            abort(404, 'No estás inscrito en este curso');
        }

        if (!$course->topics->contains($topic) || $topic->tests->where('id', $test->id)->isEmpty()) {
            abort(404, 'Este test no pertenece al topic o curso');
        }

        // Cargar preguntas con respuestas
        $test->load('questions.answers');

        // Obtener intentos previos del usuario en este test
        $userAttempts = $user->attempts()
            ->where('test_id', $test->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.tests.show', compact('user', 'course', 'topic', 'test', 'userAttempts'));
    }

    /**
     * Procesar respuesta de un test
     */
    public function submitTest(Request $request, Course $course, Topic $topic, Test $test)
    {
        $user = Auth::user();

        // Verificar permisos
        if (!$user->isEnrolledInCourse($course->id)) {
            abort(404, 'No estás inscrito en este curso');
        }

        if (!$course->topics->contains($topic) || $topic->tests->where('id', $test->id)->isEmpty()) {
            abort(404, 'Este test no pertenece al topic o curso');
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer|exists:answers,id',
        ]);

        // Crear el intento
        $attempt = Attempt::create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'score' => 0,
            'passed' => false,
        ]);

        $correctAnswers = 0;
        $totalQuestions = $test->questions->count();

        // Procesar cada respuesta
        foreach ($request->answers as $questionId => $answerId) {
            $question = $test->questions->find($questionId);
            if ($question && $question->correct_answer == $answerId) {
                $correctAnswers++;
            }

            // Guardar la respuesta del usuario
            $attempt->attemptAnswers()->create([
                'question_id' => $questionId,
                'answer_id' => $answerId,
            ]);
        }

        // Calcular puntuación y si pasó
        $score = ($correctAnswers / $totalQuestions) * 100;
        $passed = $score >= 70; // 70% para pasar

        $attempt->update([
            'score' => $score,
            'passed' => $passed,
        ]);

        // Actualizar progreso del curso si es necesario
        $this->updateCourseProgress($user, $course);

        return redirect()
            ->route('user.tests.show', [$course, $topic, $test])
            ->with('success', $passed 
                ? "¡Felicitaciones! Has pasado el test con {$score}% de aciertos." 
                : "Has obtenido {$score}% de aciertos. Necesitas 70% o más para pasar."
            );
    }

    /**
     * Actualizar progreso del usuario en el curso
     */
    private function updateCourseProgress(User $user, Course $course)
    {
        $progress = $user->getCourseProgress($course->id);
        
        // Actualizar el progreso en la tabla pivot
        $user->enrolledCourses()->updateExistingPivot($course->id, [
            'progress_percentage' => $progress['progress_percentage'],
            'status' => $progress['progress_percentage'] >= 100 ? 'completed' : 'active',
            'completed_at' => $progress['progress_percentage'] >= 100 ? now() : null,
        ]);
    }
}
