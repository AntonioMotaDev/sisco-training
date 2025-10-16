<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Role;
use App\Models\Course;
use App\Models\AccessToken;
use Carbon\Carbon;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Estadísticas generales
        $totalUsers = User::where('role_id', '!=', 1)->get();
        // dd($totalUsers);
        $tokenUsers = User::whereNotNull('access_token')->where('role_id', '!=', 1)->get();
        $accountUsers = User::whereNull('access_token')->where('role_id', '!=', 1)->get();
        $activeUsers = User::where('role_id', '!=', 1)
            ->where(function($query) {
                $query->whereNull('access_token')
                      ->orWhereHas('accessTokens', function($q) {
                          $q->where('expires_at', '>', now());
                      });
            })->count();
        
        return view('admin.users.dashboard', compact(
            'user', 'totalUsers', 'tokenUsers', 'accountUsers', 'activeUsers'
        ));
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = User::with(['role', 'accessTokens', 'attempts.test.topic.courses'])
                    ->where('role_id', '!=', 1); // Excluir admins
        
        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('type')) {
            if ($request->type === 'token') {
                $query->whereNotNull('access_token');
            } elseif ($request->type === 'account') {
                $query->whereNull('access_token');
            }
        }
        
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where(function($q) {
                    $q->whereNull('access_token')
                      ->orWhereHas('accessTokens', function($subq) {
                          $subq->where('expires_at', '>', now());
                      });
                });
            } elseif ($request->status === 'expired') {
                $query->whereNotNull('access_token')
                      ->whereDoesntHave('accessTokens', function($q) {
                          $q->where('expires_at', '>', now());
                      });
            }
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        $roles = Role::where('id', '!=', 1)->get(); // Excluir rol admin
        
        return view('admin.users.index', compact('user', 'users', 'roles'));
    }

    public function create()
    {
        $user = Auth::user();
        $roles = Role::where('id', '!=', 1)->get(); // Excluir rol admin
        
        return view('admin.users.create', compact('user', 'roles'));
    }

    public function store(Request $request)
    {
        $type = $request->input('type', 'account');
        // dd($request->all());
        
        if ($type === 'token') {
            // Validación para usuario con token
            $validated = $request->validate([
                'name_token' => 'required|string|max:255',
                'generated_token' => 'required|string|size:16|unique:users,access_token',
                'token_duration' => 'required|integer|in:1,3,7,14,30',
                'role_id_token' => ['required', 'exists:roles,id', Rule::notIn([1])],
                'type' => 'required|in:account,token',
            ]);

            // Generar username único basado en el nombre
            $username = $this->generateUniqueUsername($validated['name_token']);

            $userData = [
                'name' => $validated['name_token'],
                'username' => $username,
                'email' => null,
                'password' => Hash::make('token_user_' . $validated['generated_token']), // Password temporal
                'role_id' => $validated['role_id_token'],
                'access_token' => $validated['generated_token'],
            ];

            $newUser = User::create($userData);

            // Crear registro en access_tokens con la duración especificada
            AccessToken::create([
                'user_id' => $newUser->id,
                'token' => $validated['generated_token'],
                'expires_at' => now()->addDays((int)$validated['token_duration']),
                'is_active' => true,
            ]);

            $message = "Usuario con token creado exitosamente. Token: {$validated['generated_token']} (válido por {$validated['token_duration']} días)";

        } else {
            // Validación para usuario con cuenta
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'role_id' => ['required', 'exists:roles,id', Rule::notIn([1])],
                'type' => 'required|in:account,token',
            ]);

            $userData = [
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'],
                'access_token' => null,
            ];

            $newUser = User::create($userData);
            $message = "Usuario con cuenta creado exitosamente.";
        }

        return redirect()->route('admin.users.index')
                        ->with('success', $message);
    }

    public function show(User $user)
    {
        if ($user->isAdmin()) {
            abort(404);
        }

        $adminUser = Auth::user();
        
        // Cargar relaciones necesarias
        $user->load(['role', 'accessTokens', 'attempts.test.topic.courses']);
        
        // Obtener cursos en los que está inscrito
        $enrolledCourses = $user->takenCourses()->with('topics.tests')->get();
        
        // Calcular progreso en cada curso
        $coursesProgress = [];
        foreach ($enrolledCourses as $course) {
            $progress = $user->getCourseProgress($course->id);
            $coursesProgress[] = [
                'course' => $course,
                'progress' => $progress,
            ];
        }
        
        // Estadísticas del usuario
        $stats = $user->stats;
        
        return view('admin.users.show', compact(
            'adminUser', 'user', 'coursesProgress', 'stats'
        ));
    }

    public function edit(User $user)
    {
        if ($user->isAdmin()) {
            abort(404);
        }

        $adminUser = Auth::user();
        $roles = Role::where('id', '!=', 1)->get(); // Excluir rol admin
        
        return view('admin.users.edit', compact('adminUser', 'user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            abort(404);
        }

        $userName = $user->name;
        
        // Eliminar tokens de acceso relacionados
        $user->accessTokens()->delete();
        
        // Eliminar intentos relacionados (mantener el historial)
        // $user->attempts()->delete();
        
        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', "Usuario '{$userName}' eliminado exitosamente.");
    }

    /**
     * Renew access token for token-based users.
     */
    public function renewToken(Request $request, User $user)
    {
        if ($user->isAdmin() || !$user->access_token) {
            abort(404);
        }

        // Validar días
        $validated = $request->validate([
            'token_days' => 'required|integer|in:1,3,7,15,30',
        ]);
        $days = (int) $validated['token_days'];

        // Desactivar tokens antiguos
        $user->accessTokens()->update(['is_active' => false]);

        // Crear nuevo token
        $newToken = $this->generateUniqueToken();
        $user->update(['access_token' => $newToken]);

        AccessToken::create([
            'user_id' => $user->id,
            'token' => $newToken,
            'expires_at' => now()->addDays($days),
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'Token renovado exitosamente.');
    }

    /**
     * Show users enrolled in a specific course.
     */
    public function courseUsers(Course $course)
    {
        $adminUser = Auth::user();
        
        $users = User::whereHas('attempts.test.topic', function($query) use ($course) {
                        $query->whereHas('courses', function($q) use ($course) {
                            $q->where('courses.id', $course->id);
                        });
                    })
                    ->with(['role', 'attempts.test.topic.courses' => function($query) use ($course) {
                        $query->where('courses.id', $course->id);
                    }])
                    ->where('role_id', '!=', 1)
                    ->paginate(15);

        return view('admin.users.course-users', compact('adminUser', 'course', 'users'));
    }

    /**
     * Show course enrollment view for a user
     */
    public function showEnrollment(User $user)
    {
        if ($user->isAdmin()) {
            abort(404);
        }

        $adminUser = Auth::user();
        
        // Obtener todos los cursos disponibles
        $allCourses = Course::where('is_active', true)->with('topics')->get();
        
        // Obtener cursos en los que ya está inscrito
        $enrolledCourseIds = $user->enrolledCourses()->pluck('courses.id')->toArray();
        
        // Cursos disponibles para inscripción
        $availableCourses = $allCourses->whereNotIn('id', $enrolledCourseIds);
        
        // Cursos inscritos con progreso
        $enrolledCourses = $user->enrolledCourses()
            ->with('topics.tests')
            ->get()
            ->map(function ($course) use ($user) {
                $progress = $user->getCourseProgress($course->id);
                $course->progress = $progress;
                return $course;
            });

        return view('admin.users.enrollment', compact(
            'adminUser', 'user', 'availableCourses', 'enrolledCourses'
        ));
    }

    /**
     * Enroll user in courses
     */
    public function enrollCourses(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            abort(404);
        }

        $request->validate([
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id'
        ]);

        $enrolledCount = 0;
        $alreadyEnrolledCount = 0;

        foreach ($request->course_ids as $courseId) {
            // Verificar si ya está inscrito
            if ($user->isEnrolledInCourse($courseId)) {
                $alreadyEnrolledCount++;
                continue;
            }

            // Inscribir al usuario
            $user->enrolledCourses()->attach($courseId, [
                'enrolled_at' => now(),
                'status' => 'active',
                'progress_percentage' => 0
            ]);
            
            $enrolledCount++;
        }

        $message = "Se inscribió el usuario en {$enrolledCount} curso(s)";
        if ($alreadyEnrolledCount > 0) {
            $message .= ". {$alreadyEnrolledCount} curso(s) ya estaba(n) inscrito(s)";
        }

        return redirect()
            ->route('admin.users.enrollment', $user)
            ->with('success', $message);
    }

    /**
     * Unenroll user from a course
     */
    public function unenrollCourse(Request $request, User $user, Course $course)
    {
        if ($user->isAdmin()) {
            abort(404);
        }

        if (!$user->isEnrolledInCourse($course->id)) {
            return redirect()
                ->route('admin.users.enrollment', $user)
                ->with('error', 'El usuario no está inscrito en este curso');
        }

        // Desinscribir al usuario
        $user->enrolledCourses()->detach($course->id);

        return redirect()
            ->route('admin.users.enrollment', $user)
            ->with('success', "Usuario desinscrito del curso '{$course->name}' exitosamente");
    }

    /**
     * Generate a unique access token.
     */
    private function generateUniqueToken(): string
    {
        do {
            $token = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (User::where('access_token', $token)->exists());

        return $token;
    }

    /**
     * Generate a unique username based on name.
     */
    private function generateUniqueUsername(string $name): string
    {
        // Convertir nombre a username base (sin espacios, caracteres especiales, etc.)
        $baseUsername = strtolower(str_replace([' ', '.', '-', '_'], '', 
            iconv('UTF-8', 'ASCII//TRANSLIT', $name)
        ));
        
        // Remover caracteres no alfanuméricos
        $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
        
        // Limitar a 10 caracteres
        $baseUsername = substr($baseUsername, 0, 10);
        
        // Si el username base está disponible, usarlo
        if (!User::where('username', $baseUsername)->exists()) {
            return $baseUsername;
        }
        
        // Si no, agregar números hasta encontrar uno único
        $counter = 1;
        do {
            $username = $baseUsername . $counter;
            $counter++;
        } while (User::where('username', $username)->exists());
        
        return $username;
    }
}