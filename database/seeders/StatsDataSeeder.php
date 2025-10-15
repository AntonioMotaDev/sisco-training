<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Video;
use App\Models\User;
use App\Models\Attempt;

class StatsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar y crear tests si no existen
        $topics = Topic::all();
        
        foreach ($topics as $topic) {
            if ($topic->tests()->count() === 0) {
                // Crear test para el tema
                $test = Test::create([
                    'topic_id' => $topic->id,
                    'name' => "Evaluación de {$topic->name}",
                    'description' => "Test de conocimientos del tema {$topic->name}",
                    'minimum_approved_grade' => 70.00
                ]);
                
                // Crear preguntas para el test
                for ($j = 1; $j <= 5; $j++) {
                    $question = Question::create([
                        'test_id' => $test->id,
                        'question_text' => "¿Pregunta {$j} sobre {$topic->name}?",
                        'type' => 'multiple_choice',
                        'score_value' => 20.0
                    ]);
                    
                    // Crear respuestas para la pregunta
                    for ($k = 1; $k <= 4; $k++) {
                        Answer::create([
                            'question_id' => $question->id,
                            'answer_text' => "Respuesta {$k} para pregunta {$j}",
                            'is_correct' => $k === 1 // La primera respuesta es correcta
                        ]);
                    }
                }
            }
        }

        // Crear intentos de test
        $tests = Test::all();
        $users = User::where('role_id', '!=', 1)->get(); // Usuarios no admin
        
        if ($tests->count() > 0 && $users->count() > 0) {
            foreach ($users as $user) {
                // Cada usuario intenta algunos tests
                $userTests = $tests->random(min(rand(2, 5), $tests->count()));
                
                foreach ($userTests as $test) {
                    // Crear múltiples intentos por test
                    for ($i = 0; $i < rand(1, 3); $i++) {
                        $score = rand(0, 100);
                        $passed = $score >= $test->minimum_approved_grade;
                        
                        Attempt::create([
                            'user_id' => $user->id,
                            'test_id' => $test->id,
                            'score' => $score,
                            'passed' => $passed,
                            'attempt_date' => now()->subDays(rand(1, 90)),
                            'created_at' => now()->subDays(rand(1, 90))
                        ]);
                    }
                }
            }
        }

        $this->command->info('Datos de estadísticas creados correctamente.');
        $this->command->info('Tests: ' . Test::count());
        $this->command->info('Intentos: ' . Attempt::count());
    }
}
