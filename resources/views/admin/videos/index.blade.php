@extends('layouts.app')

@section('title', 'Dashboard Videos - SISCO Training')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Dashboard de Videos</h1>
                <p class="text-muted">Gestiona la biblioteca de videos educativos</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.dashboard') }}">Cursos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Videos</li>
                </ol>
            </nav>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('videos.create') }}" class="btn btn-primary">
                    <i class="fas fa-upload me-2"></i>Cargar nuevo Video
                </a>
        </div>

        <!-- Videos Grid -->
        <div class="row">
            @forelse($videos as $video)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="video-thumbnail position-relative" style="">
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
                            <p class="card-text text-muted">{{ $video->topic->name ?? 'Sin tema' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-eye me-1"></i>-- vistas
                                </small>
                                <div class="btn-group">
                                    <a href="{{ route('videos.edit', $video->id) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('videos.destroy', $video->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-start" title="Eliminar" onclick="return confirm('¿Eliminar este video?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">No hay videos cargados.</div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $videos->links() }}
        </div>
    </div>

    </div>
</div>

@endsection     