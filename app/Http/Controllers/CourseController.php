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
        $course = Course::with('topics')->findOrFail($id);
        return view('admin.courses.show', compact('user', 'course'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $course = Course::with('topics')->findOrFail($id);
        return view('admin.courses.edit', compact('user', 'course'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'topics' => 'array',
            'topics.*.id' => 'nullable|integer|exists:topics,id',
            'topics.*.name' => 'required_with:topics|string|max:255',
            'topics.*.description' => 'nullable|string',
            'topics.*.order_in_course' => 'nullable|integer',
            'topics.*.is_approved' => 'nullable|boolean',
            'topics.*.code' => 'nullable|string|max:50',
        ]);

        $course = Course::findOrFail($id);
        $course->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // Actualizar o crear topics
        if (!empty($validated['topics'])) {
            foreach ($validated['topics'] as $topicData) {
                if (!empty($topicData['id'])) {
                    // Actualizar topic existente
                    $topic = Topic::find($topicData['id']);
                    if ($topic && $topic->course_id == $course->id) {
                        $topic->update([
                            'name' => $topicData['name'],
                            'description' => $topicData['description'] ?? null,
                            'order_in_course' => $topicData['order_in_course'] ?? 1,
                            'is_approved' => $topicData['is_approved'] ?? false,
                            'code' => $topicData['code'] ?? null,
                        ]);
                    }
                } else {
                    // Crear nuevo topic
                    $course->topics()->create([
                        'name' => $topicData['name'],
                        'description' => $topicData['description'] ?? null,
                        'order_in_course' => $topicData['order_in_course'] ?? 1,
                        'is_approved' => $topicData['is_approved'] ?? false,
                        'code' => $topicData['code'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.courses.index')->with('success', 'Curso actualizado exitosamente');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->topics()->delete();
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Curso y temas eliminados exitosamente');
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