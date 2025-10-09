<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Role;
use App\Models\Course;
use App\Models\Video;
use App\Models\Topic;
use App\Models\Test;
use App\Models\Question;
use App\Models\AccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class WebAuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show the token login form
     */
    public function showTokenLoginForm()
    {
        return view('auth.login-token');
    }

    /**
     * Show the request token form
     */
    public function showRequestTokenForm()
    {
        return view('auth.request-token');
    }

    /**
     * Show the dashboard after successful login
     */
    public function showDashboard()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder al dashboard');
        }
        if(isset($user->role_id) && $user->role_id == 1){
            return redirect()->route('dashboard-admin')->with('error', 'No tienes permisos para acceder a este dashboard');
        } 

        return view('dashboard.index', compact('user'));
    }

    public function showDashboardAdmin()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder al dashboard');
        }

        if (!isset($user->role_id) || $user->role_id != 1) {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a este dashboard');
        }

        $courses = Course::all();
        $videos = Video::all();
        $tests = Test::all();
        $currentStudents = User::where('role_id', '!=', 1)->get();
        return view('admin.dashboard.index', compact('user', 'courses', 'videos', 'tests', 'currentStudents'));
    }

    /**
     * Handle web-based login with username/password
     */
    public function login(Request $request)
    {
        Log::info('Login attempt started', ['username' => $request->username]);
        
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'El nombre de usuario es requerido',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        if ($validator->fails()) {
            Log::info('Validation failed', ['errors' => $validator->errors()]);
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('username', 'password');
        
        // First attempt web guard authentication
        if (Auth::guard('web')->attempt($credentials)) {
            Log::info('Web authentication successful');
            $user = Auth::guard('web')->user();
            // No usar load, se asume que la relación 'role' está disponible o es null
            Log::info('User authenticated successfully', [
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $user->role ? $user->role->name : 'No role',
                'is_admin' => (isset($user->role) && $user->role->name === 'Admin'),
                'auth_check' => Auth::guard('web')->check(),
                'session_id' => session()->getId()
            ]);
            // Generate JWT token for API usage
            try {
                $token = JWTAuth::fromUser($user);
                session(['jwt_token' => $token]);
                Log::info('JWT token generated and stored in session');
            } catch (JWTException $e) {
                Log::warning('Failed to generate JWT token, but continuing with web auth', [
                    'error' => $e->getMessage()
                ]);
            }
            // Store user data in session
            session([
                'user_data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $user->role->name ?? null,
                ]
            ]);
            // Force session to be saved
            session()->save();
            Log::info('Session data saved', [
                'session_id' => session()->getId(),
                'auth_check_after' => Auth::guard('web')->check()
            ]);
            if (isset($user->role_id) && $user->role_id == 1) {
                Log::info('Redirecting admin to dashboard-admin');
                return redirect()->intended(route('dashboard-admin'));
            } else {
                Log::info('Redirecting user to regular dashboard');
                return redirect()->intended(route('dashboard'));
            }
        }

        // If web authentication fails, try JWT
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                Log::info('All authentication attempts failed');
                return back()->with('error', 'Credenciales incorrectas. Verifique su usuario y contraseña.')->withInput();
            }
        } catch (JWTException $e) {
            Log::error('JWT authentication error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error interno del servidor. Intente nuevamente.')->withInput();
        }
        // If we get here, JWT auth succeeded but web auth failed (shouldn't happen)
        Log::warning('Unusual state: JWT auth succeeded but web auth failed');
        return back()->with('error', 'Error de autenticación. Intente nuevamente.')->withInput();
    }

    /** 
     * Handle web-based login with access token
     */
    public function loginWithToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string',
        ], [
            'access_token.required' => 'El token de acceso es requerido',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('access_token', $request->access_token)->first();

        if (!$user) {
            return back()->with('error', 'Token de acceso inválido o expirado')->withInput();
        }

        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return back()->with('error', 'Error interno del servidor. Intente nuevamente.')->withInput();
        }

        // Login the user
        Auth::login($user);
        
        // Store token in session for web usage
        session(['jwt_token' => $token]);
        session(['user_data' => [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'role' => $user->role->name ?? null,
        ]]);

        return redirect()->route('dashboard')->with('success', '¡Bienvenido, ' . $user->name . '!');
    }

    /**
     * Handle token request
     */
    public function requestToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'duration' => 'required|integer|min:1|max:1440',
        ], [
            'username.required' => 'El nombre de usuario es requerido',
            'duration.required' => 'La duración es requerida',
            'duration.integer' => 'La duración debe ser un número entero',
            'duration.min' => 'La duración debe ser al menos 1 minuto',
            'duration.max' => 'La duración no puede exceder 24 horas (1440 minutos)',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->with('error', 'Usuario no encontrado')->withInput();
        }

        // Generate a new access token
        $accessToken = bin2hex(random_bytes(32));
        
        // Update user's access token
        $user->update(['access_token' => $accessToken]);

        // Create access token record
        \App\Models\AccessToken::create([
            'token' => $accessToken,
            'user_id' => $user->id,
            'duration' => $request->duration,
            'expires_at' => now()->addMinutes($request->duration),
        ]);

        return back()->with('success', 'Token generado exitosamente: ' . $accessToken)
                    ->with('token_data', [
                        'token' => $accessToken,
                        'duration' => $request->duration,
                        'expires_at' => now()->addMinutes($request->duration)->format('d/m/Y H:i:s'),
                        'user' => $user->name,
                    ]);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        try {
            // Get token from session
            $token = session('jwt_token');
            
            if ($token) {
                // Set the token for invalidation
                JWTAuth::setToken($token);
                JWTAuth::invalidate();
            }
        } catch (JWTException $e) {
            // Token might already be invalid, continue with logout
        }

        // Clear session data
        session()->forget(['jwt_token', 'user_data']);
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente');
    }
} 