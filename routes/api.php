<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public authentication routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('login-token', [AuthController::class, 'loginWithToken']);
    Route::post('request-token', [AuthController::class, 'requestToken']);
    Route::post('register', [AuthController::class, 'register']);
});

// Protected authentication routes
Route::group(['prefix' => 'auth', 'middleware' => 'auth:api'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('profile', [AuthController::class, 'profile']);
});

// Protected routes by role
Route::group(['middleware' => ['auth:api', 'role:Admin']], function () {
    // Admin only routes
    Route::get('admin/users', function () {
        return response()->json(['message' => 'Admin users endpoint']);
    });
    
    // Statistics API routes
    Route::prefix('admin/stats')->group(function () {
        Route::get('realtime', [\App\Http\Controllers\Api\StatsController::class, 'getRealtimeStats']);
        Route::get('monthly', [\App\Http\Controllers\Api\StatsController::class, 'getMonthlyData']);
        Route::get('performance', [\App\Http\Controllers\Api\StatsController::class, 'getPerformanceMetrics']);
    });
});

Route::group(['middleware' => ['auth:api', 'role:Técnico']], function () {
    // Technician only routes
    Route::get('technician/courses', function () {
        return response()->json(['message' => 'Technician courses endpoint']);
    });
});

Route::group(['middleware' => ['auth:api', 'role:Cliente']], function () {
    // Client only routes
    Route::get('client/courses', function () {
        return response()->json(['message' => 'Client courses endpoint']);
    });
}); 