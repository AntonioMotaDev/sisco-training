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
    // Listar cuestionarios de un tema
    public function index($topicId)
    {
        $topic = Topic::findOrFail($topicId);
        $tests = $topic->tests()->get();
        return view('admin.tests.index', compact('topic', 'tests'));
    }

    // Mostrar detalle de un cuestionario
    public function show($testId)
    {
        $test = Test::with(['questions.answers', 'topic'])->findOrFail($testId);
        $topic = $test->topic;
        return view('admin.tests.show', compact('test', 'topic'));
    }

    // Mostrar formulario de edición de un cuestionario
    public function edit($testId)
    {
        $test = Test::with(['topic', 'questions.answers'])->findOrFail($testId);
        $topic = $test->topic;
        return view('admin.tests.edit', compact('test', 'topic'));
    }

    // Actualizar un cuestionario
    public function update(Request $request, $testId)
    {
        $test = Test::findOrFail($testId);
        $topic = $test->topic;
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'minimum_approved_grade' => 'required|numeric|min:0',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string',
            'questions.*.score_value' => 'required|numeric|min:0',
        ]);

        // Actualizar datos básicos del cuestionario
        $test->update([
            'name' => $request->name,
            'description' => $request->description,
            'minimum_approved_grade' => $request->minimum_approved_grade,
        ]);

        // Eliminar todas las preguntas existentes y sus respuestas
        $test->questions()->each(function($question) {
            $question->answers()->delete();
            $question->delete();
        });

        // Crear las nuevas preguntas y respuestas
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

        return redirect()->route('admin.tests.index', $topic->id)
            ->with('success', 'Cuestionario actualizado correctamente.');
    }
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
            'questions' => 'required|array|min:1', // Asegurarse de que haya al menos una pregunta
            'questions.*.text' => 'required|string', // Validar que cada pregunta tenga texto
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
