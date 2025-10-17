@extends('layouts.app')

@section('title', 'Detalle del Curso - SISCO Training')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">
        <div class="container-fluid py-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">{{ $course->name }}</h1>
                    <p class="text-muted mb-0">{{ $course->description ?? 'Sin descripción.' }}</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Cursos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalle</li>
                    </ol>
                </nav>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-edit me-1"></i> Editar curso
                </a>
                <a href="{{ route('videos.byCourse', $course->id) }}" class="btn btn-outline">
                    <i class="fas fa-video me-1"></i> Ver videos
                </a>
                <a href="#" class="btn btn-outline-olive">
                    <i class="fas fa-clipboard-list me-1"></i> Ver temas
                </a>
            </div>

            <!-- Meta info -->
            <div class="row mb-4">
                <div class="col-md-4 mb-2">
                    <div class="card card-body shadow-sm h-100">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock fa-2x text-primary-blue me-3"></i>
                            <div>
                                <div class="fw-bold">Duración total</div>
                                <div>~ {{ $course->getDuration() }} min</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="card card-body shadow-sm h-100">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users fa-2x text-primary-blue me-3"></i>
                            <div>
                                <div class="fw-bold">Estudiantes inscritos</div>
                                <div>{{ $course->getStudentsCount() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="card card-body shadow-sm h-100">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list fa-2x text-primary-blue me-3"></i>
                            <div>
                                <div class="fw-bold">Temas</div>
                                <div>{{ $course->topicsOrdered->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Topics List -->
            <div class="card mb-4">
                <div class="card-header bg-white fw-bold">
                    Temario del curso
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Tema</th>
                                    <th width="40%">Descripción</th>
                                    <th width="10%" class="text-center">Videos</th>
                                    <th width="10%" class="text-center">Cuestionarios</th>
                                    <th width="10%" class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($course->topicsOrdered as $index => $topic)
                                    <tr>
                                        <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold text-olive">{{ $topic->name }}</div>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $topic->description ?? 'Sin descripción' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-primary-blue">
                                                <i class="fas fa-video me-1"></i> {{ $topic->videos->count() }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-primary-blue text-dark">
                                                <i class="fas fa-clipboard-list me-1"></i> {{ $topic->tests->count() }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.tests.index', $topic->id) }}" 
                                                   class="btn btn-sm btn-outline" 
                                                   title="Ver cuestionarios">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                                <a href="{{ route('admin.tests.create', $topic->id) }}" 
                                                   class="btn btn-sm btn-outline-olive" 
                                                   title="Crear cuestionario">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-book-open fa-2x mb-2 opacity-50"></i>
                                            <div>No hay temas registrados para este curso.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Videos destacados -->
            <div class="card mb-4">
                <div class="card-header bg-white fw-bold">
                    Videos destacados
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $featuredVideos = $course->topicsOrdered->flatMap(fn($t) => $t->videos)->sortByDesc('created_at')->take(3);
                        @endphp
                        @forelse($featuredVideos as $video)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 shadow-sm">
                                    <img src="https://img.youtube.com/vi/{{ $video->youtube_id ?? 'dQw4w9WgXcQ' }}/hqdefault.jpg" class="card-img-top" alt="Video thumbnail">
                                    <div class="card-body">
                                        <h6 class="card-title mb-1">{{ $video->name }}</h6>
                                        <p class="card-text small text-muted mb-2">Tema: {{ $video->topic->name ?? 'Sin tema' }}</p>
                                        <span class="badge bg-secondary"><i class="fas fa-clock me-1"></i>{{ $video->length_seconds ? gmdate('i:s', $video->length_seconds) : '--:--' }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-primary float-end" data-bs-toggle="modal" data-bs-target="#videoModalShow" data-ytid="{{ $video->youtube_id }}" data-title="{{ $video->name }}">
                                            <i class="fas fa-play"></i> Ver
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted">No hay videos registrados.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Modal para reproducir video destacado -->
            <div class="modal fade" id="videoModalShow" tabindex="-1" aria-labelledby="videoModalShowLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="videoModalShowLabel">Ver video</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body p-0" style="background:#000;">
                            <div class="ratio ratio-16x9">
                                <iframe id="ytplayerShow" src="" title="YouTube video" allowfullscreen allow="autoplay"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progreso de usuarios -->
            <div class="card mb-4">
                <div class="card-header bg-white fw-bold">
                    Progreso de usuarios
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Usuario</th>
                                    <th>Temas Aprobados</th>
                                    <th>Progreso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $users = $course->topics->flatMap(fn($t) => $t->users)->unique('id');
                                @endphp
                                @forelse($users as $user)
                                    @php
                                        $totalTopics = $course->topics->count();
                                        $completed = $user->topics->whereIn('id', $course->topics->pluck('id'))->where('pivot.status', 'aprobado')->count();
                                        $progress = $totalTopics > 0 ? round(($completed / $totalTopics) * 100) : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $completed }} / {{ $totalTopics }}</td>
                                        <td>
                                            <div class="progress" style="height: 18px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">{{ $progress }}%</div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No hay usuarios inscritos en este curso.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Borrar Curso -->
            <div class="d-flex justify-content-end gap-2">
                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar este curso?')">
                        <i class="fas fa-trash"></i> Eliminar Curso
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var videoModal = document.getElementById('videoModalShow');
    var ytplayer = document.getElementById('ytplayerShow');
    var title = document.getElementById('videoModalShowLabel');
    videoModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var ytid = button.getAttribute('data-ytid');
        var vtitle = button.getAttribute('data-title');
        if (ytid) {
            ytplayer.src = 'https://www.youtube.com/embed/' + ytid + '?autoplay=1&controls=1&modestbranding=1&rel=0&fs=1';
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

@endsection
