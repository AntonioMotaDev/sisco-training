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
                        <li class="breadcrumb-item active" aria-current="page">{{ $video->title }}</li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Video Player -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-0">
                                <div class="video-container" style="position: relative; width: 100%; height: 0; padding-bottom: 56.25%;">
                                    <iframe 
                                        src="https://www.youtube.com/embed/{{ $video->youtube_video_id }}" 
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                                        frameborder="0" 
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                        </div>

                        <!-- Video Info -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="mb-3">{{ $video->title }}</h3>
                                <p class="text-muted mb-3">{{ $video->description }}</p>
                                
                                <div class="d-flex align-items-center text-muted mb-3">
                                    <div class="me-4">
                                        <i class="fas fa-clock me-1"></i>
                                        Duración: {{ gmdate("H:i:s", $video->length_seconds) }}
                                    </div>
                                    <div class="me-4">
                                        <i class="fas fa-calendar me-1"></i>
                                        Agregado: {{ $video->created_at->format('d/m/Y') }}
                                    </div>
                                </div>

                                @if($video->tags)
                                    <div class="mb-3">
                                        <h6>Tags:</h6>
                                        @foreach(explode(',', $video->tags) as $tag)
                                            <span class="badge bg-light text-dark me-1">{{ trim($tag) }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Botones de navegación -->
                                <div class="d-flex gap-2 mt-4">
                                    <a href="{{ route('user.topics.show', [$course, $topic]) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Volver al Topic
                                    </a>
                                    
                                    @php
                                        $currentVideoIndex = $topic->videos->search(function($v) use ($video) {
                                            return $v->id === $video->id;
                                        });
                                        $nextVideo = $topic->videos->get($currentVideoIndex + 1);
                                        $prevVideo = $currentVideoIndex > 0 ? $topic->videos->get($currentVideoIndex - 1) : null;
                                    @endphp

                                    @if($prevVideo)
                                        <a href="{{ route('user.videos.show', [$course, $topic, $prevVideo]) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-chevron-left me-1"></i>
                                            Video Anterior
                                        </a>
                                    @endif

                                    @if($nextVideo)
                                        <a href="{{ route('user.videos.show', [$course, $topic, $nextVideo]) }}" class="btn btn-primary">
                                            Siguiente Video
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    @else
                                        <!-- Si no hay más videos, mostrar botón para ir a los tests -->
                                        @if($topic->tests->count() > 0)
                                            <a href="{{ route('user.topics.show', [$course, $topic]) }}" class="btn btn-success">
                                                <i class="fas fa-clipboard-list me-1"></i>
                                                Ir a las Evaluaciones
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Lista de videos del topic -->
                        @if($topic->videos->count() > 1)
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-0">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-list me-2 text-primary"></i>
                                        Videos del Topic
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    @foreach($topic->videos as $index => $topicVideo)
                                        <div class="video-list-item p-3 border-bottom {{ $topicVideo->id === $video->id ? 'bg-light' : '' }} {{ $loop->last ? 'border-0' : '' }}">
                                            <div class="d-flex align-items-start">
                                                <div class="me-3">
                                                    <div class="video-thumbnail bg-primary text-white d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 24px; border-radius: 2px; font-size: 10px;">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 small {{ $topicVideo->id === $video->id ? 'text-primary' : '' }}">
                                                        {{ $topicVideo->title }}
                                                        @if($topicVideo->id === $video->id)
                                                            <i class="fas fa-play ms-1"></i>
                                                        @endif
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ gmdate("i:s", $topicVideo->length_seconds) }}
                                                    </small>
                                                    @if($topicVideo->id !== $video->id)
                                                        <div class="mt-1">
                                                            <a href="{{ route('user.videos.show', [$course, $topic, $topicVideo]) }}" 
                                                               class="btn btn-sm btn-outline-primary">
                                                                Ver
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Progreso del curso -->
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
                                         style="width: {{ $progress['progress_percentage'] }}%"
                                         aria-valuenow="{{ $progress['progress_percentage'] }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">{{ $progress['passed_tests'] }}/{{ $progress['total_tests'] }} tests completados</small>
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

<style>
.video-list-item {
    transition: background-color 0.2s ease-in-out;
}

.video-list-item:hover:not(.bg-light) {
    background-color: #f8f9fa;
}

.video-container iframe {
    border-radius: 8px;
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