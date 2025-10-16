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
                        <li class="breadcrumb-item active" aria-current="page">{{ $topic->name }}</li>
                    </ol>
                </nav>

                <!-- Header del topic -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <h1 class="h3 mb-3">{{ $topic->name }}</h1>
                        <p class="text-muted">{{ $topic->description }}</p>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Contenido</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Videos:</span>
                                    <strong>{{ $topic->videos->count() }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tests:</span>
                                    <strong>{{ $topic->tests->count() }}</strong>
                                </div>
                                @if($topic->videos->count() > 0)
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Duración total:</span>
                                    <strong>{{ ceil($topic->videos->sum('length_seconds') / 60) }} min</strong>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Videos -->
                    @if($topic->videos->count() > 0)
                        <div class="col-lg-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-0">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-video me-2 text-primary"></i>
                                        Videos
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @foreach($topic->videos as $video)
                                        <div class="video-item border-bottom pb-3 mb-3 {{ $loop->last ? 'border-0 mb-0 pb-0' : '' }}">
                                            <div class="d-flex align-items-start">
                                                <div class="me-3">
                                                    <div class="video-thumbnail bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 80px; height: 45px; border-radius: 4px;">
                                                        <i class="fas fa-play text-primary"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $video->title }}</h6>
                                                    <p class="text-muted small mb-2">{{ Str::limit($video->description, 100) }}</p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            {{ gmdate("i:s", $video->length_seconds) }}
                                                        </small>
                                                        <a href="{{ route('user.videos.show', [$course, $topic, $video]) }}" 
                                                           class="btn btn-primary btn-sm">
                                                            <i class="fas fa-play me-1"></i>
                                                            Ver Video
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tests -->
                    @if($topic->tests->count() > 0)
                        <div class="col-lg-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-0">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-clipboard-list me-2 text-success"></i>
                                        Evaluaciones
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @foreach($topic->tests as $test)
                                        <div class="test-item border-bottom pb-3 mb-3 {{ $loop->last ? 'border-0 mb-0 pb-0' : '' }}">
                                            <div class="d-flex align-items-start">
                                                <div class="me-3">
                                                    <div class="test-icon bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px; border-radius: 50%;">
                                                        @if(isset($userAttempts[$test->id]) && $userAttempts[$test->id]->passed)
                                                            <i class="fas fa-check-circle text-success"></i>
                                                        @elseif(isset($userAttempts[$test->id]))
                                                            <i class="fas fa-times-circle text-danger"></i>
                                                        @else
                                                            <i class="fas fa-clipboard-list text-muted"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $test->title }}</h6>
                                                    <p class="text-muted small mb-2">{{ Str::limit($test->description, 100) }}</p>
                                                    
                                                    <!-- Estado del test -->
                                                    @if(isset($userAttempts[$test->id]))
                                                        @php $attempt = $userAttempts[$test->id]; @endphp
                                                        @if($attempt->passed)
                                                            <div class="alert alert-success py-2 mb-2">
                                                                <small>
                                                                    <i class="fas fa-check-circle me-1"></i>
                                                                    Aprobado con {{ number_format($attempt->score, 1) }}%
                                                                </small>
                                                            </div>
                                                        @else
                                                            <div class="alert alert-warning py-2 mb-2">
                                                                <small>
                                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                                    Último intento: {{ number_format($attempt->score, 1) }}% (Mínimo: 70%)
                                                                </small>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="fas fa-question-circle me-1"></i>
                                                            {{ $test->questions->count() }} preguntas
                                                        </small>
                                                        <a href="{{ route('user.tests.show', [$course, $topic, $test]) }}" 
                                                           class="btn 
                                                            @if(isset($userAttempts[$test->id]) && $userAttempts[$test->id]->passed) 
                                                                btn-success 
                                                            @else 
                                                                btn-primary 
                                                            @endif 
                                                            btn-sm">
                                                            @if(isset($userAttempts[$test->id]) && $userAttempts[$test->id]->passed)
                                                                <i class="fas fa-eye me-1"></i>
                                                                Revisar
                                                            @else
                                                                <i class="fas fa-play me-1"></i>
                                                                Realizar Test
                                                            @endif
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Mensaje si no hay contenido -->
                    @if($topic->videos->count() === 0 && $topic->tests->count() === 0)
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center py-5">
                                    <div class="text-muted mb-3">
                                        <i class="fas fa-folder-open fa-3x"></i>
                                    </div>
                                    <h5 class="text-muted">Este topic no tiene contenido disponible</h5>
                                    <p class="text-muted">No hay videos ni evaluaciones en este topic.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.video-item,
.test-item {
    transition: background-color 0.2s ease-in-out;
}

.video-item:hover,
.test-item:hover {
    background-color: #f8f9fa;
    border-radius: 4px;
    padding: 8px !important;
}

.video-thumbnail {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.test-icon {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.alert {
    font-size: 0.875rem;
}
</style>
@endsection