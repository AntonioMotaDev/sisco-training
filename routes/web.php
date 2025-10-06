<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;


// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login POST routes (no guest middleware)
Route::post('/login', [WebAuthController::class, 'login'])->name('login.submit');
Route::post('/login-token', [WebAuthController::class, 'loginWithToken'])->name('login.token.submit');
Route::post('/request-token', [WebAuthController::class, 'requestToken'])->name('request.token.submit');

// Authentication routes that should only be accessible to guests
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');
    Route::get('/login-token', [WebAuthController::class, 'showTokenLoginForm'])->name('login.token');
    Route::get('/request-token', [WebAuthController::class, 'showRequestTokenForm'])->name('request.token');
});

// Protected routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [WebAuthController::class, 'showDashboard'])->name('dashboard');
    Route::get('/dashboard-admin', [WebAuthController::class, 'showDashboardAdmin'])->name('dashboard-admin');
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');   
    
    // Profile routes
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // Course management routes
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/dashboard', [CourseController::class, 'dashboard'])->name('dashboard');
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::get('/create', [CourseController::class, 'create'])->name('create');
        Route::post('/store', [CourseController::class, 'store'])->name('store');
        
        // Videos management
        Route::get('/videos', [CourseController::class, 'videosDashboard'])->name('videos.dashboard');
        
        // Quizzes management
        Route::get('/quizzes', [CourseController::class, 'quizzesDashboard'])->name('quizzes.dashboard');
        Route::get('/quizzes/create', [CourseController::class, 'createQuiz'])->name('quizzes.create');
        
        // Users management
        Route::get('/users', [CourseController::class, 'usersManagement'])->name('users.index');

        // Stats management
        Route::get('/stats', [CourseController::class, 'statsDashboard'])->name('stats.dashboard');
    });
});
    