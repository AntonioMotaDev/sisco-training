<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use Carbon\Carbon;

class CourseUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios técnicos y clientes (no admins)
        $users = User::where('role_id', '!=', 1)->get();
        
        // Obtener cursos disponibles
        $courses = Course::all();
        
        if ($users->isEmpty() || $courses->isEmpty()) {
            $this->command->info('No hay usuarios o cursos disponibles para inscribir.');
            return;
        }

        // Inscribir algunos usuarios en cursos aleatorios
        foreach ($users as $user) {
            // Inscribir cada usuario en 1-3 cursos aleatorios
            $randomCourses = $courses->random(rand(1, min(3, $courses->count())));
            
            foreach ($randomCourses as $course) {
                // Verificar si ya está inscrito
                if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
                    $enrolledAt = Carbon::now()->subDays(rand(1, 30));
                    
                    // Determinar progreso aleatorio
                    $progressPercentage = rand(0, 100);
                    $status = $progressPercentage >= 100 ? 'completed' : 'active';
                    $completedAt = $status === 'completed' ? $enrolledAt->copy()->addDays(rand(1, 20)) : null;
                    
                    $user->enrolledCourses()->attach($course->id, [
                        'enrolled_at' => $enrolledAt,
                        'completed_at' => $completedAt,
                        'progress_percentage' => $progressPercentage,
                        'status' => $status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('Inscripciones de prueba creadas exitosamente.');
    }
}
