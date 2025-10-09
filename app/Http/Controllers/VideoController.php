<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Video;
use App\Models\Topic;

class VideoController extends Controller
{
    public function index()
    {
        // Paginar videos, 6 por página
        $videos = Video::with('topic')->orderByDesc('created_at')->paginate(6);
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        // Obtener todos los topics con sus cursos para el selector
        $topics = Topic::all();
                    
        return view('admin.videos.create', compact('topics'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validar los datos del formulario
        $validated = $request->validate(
            [
            'topic_id' => 'required|exists:topics,id',
            'url' => 'required|string',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:videos,code',
            'length_seconds' => 'nullable|integer|min:0',
        ], [
            'topic_id.required' => 'El tema es obligatorio.',
            'topic_id.exists' => 'El tema seleccionado no es válido.',
            'url.required' => 'La URL del video es obligatoria.',
            'url.url' => 'La URL debe ser válida.',
            'url.regex' => 'La URL debe ser de YouTube (youtube.com o youtu.be).',
            'name.required' => 'El nombre del video es obligatorio.',
            'name.max' => 'El nombre no debe exceder 255 caracteres.',
            'code.required' => 'El código del video es obligatorio.',
            'code.unique' => 'Este código de video ya está en uso.',
            'code.max' => 'El código no debe exceder 255 caracteres.',
            'length_seconds.integer' => 'La duración debe ser un número entero.',
            'length_seconds.min' => 'La duración debe ser mayor o igual a 0.',
        ]);

        try {
            // El código será el video ID de YouTube que viene del formulario
            // No necesitamos generar un código automático
            
            // Crear el video
            $video = Video::create($validated);

            return redirect()
                ->route('videos.index')
                ->with('success', 'Video creado exitosamente: ' . $video->name);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear el video: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Código para mostrar un video específico
    }

    // Muestra todos los videos de un curso específico (usando la relación muchos a muchos)
    public function videosByCourse($courseId)
    {
        $topics = \App\Models\Course::with('topics.videos')->findOrFail($courseId)->topics;
        $videosCollection = $topics->flatMap(fn($topic) => $topic->videos)->sortByDesc('created_at')->values();

        // Paginación manual sobre la colección
        $perPage = 6;
        $page = request()->get('page', 1);
        $total = $videosCollection->count();
        $videos = new \Illuminate\Pagination\LengthAwarePaginator(
            $videosCollection->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.videos.by_course', [
            'videos' => $videos,
            'courseId' => $courseId,
            'topics' => $topics,
        ]);
    }

    public function edit($id)
    {
        // Código para mostrar formulario de edición
    }

    public function update(Request $request, $id)
    {
        // Código para actualizar un video
    }

    public function destroy($id)
    {
        // Código para eliminar un video
    }
}
