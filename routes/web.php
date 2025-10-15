<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\UserController;
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
        Route::get('/stats', [CourseController::class, 'statsDashboard'])->name('stats');
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
    });

    // Topic management routes
    Route::prefix('topics')->name('topics.')->group(function () {
        Route::get('/', [TopicController::class, 'index'])->name('index');
        Route::get('/create', [TopicController::class, 'create'])->name('create');
        Route::post('/', [TopicController::class, 'store'])->name('store');
        Route::get('/{topic}', [TopicController::class, 'show'])->name('show');
        Route::get('/{topic}/edit', [TopicController::class, 'edit'])->name('edit');
        Route::put('/{topic}', [TopicController::class, 'update'])->name('update');
        Route::delete('/{topic}', [TopicController::class, 'destroy'])->name('destroy');
        Route::patch('/{topic}/toggle-approval', [TopicController::class, 'toggleApproval'])->name('toggle-approval');
        Route::get('/api/select-options', [TopicController::class, 'getTopicsForSelect'])->name('select-options');
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
        Route::get('/dashboard', [TestController::class, 'dashboard'])->name('dashboard');
        Route::get('/topic/{topic}', [TestController::class, 'index'])->name('index');
        Route::get('/create/{topic}', [TestController::class, 'create'])->name('create');
        Route::post('/store/{topic}', [TestController::class, 'store'])->name('store');
        Route::get('/{test}', [TestController::class, 'show'])->name('show');
        Route::get('/{test}/edit', [TestController::class, 'edit'])->name('edit');
        Route::put('/{test}', [TestController::class, 'update'])->name('update');
        Route::delete('/{test}', [TestController::class, 'destroy'])->name('destroy');
    });

    // User management routes (Admin)
    Route::prefix('admin/users')->name('admin.users.')->middleware('auth')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/renew-token', [UserController::class, 'renewToken'])->name('renew-token');
        Route::get('/course/{course}/users', [UserController::class, 'courseUsers'])->name('course-users');
    });

    // YouTube API routes
    Route::prefix('youtube')->name('youtube.')->group(function () {
        Route::post('/video-info', [YouTubeController::class, 'getVideoInfo'])->name('video.info');
    });

});
    