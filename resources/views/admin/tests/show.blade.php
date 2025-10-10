@extends('layouts.app')

@section('title', 'Detalle del Cuestionario - SISCO Training')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">
        <div class="container-fluid px-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Detalle del cuestionario</h1>
                    <p class="text-muted mb-0">Tema: <span class="fw-bold">{{ $topic->name }}</span></p>
                </div>
                <a href="{{ route('admin.tests.index', $topic->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver a la lista
                </a>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-2 text-olive">{{ $test->name }}</h4>
                    <p class="mb-1">{{ $test->description }}</p>
                    <p class="mb-1 text-end"><strong >Calificación mínima:</strong> {{ $test->minimum_approved_grade }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Preguntas</h5>
                    @if($test->questions->isEmpty())
                        <div class="alert alert-info">No hay preguntas registradas para este cuestionario.</div>
                    @else
                        <ol>
                        @foreach($test->questions as $question)
                            <li class="mb-3">
                                <div><strong>{{ $question->question_text }}</strong> <span class="badge bg-secondary">{{ $question->type }}</span> <span class="badge bg-info">Valor: {{ $question->score_value }}</span></div>
                                @if($question->type !== 'free_text')
                                    <ul>
                                    @foreach($question->answers as $answer)
                                        <li>
                                            {{ $answer->answer_text }}
                                            @if($answer->is_correct)
                                                <span class="badge bg-success">Correcta</span>
                                            @endif
                                        </li>
                                    @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                        </ol>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
