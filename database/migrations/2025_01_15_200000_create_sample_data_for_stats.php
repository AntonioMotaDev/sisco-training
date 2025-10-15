<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Video;
use App\Models\User;
use App\Models\Attempt;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Este archivo contiene datos de prueba para el dashboard de estadísticas
        // Solo crear datos si no existen suficientes
        
        if (Course::count() < 3) {
            $this->createSampleCourses();
        }
        
        if (User::count() < 10) {
            $this->createSampleUsers();
        }
        
        if (Attempt::count() < 20) {
            $this->createSampleAttempts();
        }
    }

    private function createSampleCourses()
    {
        $courses = [
            [
                'name' => 'Curso de Seguridad Industrial',
                'description' => 'Aprende las bases de la seguridad en el trabajo',
                'is_active' => true
            ],
            [
                'name' => 'Primeros Auxilios Básicos',
                'description' => 'Conocimientos esenciales de primeros auxilios',
                'is_active' => true
            ],
            [
                'name' => 'Prevención de Riesgos Laborales',
                'description' => 'Identificación y prevención de riesgos en el trabajo',
                'is_active' => true
            ]
        ];

        foreach ($courses as $courseData) {
            $course = Course::create($courseData);
            
            // Crear temas para cada curso
            for ($i = 1; $i <= 3; $i++) {
                $topic = Topic::create([
                    'name' => "Tema {$i} del {$course->name}",
                    'description' => "Descripción del tema {$i}",
                    'is_approved' => true,
                    'code' => strtoupper(substr($course->name, 0, 3)) . $course->id . $i
                ]);
                
                // Asociar tema con curso
                $course->topics()->attach($topic->id, ['order_in_course' => $i]);
                
                // Crear videos para el tema
                Video::create([
                    'topic_id' => $topic->id,
                    'name' => "Video del {$topic->name}",
                    'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'code' => strtoupper(substr($course->name, 0, 3)) . $course->id . "V{$i}",
                    'length_seconds' => rand(300, 1800)
                ]);
                
                // Crear test para el tema
                $test = Test::create([
                    'topic_id' => $topic->id,
                    'name' => "Evaluación del {$topic->name}",
                    'description' => "Test de conocimientos del tema",
                    'minimum_approved_grade' => 70.00
                ]);
                
                // Crear preguntas para el test
                for ($j = 1; $j <= 5; $j++) {
                    $question = Question::create([
                        'test_id' => $test->id,
                        'question' => "¿Pregunta {$j} sobre {$topic->name}?",
                        'order' => $j
                    ]);
                    
                    // Crear respuestas para la pregunta
                    for ($k = 1; $k <= 4; $k++) {
                        Answer::create([
                            'question_id' => $question->id,
                            'answer' => "Respuesta {$k}",
                            'is_correct' => $k === 1 // La primera respuesta es correcta
                        ]);
                    }
                }
            }
        }
    }

    private function createSampleUsers()
    {
        $users = [
            ['name' => 'Juan Pérez', 'email' => 'juan@example.com', 'role_id' => 2],
            ['name' => 'María García', 'email' => 'maria@example.com', 'role_id' => 2],
            ['name' => 'Carlos López', 'email' => 'carlos@example.com', 'role_id' => 3],
            ['name' => 'Ana Martínez', 'email' => 'ana@example.com', 'role_id' => 3],
            ['name' => 'Pedro Rodríguez', 'email' => 'pedro@example.com', 'role_id' => 3],
            ['name' => 'Laura Sánchez', 'email' => 'laura@example.com', 'role_id' => 3],
            ['name' => 'Miguel Torres', 'email' => 'miguel@example.com', 'role_id' => 3],
            ['name' => 'Carmen Ruiz', 'email' => 'carmen@example.com', 'role_id' => 3],
            ['name' => 'José Hernández', 'email' => 'jose@example.com', 'role_id' => 3],
            ['name' => 'Lucía Jiménez', 'email' => 'lucia@example.com', 'role_id' => 3],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'username' => strtolower(str_replace(' ', '.', $userData['name'])),
                'password' => bcrypt('password'),
                'role_id' => $userData['role_id'],
                'created_at' => now()->subDays(rand(1, 90))
            ]);
        }
    }

    private function createSampleAttempts()
    {
        $tests = Test::all();
        $users = User::where('role_id', '!=', 1)->get(); // Usuarios no admin
        
        if ($tests->count() === 0 || $users->count() === 0) {
            return; // No hay tests o usuarios, salir silenciosamente
        }
        
        foreach ($users as $user) {
            $testsToAttempt = $tests->random(min(rand(1, 3), $tests->count()));
            foreach ($testsToAttempt as $test) {
                // Crear varios intentos por usuario
                for ($i = 0; $i < rand(1, 3); $i++) {
                    $score = rand(0, 100);
                    $passed = $score >= $test->minimum_approved_grade;
                    
                    Attempt::create([
                        'user_id' => $user->id,
                        'test_id' => $test->id,
                        'score' => $score,
                        'passed' => $passed,
                        'attempt_date' => now()->subDays(rand(1, 60)),
                        'created_at' => now()->subDays(rand(1, 60))
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacer nada en down para preservar datos
    }
};