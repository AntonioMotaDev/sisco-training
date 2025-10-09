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
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string',
        ]);

        $test = Test::create([
            'topic_id' => $topic->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        foreach ($request->questions as $qData) {
            $question = $test->questions()->create([
                'text' => $qData['text'],
                'type' => $qData['type'],
            ]);
            if (isset($qData['answers']) && is_array($qData['answers'])) {
                foreach ($qData['answers'] as $aData) {
                    $question->answers()->create([
                        'text' => $aData['text'],
                        'is_correct' => isset($aData['is_correct']) ? 1 : 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.courses.show', $topic->courses->first()->id ?? 1)
            ->with('success', 'Cuestionario creado correctamente.');
    }
}
