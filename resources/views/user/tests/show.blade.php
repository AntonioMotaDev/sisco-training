@extends('layouts.app')

@section('content')
<div class="user-layout">
    @include('user.navigation')
    <div class="user-content">
        <!-- Main content -->
        <div class="container-fluid px-4 py-4">
            <div class="content-area p-4">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.courses.show', $course) }}">{{ $course->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.topics.show', [$course, $topic]) }}">{{ $topic->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $test->title }}</li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Test Header -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h2 class="mb-3">{{ $test->title }}</h2>
                                <p class="text-muted mb-3">{{ $test->description }}</p>
                                
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="border-end">
                                            <h5 class="text-primary mb-1">{{ $test->questions->count() }}</h5>
                                            <small class="text-muted">Preguntas</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border-end">
                                            <h5 class="text-success mb-1">70%</h5>
                                            <small class="text-muted">Mín. para aprobar</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border-end">
                                            <h5 class="text-info mb-1">{{ $userAttempts->count() }}</h5>
                                            <small class="text-muted">Intentos realizados</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <h5 class="text-warning mb-1">
                                            @if($userAttempts->where('passed', true)->count() > 0)
                                                <i class="fas fa-check-circle text-success"></i>
                                            @else
                                                <i class="fas fa-clock"></i>
                                            @endif
                                        </h5>
                                        <small class="text-muted">Estado</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Historial de intentos -->
                        @if($userAttempts->count() > 0)
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-0">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-history me-2 text-info"></i>
                                        Historial de Intentos
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @foreach($userAttempts->take(5) as $attempt)
                                        <div class="d-flex justify-content-between align-items-center border-bottom py-2 {{ $loop->last ? 'border-0' : '' }}">
                                            <div>
                                                <span class="badge 
                                                    @if($attempt->passed) bg-success
                                                    @else bg-danger
                                                    @endif me-2">
                                                    @if($attempt->passed)
                                                        <i class="fas fa-check"></i> Aprobado
                                                    @else
                                                        <i class="fas fa-times"></i> Reprobado
                                                    @endif
                                                </span>
                                                <strong>{{ number_format($attempt->score, 1) }}%</strong>
                                            </div>
                                            <small class="text-muted">{{ $attempt->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    @endforeach
                                    
                                    @if($userAttempts->where('passed', true)->count() > 0)
                                        <div class="alert alert-success mt-3">
                                            <i class="fas fa-trophy me-1"></i>
                                            ¡Felicitaciones! Ya has aprobado este test.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Test Form -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-clipboard-list me-2 text-primary"></i>
                                    @if($userAttempts->where('passed', true)->count() > 0)
                                        Revisar Test (Ya Aprobado)
                                    @else
                                        Realizar Test
                                    @endif
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('user.tests.submit', [$course, $topic, $test]) }}" id="testForm">
                                    @csrf
                                    @foreach($test->questions as $index => $question)
                                        <div class="question-item mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                                            <h6 class="mb-3">
                                                <span class="badge bg-light text-dark me-2">{{ $index + 1 }}</span>
                                                {{ $question->question_text }}
                                            </h6>
                                            
                                            <div class="options">
                                                @foreach($question->answers as $answer)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" 
                                                               type="radio" 
                                                               name="answers[{{ $question->id }}]" 
                                                               value="{{ $answer->id }}"
                                                               id="answer_{{ $answer->id }}"
                                                               required>
                                                        <label class="form-check-label" for="answer_{{ $answer->id }}">
                                                            {{ $answer->answer_text }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="d-flex justify-content-between align-items-center mt-4">
                                        <a href="{{ route('user.topics.show', [$course, $topic]) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>
                                            Volver al Topic
                                        </a>
                                        
                                        <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                            <i class="fas fa-paper-plane me-1"></i>
                                            Enviar Respuestas
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Instrucciones -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h6 class="card-title mb-0 text-white">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Instrucciones
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Selecciona una respuesta por pregunta
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Necesitas 70% o más para aprobar
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Puedes intentar múltiples veces
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Revisa tus respuestas antes de enviar
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Progress en el curso -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-chart-line me-2 text-success"></i>
                                    Progreso del Curso
                                </h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $progress = $user->getCourseProgress($course->id);
                                @endphp
                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar bg-success" 
                                         role="progressbar" 
                                         style="width: {{ $progress['progress_percentage'] }}%">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">{{ $progress['passed_tests'] }}/{{ $progress['total_tests'] }} tests</small>
                                    <small class="text-muted">{{ number_format($progress['progress_percentage'], 1) }}%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('testForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Validar que todas las preguntas tengan respuesta antes de enviar
    form.addEventListener('submit', function(e) {
        const questions = document.querySelectorAll('.question-item');
        let allAnswered = true;
        
        questions.forEach(function(question) {
            const radios = question.querySelectorAll('input[type="radio"]');
            const isAnswered = Array.from(radios).some(radio => radio.checked);
            
            if (!isAnswered) {
                allAnswered = false;
                question.classList.add('border', 'border-warning', 'rounded', 'p-3');
            } else {
                question.classList.remove('border', 'border-warning', 'rounded', 'p-3');
            }
        });
        
        if (!allAnswered) {
            e.preventDefault();
            alert('Por favor responde todas las preguntas antes de enviar.');
            return false;
        }
        
        // Confirmar envío
        if (!confirm('¿Estás seguro de enviar tus respuestas? Una vez enviadas no podrás cambiarlas.')) {
            e.preventDefault();
            return false;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Enviando...';
    });
});
</script>

<style>
.question-item {
    transition: all 0.3s ease-in-out;
}

.form-check-input:checked + .form-check-label {
    font-weight: 500;
    color: #0d6efd;
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 10px;
}

.border-warning {
    border-color: #ffc107 !important;
}
</style>
@endsection