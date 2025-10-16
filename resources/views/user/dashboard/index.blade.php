@extends('layouts.app')

@section('content')
<div class="user-layout">
    @include('user.navigation')
    <div class="user-content">
        <!-- Main content -->
        <div class="container-fluid px-4 py-4">
            <div class="content-area p-4">
                <div class="mb-4">
                    <h1 class="h3 mb-0 text-muted">Mi Dashboard</h1>
                    <p class="text-muted">Bienvenido a tu panel de cursos, {{ $user->name }}</p>
                </div>

                <!-- Estadísticas generales -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="text-primary mb-2">
                                    <i class="fas fa-book fa-2x"></i>
                                </div>
                                <h4 class="fw-bold text-primary-blue">{{ $stats['total_courses'] }}</h4>
                                <p class="text-muted mb-0">Cursos Inscritos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="text-success mb-2">
                                    <i class="fas fa-play-circle fa-2x"></i>
                                </div>
                                <h4 class="fw-bold text-success">{{ $stats['active_courses'] }}</h4>
                                <p class="text-muted mb-0">Cursos Activos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="text-info mb-2">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <h4 class="fw-bold text-info">{{ $stats['completed_courses'] }}</h4>
                                <p class="text-muted mb-0">Cursos Completados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="text-warning mb-2">
                                    <i class="fas fa-percentage fa-2x"></i>
                                </div>
                                <h4 class="fw-bold text-warning">{{ $stats['success_rate'] }}%</h4>
                                <p class="text-muted mb-0">Éxito en Tests</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cursos inscritos -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-book-open me-2 text-primary"></i>
                            Mis Cursos
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($enrolledCourses->count() > 0)
                            <div class="row">
                                @foreach($enrolledCourses as $course)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100 border-0 shadow-sm course-card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title text-truncate">{{ $course->name }}</h6>
                                                    @if($course->pivot->status === 'completed')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    @elseif($course->pivot->status === 'active')
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-play"></i>
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <p class="card-text text-muted small">
                                                    {{ Str::limit($course->description, 100) }}
                                                </p>

                                                <!-- Barra de progreso -->
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <small class="text-muted">Progreso</small>
                                                        <small class="text-muted">{{ number_format($course->progress['progress_percentage'], 1) }}%</small>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar 
                                                            @if($course->progress['progress_percentage'] >= 100) bg-success
                                                            @elseif($course->progress['progress_percentage'] >= 70) bg-info
                                                            @elseif($course->progress['progress_percentage'] >= 30) bg-warning
                                                            @else bg-danger
                                                            @endif"
                                                            role="progressbar" 
                                                            style="width: {{ $course->progress['progress_percentage'] }}%"
                                                            aria-valuenow="{{ $course->progress['progress_percentage'] }}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Información del curso -->
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <div class="text-center">
                                                            <small class="text-muted d-block">Topics</small>
                                                            <strong class="text-primary">{{ $course->topics->count() }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-center">
                                                            <small class="text-muted d-block">Tests pasados</small>
                                                            <strong class="text-success">{{ $course->progress['passed_tests'] }}/{{ $course->progress['total_tests'] }}</strong>
                                                        </div>
                                                    </div>
                                                </div>

                                                <a href="{{ route('user.courses.show', $course) }}" class="btn btn-primary btn-sm w-100">
                                                    <i class="fas fa-eye me-1"></i>
                                                    @if($course->pivot->status === 'completed')
                                                        Revisar Curso
                                                    @else
                                                        Continuar Curso
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="text-muted mb-3">
                                    <i class="fas fa-book fa-3x"></i>
                                </div>
                                <h5 class="text-muted">No tienes cursos asignados</h5>
                                <p class="text-muted">Contacta con el administrador para que te inscriba en cursos.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.course-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.course-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 10px;
}

.badge {
    font-size: 0.7rem;
}
</style>
@endsection