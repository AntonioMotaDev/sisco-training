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
                        <li class="breadcrumb-item active" aria-current="page">{{ $course->name }}</li>
                    </ol>
                </nav>

                <!-- Header del curso -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <h1 class="h3 mb-3">{{ $course->name }}</h1>
                        <p class="text-muted">{{ $course->description }}</p>
                        
                        <!-- Progreso general -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Progreso del Curso</h6>
                                    <span class="badge 
                                        @if($progress['progress_percentage'] >= 100) bg-success
                                        @elseif($progress['progress_percentage'] >= 70) bg-info
                                        @else bg-warning
                                        @endif">
                                        {{ number_format($progress['progress_percentage'], 1) }}%
                                    </span>
                                </div>
                                <div class="progress mb-2" style="height: 12px;">
                                    <div class="progress-bar 
                                        @if($progress['progress_percentage'] >= 100) bg-success
                                        @elseif($progress['progress_percentage'] >= 70) bg-info
                                        @else bg-warning
                                        @endif"
                                        role="progressbar" 
                                        style="width: {{ $progress['progress_percentage'] }}%"
                                        aria-valuenow="{{ $progress['progress_percentage'] }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Tests Totales</small>
                                        <strong>{{ $progress['total_tests'] }}</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Tests Aprobados</small>
                                        <strong class="text-success">{{ $progress['passed_tests'] }}</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Pendientes</small>
                                        <strong class="text-warning">{{ $progress['total_tests'] - $progress['passed_tests'] }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Información del Curso</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Topics:</span>
                                    <strong>{{ $course->topicsOrdered->count() }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Videos:</span>
                                    <strong>{{ $course->topicsOrdered->sum(fn($topic) => $topic->videos->count()) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tests:</span>
                                    <strong>{{ $course->topicsOrdered->sum(fn($topic) => $topic->tests->count()) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Duración estimada:</span>
                                    <strong>{{ $course->getDuration() }} min</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Topics del curso -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list-ul me-2 text-primary"></i>
                            Contenido del Curso
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($course->topicsOrdered->count() > 0)
                            @foreach($course->topicsOrdered as $index => $topic)
                                <div class="topic-item border-bottom p-4 {{ $loop->last ? 'border-0' : '' }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="mb-2">
                                                <span class="badge bg-light text-dark me-2">{{ $index + 1 }}</span>
                                                {{ $topic->name }}
                                            </h6>
                                            <p class="text-muted mb-2">{{ $topic->description }}</p>
                                            
                                            <!-- Información del topic -->
                                            <div class="d-flex gap-3 small text-muted">
                                                @if($topic->videos->count() > 0)
                                                    <span>
                                                        <i class="fas fa-video me-1"></i>
                                                        {{ $topic->videos->count() }} video{{ $topic->videos->count() > 1 ? 's' : '' }}
                                                    </span>
                                                @endif
                                                @if($topic->tests->count() > 0)
                                                    <span>
                                                        <i class="fas fa-clipboard-list me-1"></i>
                                                        {{ $topic->tests->count() }} test{{ $topic->tests->count() > 1 ? 's' : '' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <!-- Estado de tests del topic -->
                                            {{-- @php
                                                $topicTests = $topic->tests;
                                                $passedTestsInTopic = $topicTests->filter(function($test) use ($userAttempts) {
                                                    return isset($userAttempts[$test->id]) && $userAttempts[$test->id]->passed;
                                                })->count();
                                                $totalTestsInTopic = $topicTests->count();
                                            @endphp
                                            
                                            @if($totalTestsInTopic > 0)
                                                <div class="mb-2">
                                                    <small class="text-muted">Tests: {{ $passedTestsInTopic }}/{{ $totalTestsInTopic }}</small>
                                                </div>
                                                @if($passedTestsInTopic === $totalTestsInTopic && $totalTestsInTopic > 0)
                                                    <span class="badge bg-success mb-2">
                                                        <i class="fas fa-check"></i> Completado
                                                    </span>
                                                @elseif($passedTestsInTopic > 0)
                                                    <span class="badge bg-warning mb-2">
                                                        <i class="fas fa-clock"></i> En progreso
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary mb-2">
                                                        <i class="fas fa-play"></i> Pendiente
                                                    </span>
                                                @endif
                                                <br>
                                            @endif --}}
                                            
                                            <a href="{{ route('user.topics.show', [$course, $topic]) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-arrow-right me-1"></i>
                                                Ver Topic
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <div class="text-muted mb-3">
                                    <i class="fas fa-folder-open fa-3x"></i>
                                </div>
                                <h5 class="text-muted">Este curso no tiene contenido disponible</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.topic-item {
    transition: background-color 0.2s ease-in-out;
}

.topic-item:hover {
    background-color: #f8f9fa;
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 10px;
}
</style>
@endsection