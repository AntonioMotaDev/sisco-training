<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado'
            ], 401)->toResponse($request);
        }

        // Verifica directamente la relación y el nombre del rol
        if (!$user->role || $user->role->name !== $role) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado. Se requiere el rol: ' . $role
            ], 403)->toResponse($request);
        }

        return $next($request);
    }
} 