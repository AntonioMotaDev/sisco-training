@extends('layouts.app')

@section('title', 'Videos del Curso - SISCO Training')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">
        <div class="container-fluid px-4 py-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Videos del Curso</h1>
                    <p class="text-muted mb-0">Biblioteca de videos asociados a este curso</p>
                </div>
                <a href="{{ route('admin.courses.show', $courseId) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver al curso
                </a>
            </div>

            <div class="mb-0 text-end">
                <a href="{{ route('videos.create', ['course' => $courseId]) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Nuevo video
                </a>
            </div>

            <!-- Videos Grid -->
            <div class="row">
                @forelse($videos as $video)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="video-thumbnail position-relative">
                                <img src="https://img.youtube.com/vi/{{ $video->youtube_id ?? 'dQw4w9WgXcQ' }}/hqdefault.jpg"
                                     class="card-img-top" alt="Video thumbnail">
                                <div class="play-overlay">
                                    <i class="fas fa-play-circle"></i>
                                </div>
                                <div class="video-duration">
                                    @if($video->length_seconds)
                                        {{ gmdate('i:s', $video->length_seconds) }}
                                    @else
                                        --:--
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $video->name }}</h5>
                                <p class="card-text text-muted mb-1">Tema: {{ $video->topic->name ?? 'Sin tema' }}</p>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#videoModal" data-ytid="{{ $video->youtube_id }}" data-title="{{ $video->name }}">
                                    <i class="fas fa-play"></i> Ver video
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">No hay videos registrados para este curso.</div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $videos->links() }}
            </div>
        </div>
    </div>

    <!-- Modal para reproducir video -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="videoModalLabel">Ver video</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body p-0" style="background:#000;">
            <div class="ratio ratio-16x9">
              <iframe id="ytplayer" src="" title="YouTube video" allowfullscreen allow="autoplay"></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

<style>
.video-thumbnail {
    position: relative;
}
.play-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    font-size: 2.5rem;
    opacity: 0.7;
    pointer-events: none;
}
.video-duration {
    position: absolute;
    bottom: 8px;
    right: 12px;
    background: rgba(0,0,0,0.7);
    color: #fff;
    font-size: 0.9rem;
    padding: 2px 8px;
    border-radius: 12px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var videoModal = document.getElementById('videoModal');
    var ytplayer = document.getElementById('ytplayer');
    var title = document.getElementById('videoModalLabel');
    videoModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var ytid = button.getAttribute('data-ytid');
        var vtitle = button.getAttribute('data-title');
        if (ytid) {
            ytplayer.src = 'https://www.youtube.com/embed/' + ytid + '?autoplay=1&controls=1&rel=0&fs=1&color=white&disablekb=1';
        }
        if (title && vtitle) {
            title.textContent = vtitle;
        }
    });
    videoModal.addEventListener('hidden.bs.modal', function () {
        ytplayer.src = '';
    });
});
</script>
