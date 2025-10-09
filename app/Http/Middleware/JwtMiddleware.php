<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            if (!$user) {
                return $this->unauthorizedResponse('Usuario no encontrado');
            }
            
        } catch (TokenExpiredException $e) {
            return $this->unauthorizedResponse('Token expirado');
        } catch (TokenInvalidException $e) {
            return $this->unauthorizedResponse('Token inválido');
        } catch (JWTException $e) {
            return $this->unauthorizedResponse('Token no encontrado');
        }

        return $next($request);
    }

    /**
     * Return unauthorized response.
     */
    private function unauthorizedResponse(string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], 401);
    }
} 