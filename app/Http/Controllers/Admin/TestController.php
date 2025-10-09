<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Topic;
use App\Models\Question;
use App\Models\Answer;

class TestController extends Controller
{
    // Mostrar formulario de creación de test para un topic
    public function create($topicId)
    {
        $topic = Topic::findOrFail($topicId);
        return view('admin.tests.create', compact('topic'));
    }

    // Guardar el test y sus preguntas/respuestas
    public function store(Request $request, $topicId)
    {
        $topic = Topic::findOrFail($topicId);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'minimum_approved_grade' => 'required|numeric|min:0',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string',
            'questions.*.score_value' => 'required|numeric|min:0',
        ]);

        $test = Test::create([
            'topic_id' => $topic->id,
            'name' => $request->name,
            'description' => $request->description,
            'minimum_approved_grade' => $request->minimum_approved_grade,
        ]);

        foreach ($request->questions as $qIdx => $qData) {
            $question = $test->questions()->create([
                'question_text' => $qData['text'],
                'type' => $qData['type'],
                'score_value' => $qData['score_value'],
            ]);
            // Solo crear respuestas si NO es free_text
            if ($qData['type'] !== 'free_text' && isset($qData['answers']) && is_array($qData['answers'])) {
                if ($qData['type'] === 'single_choice' && isset($qData['correct'])) {
                    // Opción única: solo una respuesta correcta
                    $correctIdx = $qData['correct'];
                    foreach ($qData['answers'] as $aIdx => $aData) {
                        $question->answers()->create([
                            'answer_text' => $aData['text'],
                            'is_correct' => ($aIdx == $correctIdx) ? 1 : 0,
                        ]);
                    }
                } else {
                    // Opción múltiple: puede haber varias correctas
                    foreach ($qData['answers'] as $aIdx => $aData) {
                        $question->answers()->create([
                            'answer_text' => $aData['text'],
                            'is_correct' => isset($aData['is_correct']) ? 1 : 0,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.courses.show', $topic->courses->first()->id ?? 1)
            ->with('success', 'Cuestionario creado correctamente.');
    }
}
