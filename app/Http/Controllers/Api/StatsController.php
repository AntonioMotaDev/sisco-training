<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Test;
use App\Models\Question;
use App\Models\Video;
use App\Models\User;
use App\Models\Attempt;

class StatsController extends Controller
{
    /**
     * Get real-time statistics for the dashboard
     */
    public function getRealtimeStats()
    {
        $user = Auth::user();
        
        // Verificar permisos
        if (!$user || $user->role_id != 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Estadísticas básicas
        $stats = [
            'totalCourses' => Course::count(),
            'activeCourses' => Course::where('is_active', true)->count(),
            'totalTopics' => Topic::count(),
            'totalVideos' => Video::count(),
            'totalTests' => Test::count(),
            'totalQuestions' => Question::count(),
            'totalUsers' => User::count(),
            'totalAttempts' => Attempt::count(),
            'passedAttempts' => Attempt::where('passed', true)->count(),
            'lastUpdate' => now()->format('d/m/Y H:i:s')
        ];

        // Calcular tasa de éxito
        $stats['successRate'] = $stats['totalAttempts'] > 0 ? 
            round(($stats['passedAttempts'] / $stats['totalAttempts']) * 100, 1) : 0;

        // Estadísticas de los últimos 7 días
        $recentAttempts = Attempt::where('created_at', '>=', now()->subDays(7))->count();
        $recentUsers = User::where('created_at', '>=', now()->subDays(7))->count();
        
        $stats['recentActivity'] = [
            'attempts' => $recentAttempts,
            'newUsers' => $recentUsers
        ];

        // Top 5 cursos más activos
        $activeCourses = Course::with('topics')
            ->get()
            ->map(function ($course) {
                $totalAttempts = 0;
                foreach ($course->topics as $topic) {
                    $topicAttempts = Attempt::whereHas('test', function($query) use ($topic) {
                        $query->where('topic_id', $topic->id);
                    })->count();
                    $totalAttempts += $topicAttempts;
                }
                
                return [
                    'name' => $course->name,
                    'attempts' => $totalAttempts
                ];
            })
            ->sortByDesc('attempts')
            ->take(5)
            ->values();

        $stats['topCourses'] = $activeCourses;

        return response()->json($stats);
    }

    /**
     * Get monthly data for charts
     */
    public function getMonthlyData(Request $request)
    {
        $user = Auth::user();
        
        // Verificar permisos
        if (!$user || $user->role_id != 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $months = $request->get('months', 6);

        // Estadísticas por mes
        $monthlyAttempts = Attempt::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths($months))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyUsers = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->where('created_at', '>=', now()->subMonths($months))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Preparar datos para los gráficos
        $chartData = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->format('M Y');
            
            $chartData[] = [
                'month' => $monthLabel,
                'attempts' => $monthlyAttempts[$monthKey] ?? 0,
                'users' => $monthlyUsers[$monthKey] ?? 0
            ];
        }

        return response()->json($chartData);
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics()
    {
        $user = Auth::user();
        
        // Verificar permisos
        if (!$user || $user->role_id != 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Tests más difíciles (últimos 10)
        $difficultTests = Test::with('topic')
            ->get()
            ->map(function($test) {
                $attemptsCount = Attempt::where('test_id', $test->id)->count();
                $passedAttemptsCount = Attempt::where('test_id', $test->id)
                    ->where('passed', true)->count();
                
                $successRate = $attemptsCount > 0 ? 
                    round(($passedAttemptsCount / $attemptsCount) * 100, 2) : 0;
                    
                return [
                    'id' => $test->id,
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
            ->take(10)
            ->values();

        // Top usuarios
        $topUsers = User::withCount(['passedAttempts'])
            ->orderBy('passed_attempts_count', 'desc')
            ->take(10)
            ->get()
            ->map(function($user, $index) {
                return [
                    'name' => $user->name,
                    'passed_attempts' => $user->passed_attempts_count,
                    'rank' => $index + 1
                ];
            });

        return response()->json([
            'difficultTests' => $difficultTests,
            'topUsers' => $topUsers
        ]);
    }
}