<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Test;
use App\Models\Question;
use App\Models\Video;
use App\Models\User;
use App\Models\Attempt;
use Illuminate\Support\Str;

class CourseController extends Controller
{

    public function dashboard()
    {   
        $user = Auth::user();
        $courses = Course::all();
        return view('admin.courses.dashboard', compact('user', 'courses'));
    }

    /**
    * Display a listing of the courses.
    */
    public function index()
    {
        $user = Auth::user();
        $query = Course::query();
        if ($search = request('q')) {
            $query->where('name', 'like', "%$search%");
        }
        $courses = $query->orderByDesc('created_at')->paginate(9)->withQueryString();
        return view('admin.courses.index', compact('user', 'courses'));
    }


    public function create()
    {
        $user = Auth::user();
        // Limpiar cualquier dato de sesión anterior
        session()->forget('course_creation');
        return view('admin.courses.create-step1', compact('user'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Guardar en sesión para el siguiente paso
        session(['course_creation.step1' => $validated]);

        return redirect()->route('admin.courses.create.step2');
    }

    public function createStep2()
    {
        $user = Auth::user();
        $courseData = session('course_creation.step1');
        if (!$courseData) {
            return redirect()->route('admin.courses.create')
                ->with('error', 'Debes completar el primer paso.');
        }
        // Obtener todos los topics existentes aprobados (o todos, según necesidad)
        $existingTopics = Topic::orderBy('name')->get();
        return view('admin.courses.create-step2', compact('user', 'courseData', 'existingTopics'));
    }

    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'topics' => 'required|array|min:1',
            'topics.*.name' => 'required|string|max:255',
            'topics.*.description' => 'nullable|string',
        ]);

        // Procesar topics y agregar order_in_course y code automáticamente
        $processedTopics = [];
        foreach ($validated['topics'] as $index => $topic) {
            $processedTopics[] = [
                'name' => $topic['name'],
                'description' => $topic['description'] ?? '',
                'order_in_course' => $index + 1,
                'code' => $this->generateTopicCode($topic['name']),
                'is_approved' => false,
            ];
        }

        // Guardar en sesión
        session(['course_creation.step2' => $processedTopics]);

        return redirect()->route('admin.courses.create.step3');
    }

    public function createStep3()
    {
        $user = Auth::user();
        $courseData = session('course_creation.step1');
        $topicsData = session('course_creation.step2');
        if (!$courseData || !$topicsData) {
            return redirect()->route('admin.courses.create')
                ->with('error', 'Debes completar los pasos anteriores.');
        }

        // Buscar tests existentes para cada topic por nombre (ya que aún no existen en BD)
        $testsByTopicName = [];
        foreach ($topicsData as $topic) {
            $testsByTopicName[$topic['name']] = Test::where('name', 'like', '%' . $topic['name'] . '%')->get();
        }

        return view('admin.courses.create-step3', compact('user', 'courseData', 'topicsData', 'testsByTopicName'));
    }

    public function finishCreation(Request $request)
    {
        $courseData = session('course_creation.step1');
        $topicsData = session('course_creation.step2');
        
        if (!$courseData || !$topicsData) {
            return redirect()->route('admin.courses.create')
                ->with('error', 'Sesión expirada. Intenta nuevamente.');
        }

        try {
            // Crear el curso
            $course = Course::create([
                'name' => $courseData['name'],
                'description' => $courseData['description'],
            ]);

            // Crear los topics y asociar tests seleccionados
            foreach ($topicsData as $index => $topicData) {
                $topic = $course->topics()->create($topicData);
                // Asociar tests seleccionados (si los hay)
                $selectedTestIds = $request->input("topic_{$index}_tests", []);
                if (!empty($selectedTestIds)) {
                    foreach ($selectedTestIds as $testId) {
                        $test = Test::find($testId);
                        if ($test) {
                            $test->topic_id = $topic->id;
                            $test->save();
                        }
                    }
                }
            }

            // Limpiar sesión
            session()->forget('course_creation');

            return redirect()->route('admin.courses.index')
                ->with('success', 'Curso creado exitosamente con ' . count($topicsData) . ' temas.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el curso: ' . $e->getMessage());
        }
    }

    private function generateTopicCode($name)
    {
        // Crear un código basado en el nombre (primeras letras + número aleatorio)
        $code = strtoupper(substr(Str::slug($name), 0, 3));
        $code .= '-' . sprintf('%03d', rand(1, 999));
        
        // Verificar que no exista (opcional, para garantizar unicidad)
        while (Topic::where('code', $code)->exists()) {
            $code = strtoupper(substr(Str::slug($name), 0, 3)) . '-' . sprintf('%03d', rand(1, 999));
        }
        
        return $code;
    }
   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'topics' => 'array',
            'topics.*.name' => 'required_with:topics|string|max:255',
            'topics.*.description' => 'nullable|string',
            'topics.*.order_in_course' => 'nullable|integer',
            'topics.*.is_approved' => 'nullable|boolean',
            'topics.*.code' => 'nullable|string|max:50',
        ]);

        $course = Course::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        if (!empty($validated['topics'])) {
            foreach ($validated['topics'] as $topicData) {
                $course->topics()->create([
                    'name' => $topicData['name'],
                    'description' => $topicData['description'] ?? null,
                    'order_in_course' => $topicData['order_in_course'] ?? 1,
                    'is_approved' => $topicData['is_approved'] ?? false,
                    'code' => $topicData['code'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.courses.index')->with('success', 'Curso y temas creados exitosamente');
    }

    public function show($id)
    {
        $user = Auth::user();
        $course = Course::with('topicsOrdered')->findOrFail($id);
        return view('admin.courses.show', compact('user', 'course'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $course = Course::with(['topicsOrdered.videos', 'topicsOrdered.tests'])->findOrFail($id);
        
        // Obtener todos los temas existentes para la opción de seleccionar
        $existingTopics = Topic::orderBy('name')->get();
        
        return view('admin.courses.edit', compact('user', 'course', 'existingTopics'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'topics' => 'array',
            'topics.*.id' => 'nullable|integer|exists:topics,id',
            'topics.*.existing_id' => 'nullable|integer|exists:topics,id',
            'topics.*.name' => 'nullable|string|max:255',
            'topics.*.description' => 'nullable|string',
            'topics.*.order_in_course' => 'nullable|integer',
            'topics.*.is_approved' => 'nullable|boolean',
            'topics.*.code' => 'nullable|string|max:50',
            'videos' => 'nullable|array',
            'videos.*' => 'nullable|array',
            'videos.*.*' => 'nullable|array',
            'videos.*.*.id' => 'nullable|integer|exists:videos,id',
            'videos.*.*.name' => 'nullable|string|max:255',
            'videos.*.*.url' => 'nullable|string',
            'videos.*.*.code' => 'nullable|string|max:50',
            'videos.*.*.length_seconds' => 'nullable|integer|min:0',
            'new_videos' => 'nullable|array',
            'new_videos.*' => 'nullable|array',
            'new_videos.*.*' => 'nullable|array',
            'new_videos.*.*.name' => 'nullable|string|max:255',
            'new_videos.*.*.url' => 'nullable|string',
            'new_videos.*.*.code' => 'nullable|string|max:50',
            'new_videos.*.*.length_seconds' => 'nullable|integer|min:0',
        ]);

        $course = Course::findOrFail($id);
        $course->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // Obtener IDs de topics existentes en el curso
        $existingTopicIds = $course->topics()->pluck('topics.id')->toArray();
        $processedTopicIds = [];

        // Actualizar o crear topics
        if (!empty($validated['topics'])) {
            foreach ($validated['topics'] as $index => $topicData) {
                if (!empty($topicData['existing_id'])) {
                    // Asociar tema existente al curso
                    $existingTopic = Topic::find($topicData['existing_id']);
                    if ($existingTopic) {
                        // Verificar si ya está asociado al curso
                        if (!$course->topics()->where('topics.id', $existingTopic->id)->exists()) {
                            $course->topics()->attach($existingTopic->id, [
                                'order_in_course' => $topicData['order_in_course'] ?? $index + 1
                            ]);
                        } else {
                            // Solo actualizar el orden si ya está asociado
                            $course->topics()->updateExistingPivot($existingTopic->id, [
                                'order_in_course' => $topicData['order_in_course'] ?? $index + 1
                            ]);
                        }
                        $processedTopicIds[] = $existingTopic->id;
                    }
                } elseif (!empty($topicData['id'])) {
                    // Actualizar topic existente
                    $topic = Topic::find($topicData['id']);
                    if ($topic && in_array($topic->id, $existingTopicIds)) {
                        $topic->update([
                            'name' => $topicData['name'],
                            'description' => $topicData['description'] ?? null,
                            'is_approved' => $topicData['is_approved'] ?? false,
                            'code' => $topicData['code'] ?? null,
                        ]);
                        
                        // Actualizar pivot con order_in_course
                        $course->topics()->updateExistingPivot($topic->id, [
                            'order_in_course' => $topicData['order_in_course'] ?? $index + 1
                        ]);
                        
                        $processedTopicIds[] = $topic->id;
                    }
                } elseif (!empty($topicData['name'])) {
                    // Crear nuevo topic
                    $topic = Topic::create([
                        'name' => $topicData['name'],
                        'description' => $topicData['description'] ?? null,
                        'is_approved' => $topicData['is_approved'] ?? false,
                        'code' => $topicData['code'] ?? $this->generateTopicCode($topicData['name']),
                    ]);
                    
                    // Asociar el topic al curso
                    $course->topics()->attach($topic->id, [
                        'order_in_course' => $topicData['order_in_course'] ?? $index + 1
                    ]);
                    
                    $processedTopicIds[] = $topic->id;
                }
            }
        }

        // Eliminar topics que ya no están en la lista
        $topicsToRemove = array_diff($existingTopicIds, $processedTopicIds);
        if (!empty($topicsToRemove)) {
            foreach ($topicsToRemove as $topicId) {
                $topic = Topic::find($topicId);
                if ($topic) {
                    // Eliminar videos del topic
                    $topic->videos()->delete();
                    // Desasociar del curso
                    $course->topics()->detach($topicId);
                    // Si el topic no está asociado a otros cursos, eliminarlo
                    if ($topic->courses()->count() == 0) {
                        $topic->delete();
                    }
                }
            }
        }

        // Procesar videos existentes
        if (!empty($validated['videos'])) {
            foreach ($validated['videos'] as $topicId => $videos) {
                $topic = Topic::find($topicId);
                if ($topic && in_array($topic->id, $processedTopicIds)) {
                    foreach ($videos as $videoData) {
                        // Verificar que $videoData sea un array válido con datos
                        if (is_array($videoData) && !empty($videoData['id'])) {
                            // Actualizar video existente
                            $video = Video::find($videoData['id']);
                            if ($video && $video->topic_id == $topic->id) {
                                $video->update([
                                    'name' => $videoData['name'] ?? '',
                                    'url' => $videoData['url'] ?? '',
                                    'code' => $videoData['code'] ?? '',
                                    'length_seconds' => $videoData['length_seconds'] ?? 0,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        // Procesar nuevos videos
        if (!empty($validated['new_videos'])) {
            foreach ($validated['new_videos'] as $topicIndex => $videos) {
                // Buscar el topic correspondiente por índice
                if (isset($validated['topics'][$topicIndex])) {
                    $topicData = $validated['topics'][$topicIndex];
                    $topic = null;
                    
                    if (!empty($topicData['id'])) {
                        $topic = Topic::find($topicData['id']);
                    } else {
                        // Para topics nuevos, buscar por nombre recién creado
                        $topic = Topic::where('name', $topicData['name'])
                                     ->whereHas('courses', function($q) use ($course) {
                                         $q->where('courses.id', $course->id);
                                     })->first();
                    }
                    
                    if ($topic && is_array($videos)) {
                        foreach ($videos as $videoData) {
                            // Verificar que los campos requeridos estén presentes y sean válidos
                            if (is_array($videoData) && 
                                (isset($videoData['name']) || isset($videoData['url'])) && 
                                (!empty($videoData['name']) || !empty($videoData['url']))) {
                                $topic->videos()->create([
                                    'name' => $videoData['name'] ?? '',
                                    'url' => $videoData['url'] ?? '',
                                    'code' => $videoData['code'] ?? null,
                                    'length_seconds' => $videoData['length_seconds'] ?? 0,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.courses.show', $course->id)
                        ->with('success', 'Curso actualizado exitosamente');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        
        // Eliminar videos de todos los topics del curso
        foreach ($course->topics as $topic) {
            $topic->videos()->delete();
        }
        
        // Desasociar topics del curso
        $course->topics()->detach();
        
        // Eliminar el curso
        $course->delete();
        
        return redirect()->route('admin.courses.index')->with('success', 'Curso eliminado exitosamente');
    }

    /**
     * Remove a video from a topic via AJAX
     */
    public function removeVideo($videoId)
    {
        try {
            $video = Video::findOrFail($videoId);
            $video->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Video eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the statistics dashboard
     */
    public function statsDashboard()
    {
        $user = Auth::user();       

        // Estadísticas generales
        $totalCourses = Course::count();
        $activeCourses = Course::where('is_active', true)->count();
        $totalTopics = Topic::count();
        $totalVideos = Video::count();
        $totalTests = Test::count();
        $totalQuestions = Question::count();
        $totalUsers = User::count();
        $totalAttempts = Attempt::count();
        $passedAttempts = Attempt::where('passed', true)->count();
        
        // Estadísticas por mes (últimos 6 meses)
        $monthlyAttempts = Attempt::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyUsers = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Estadísticas por cursos
        $courseStats = Course::with('topics')->get()->map(function ($course) {
            $videosCount = 0;
            $testsCount = 0;
            $totalAttempts = 0;
            $passedAttempts = 0;
            
            foreach ($course->topics as $topic) {
                $videosCount += Video::where('topic_id', $topic->id)->count();
                $testsCount += Test::where('topic_id', $topic->id)->count();
                
                $topicAttempts = Attempt::whereHas('test', function($query) use ($topic) {
                    $query->where('topic_id', $topic->id);
                })->count();
                
                $topicPassedAttempts = Attempt::whereHas('test', function($query) use ($topic) {
                    $query->where('topic_id', $topic->id);
                })->where('passed', true)->count();
                
                $totalAttempts += $topicAttempts;
                $passedAttempts += $topicPassedAttempts;
            }

            return [
                'name' => $course->name,
                'topics_count' => $course->topics->count(),
                'videos_count' => $videosCount,
                'tests_count' => $testsCount,
                'total_attempts' => $totalAttempts,
                'passed_attempts' => $passedAttempts,
                'success_rate' => $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 2) : 0,
            ];
        });

        // Top usuarios con más intentos exitosos
        $topUsers = User::withCount(['passedAttempts'])
            ->orderBy('passed_attempts_count', 'desc')
            ->take(10)
            ->get();

        // Estadísticas por tipo de usuario
        $usersByRole = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->selectRaw('roles.name as role_name, COUNT(*) as total')
            ->groupBy('roles.name')
            ->pluck('total', 'role_name');

        // Tests más difíciles (menor tasa de aprobación)
        $difficultTests = Test::with('topic')
            ->get()
            ->map(function($test) {
                $attemptsCount = Attempt::where('test_id', $test->id)->count();
                $passedAttemptsCount = Attempt::where('test_id', $test->id)->where('passed', true)->count();
                
                $successRate = $attemptsCount > 0 ? 
                    round(($passedAttemptsCount / $attemptsCount) * 100, 2) : 0;
                    
                return [
                    'name' => $test->name ?? $test->title ?? 'Test sin nombre',
                    'topic' => $test->topic->name ?? 'Sin tema',
                    'attempts' => $attemptsCount,
                    'success_rate' => $successRate,
                ];
            })
            ->filter(function($test) {
                return $test['attempts'] > 0;
            })
            ->sortBy('success_rate')
            ->take(10);

        return view('admin.courses.stats-dashboard', compact(
            'user', 
            'totalCourses', 
            'activeCourses',
            'totalTopics', 
            'totalVideos', 
            'totalTests', 
            'totalQuestions',
            'totalUsers',
            'totalAttempts',
            'passedAttempts',
            'monthlyAttempts',
            'monthlyUsers', 
            'courseStats',
            'topUsers',
            'usersByRole',
            'difficultTests'
        ));
    }

}