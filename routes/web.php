<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\YouTubeController;


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
    Route::prefix('admin/courses')->name('admin.courses.')->group(function () {
        Route::get('/dashboard', [CourseController::class, 'dashboard'])->name('dashboard');
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::get('/create', [CourseController::class, 'create'])->name('create');
        Route::post('/store', [CourseController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CourseController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CourseController::class, 'update'])->name('update');
        Route::delete('/{id}', [CourseController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [CourseController::class, 'show'])->name('show');
        
        // Multi-step course creation
        Route::prefix('create')->name('create.')->group(function () {
            Route::post('/step1', [CourseController::class, 'storeStep1'])->name('step1.store');
            Route::get('/step2', [CourseController::class, 'createStep2'])->name('step2');
            Route::post('/step2', [CourseController::class, 'storeStep2'])->name('step2.store');
            Route::get('/step3', [CourseController::class, 'createStep3'])->name('step3');
            Route::post('/finish', [CourseController::class, 'finishCreation'])->name('finish');
        });
        
        // Quizzes management
        Route::get('/quizzes', [CourseController::class, 'quizzesDashboard'])->name('quizzes.dashboard');
        Route::get('/quizzes/create', [CourseController::class, 'createQuiz'])->name('quizzes.create');
        
        // Users management
        Route::get('/users', [CourseController::class, 'usersManagement'])->name('users.index');

        // Stats management
        Route::get('/stats', [CourseController::class, 'statsDashboard'])->name('stats.dashboard');
    });

    // Topic management routes
    Route::prefix('topics')->name('topics.')->group(function () {
        Route::get('/', [CourseController::class, 'topics'])->name('index');
        Route::get('/create', [CourseController::class, 'createTopic'])->name('create');
    });

    // Video management routes
    Route::prefix('videos')->name('videos.')->group(function () {
        Route::get('/', [VideoController::class, 'index'])->name('index');
        Route::get('/create', [VideoController::class, 'create'])->name('create');
        Route::post('/store', [VideoController::class, 'store'])->name('store');
        Route::get('/{id}', [VideoController::class, 'show'])->name('show');
        Route::get('videos/course/{courseId}', [VideoController::class, 'videosByCourse'])->name('byCourse');
        Route::get('/{id}/edit', [VideoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VideoController::class, 'update'])->name('update');
        Route::delete('/{id}', [VideoController::class, 'destroy'])->name('destroy');
    });

    // Test management routes
    Route::prefix('admin/tests')->name('admin.tests.')->middleware('auth')->group(function () {
    Route::get('/create/{topic}', [\App\Http\Controllers\Admin\TestController::class, 'create'])->name('create');
    Route::post('/store/{topic}', [\App\Http\Controllers\Admin\TestController::class, 'store'])->name('store');

    // Listar cuestionarios de un tema
    Route::get('/topic/{topic}', [\App\Http\Controllers\Admin\TestController::class, 'index'])->name('index');
    // Ver un cuestionario
    Route::get('/{test}', [\App\Http\Controllers\Admin\TestController::class, 'show'])->name('show');
    // Editar un cuestionario
    Route::get('/{test}/edit', [\App\Http\Controllers\Admin\TestController::class, 'edit'])->name('edit');
    // Actualizar un cuestionario
    Route::put('/{test}', [\App\Http\Controllers\Admin\TestController::class, 'update'])->name('update');
    });

    // YouTube API routes
    Route::prefix('youtube')->name('youtube.')->group(function () {
        Route::post('/video-info', [YouTubeController::class, 'getVideoInfo'])->name('video.info');
    });

});
    