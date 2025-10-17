@extends('layouts.app')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">    
        <!-- Main content -->
        <div class="container-fluid px-4">
            <div class="content-area p-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.dashboard') }}">Usuarios</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a></li>
                        <li class="breadcrumb-item active">Inscripciones</li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="text-muted mb-0">
                            <i class="fas fa-user-graduate me-2"></i>
                            Gestión de Inscripciones
                        </h3>
                        <p class="text-muted mb-0">{{ $user->name }} - {{ $user->role->name ?? 'Sin rol' }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- Inscribir en nuevos cursos -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Inscribir en Cursos
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($availableCourses->count() > 0)
                                    <form method="POST" action="{{ route('admin.users.enroll', $user) }}">
                                        @csrf
                                        <p class="text-muted mb-3">Selecciona los cursos en los que deseas inscribir al usuario:</p>
                                        
                                        <div class="available-courses mb-3" style="max-height: 400px; overflow-y: auto;">
                                            @foreach($availableCourses as $course)
                                                <div class="form-check mb-3 p-3 border rounded">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           name="course_ids[]" 
                                                           value="{{ $course->id }}"
                                                           id="course_{{ $course->id }}">
                                                    <label class="form-check-label w-100" for="course_{{ $course->id }}">
                                                        <h6 class="mb-1">{{ $course->name }}</h6>
                                                        <p class="text-muted small mb-2">{{ Str::limit($course->description, 100) }}</p>
                                                        <div class="d-flex justify-content-between small text-muted">
                                                            <span>
                                                                <i class="fas fa-list me-1"></i>
                                                                {{ $course->topicsOrdered->count() }} topics
                                                            </span>
                                                            <span>
                                                                <i class="fas fa-clock me-1"></i>
                                                                {{ $course->getDuration() }} min
                                                            </span>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-user-plus me-1"></i>
                                            Inscribir en Cursos Seleccionados
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center py-4">
                                        <div class="text-muted mb-3">
                                            <i class="fas fa-check-circle fa-3x"></i>
                                        </div>
                                        <h5 class="text-muted">Todos los cursos asignados</h5>
                                        <p class="text-muted">El usuario ya está inscrito en todos los cursos disponibles.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Cursos inscritos -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0 text-white">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    Cursos Inscritos ({{ $enrolledCourses->count() }})
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($enrolledCourses->count() > 0)
                                    <div class="enrolled-courses" style="max-height: 400px; overflow-y: auto;">
                                        @foreach($enrolledCourses as $course)
                                            <div class="course-item border rounded p-3 mb-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="mb-0">{{ $course->name }}</h6>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                                type="button" 
                                                                data-bs-toggle="dropdown">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form method="POST" 
                                                                      action="{{ route('admin.users.unenroll', [$user, $course]) }}" 
                                                                      class="d-inline"
                                                                      onsubmit="return confirm('¿Estás seguro de desinscribir al usuario de este curso?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="fas fa-user-minus me-1"></i>
                                                                        Desinscribir
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                
                                                <p class="text-muted small mb-2">{{ Str::limit($course->description, 80) }}</p>
                                                
                                                <!-- Estado y progreso -->
                                                <div class="mb-2">
                                                    <span class="badge 
                                                        @if($course->pivot->status === 'completed') bg-success
                                                        @elseif($course->pivot->status === 'active') bg-primary
                                                        @else bg-secondary
                                                        @endif">
                                                        @if($course->pivot->status === 'completed')
                                                            <i class="fas fa-check"></i> Completado
                                                        @elseif($course->pivot->status === 'active')
                                                            <i class="fas fa-play"></i> Activo
                                                        @else
                                                            <i class="fas fa-pause"></i> {{ ucfirst($course->pivot->status) }}
                                                        @endif
                                                    </span>
                                                </div>

                                                <!-- Barra de progreso -->
                                                <div class="mb-2">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <small class="text-muted">Progreso</small>
                                                        <small class="text-muted">{{ number_format($course->progress['progress_percentage'], 1) }}%</small>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar 
                                                            @if($course->progress['progress_percentage'] >= 100) bg-success
                                                            @elseif($course->progress['progress_percentage'] >= 70) bg-info
                                                            @else bg-warning
                                                            @endif"
                                                            role="progressbar" 
                                                            style="width: {{ $course->progress['progress_percentage'] }}%">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Información adicional -->
                                                <div class="d-flex justify-content-between small text-muted">
                                                    <span>Inscrito: {{ $course->pivot->enrolled_at->format('d/m/Y') }}</span>
                                                    <span>Tests: {{ $course->progress['passed_tests'] }}/{{ $course->progress['total_tests'] }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <div class="text-muted mb-3">
                                            <i class="fas fa-book-open fa-3x"></i>
                                        </div>
                                        <h5 class="text-muted">Sin cursos asignados</h5>
                                        <p class="text-muted">Este usuario no está inscrito en ningún curso.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.course-item {
    transition: background-color 0.2s ease-in-out;
    background-color: #f8f9fa;
}

.course-item:hover {
    background-color: #e9ecef;
}

.form-check:hover {
    background-color: #f8f9fa;
}

.progress {
    border-radius: 3px;
}

.progress-bar {
    border-radius: 3px;
}

.available-courses::-webkit-scrollbar,
.enrolled-courses::-webkit-scrollbar {
    width: 6px;
}

.available-courses::-webkit-scrollbar-track,
.enrolled-courses::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.available-courses::-webkit-scrollbar-thumb,
.enrolled-courses::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.available-courses::-webkit-scrollbar-thumb:hover,
.enrolled-courses::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
</style>
@endsection