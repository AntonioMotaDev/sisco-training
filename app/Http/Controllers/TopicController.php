<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $topics = Topic::withCount(['courses', 'videos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.topics.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:topics,name',
            'description' => 'nullable|string|max:1000',
            'code' => 'nullable|string|max:10|unique:topics,code',
            'is_approved' => 'boolean'
        ]);

        // Generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = $this->generateTopicCode($validated['name']);
        }

        $topic = Topic::create($validated);

        return redirect()
            ->route('topics.index')
            ->with('success', 'Tema creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic): View
    {
        $topic->load(['courses', 'videos', 'tests']);
        
        return view('admin.topics.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic): View
    {
        return view('admin.topics.edit', compact('topic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:topics,name,' . $topic->id,
            'description' => 'nullable|string|max:1000',
            'code' => 'nullable|string|max:10|unique:topics,code,' . $topic->id,
            'is_approved' => 'boolean'
        ]);

        // Generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = $this->generateTopicCode($validated['name']);
        }

        $topic->update($validated);

        return redirect()
            ->route('topics.index')
            ->with('success', 'Tema actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic): RedirectResponse
    {
        // Check if topic has associated courses or videos
        if ($topic->courses()->exists() || $topic->videos()->exists()) {
            return redirect()
                ->route('topics.index')
                ->with('error', 'No se puede eliminar el tema porque tiene cursos o videos asociados.');
        }

        $topic->delete();

        return redirect()
            ->route('topics.index')
            ->with('success', 'Tema eliminado exitosamente.');
    }

    /**
     * Toggle the approval status of a topic.
     */
    public function toggleApproval(Topic $topic): JsonResponse
    {
        $topic->update(['is_approved' => !$topic->is_approved]);

        return response()->json([
            'success' => true,
            'is_approved' => $topic->is_approved,
            'message' => $topic->is_approved ? 'Tema aprobado exitosamente.' : 'Tema desaprobado exitosamente.'
        ]);
    }

    /**
     * Get topics for select dropdown (AJAX).
     */
    public function getTopicsForSelect(): JsonResponse
    {
        $topics = Topic::where('is_approved', true)
            ->select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        return response()->json($topics);
    }

    /**
     * Generate a unique code for the topic based on its name.
     */
    private function generateTopicCode(string $name): string
    {
        // Take first 3 characters of each word, convert to uppercase
        $words = explode(' ', $name);
        $code = '';
        
        foreach ($words as $word) {
            if (strlen($word) >= 3) {
                $code .= strtoupper(substr($word, 0, 3));
            } else {
                $code .= strtoupper($word);
            }
        }

        // Ensure code is max 10 characters
        $code = substr($code, 0, 10);

        // Check if code already exists and make it unique
        $originalCode = $code;
        $counter = 1;
        while (Topic::where('code', $code)->exists()) {
            $code = $originalCode . $counter;
            $counter++;
        }

        return $code;
    }
}