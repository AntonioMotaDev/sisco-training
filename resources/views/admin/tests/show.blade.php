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

            <!-- Detalles del cuestionario -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-2 text-primary-blue">{{ $test->name }}</h4>
                    <p class="mb-1">{{ $test->description }}</p>
                    <p class="mb-1 text-end"><strong >Calificación mínima:</strong> {{ $test->minimum_approved_grade }}</p>
                </div>
            </div>

            <!-- Preguntas --> 
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3 text-olive">Preguntas</h5>
                    @if($test->questions->isEmpty())
                        <div class="alert alert-info">No hay preguntas registradas para este cuestionario.</div>
                    @else
                        <ol>
                        @foreach($test->questions as $question)
                            <li class="mb-3">
                                <div>
                                    <strong>{{ $question->question_text }}</strong>
                                    <br> 
                                    <span class="text-primary-blue">Valor: {{ $question->score_value }}</span>
                                </div>
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

            <!-- Botones de accion -->
            <div class="d-flex justify-content-end align-items-center mt-4">
                <a href="{{ route('admin.tests.edit', [$test->id]) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Editar Cuestionario
                </a>
                <form action="{{ route('admin.tests.destroy', [$test->id]) }}" method="POST" class="ms-2" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cuestionario? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-1"></i> Eliminar Cuestionario
                    </button>
                </form>
            </div>

            <!-- estadisticas de respuestas de los usuarios -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="mb-3 text-olive">Estadísticas de Respuestas</h5>
                    @if(!$test->userTest || $test->userTests->isEmpty())
                        <div class="alert alert-info">No hay respuestas de usuarios para este cuestionario.</div>
                    @else
                        <table class="table table-bordered"></table>
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Calificación</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($test->userTests as $userTest)
                                    <tr>
                                        <td>{{ $userTest->user->name }}</td>
                                        <td>{{ $userTest->score }}</td>
                                        <td>{{ $userTest->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>


        </div>
    </div>
</div>
@endsection
