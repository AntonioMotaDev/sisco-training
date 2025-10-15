@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 p-0">
            @include('admin.navigation')
        </div>
        
        <!-- Main content -->
        <div class="col-lg-10">
            <div class="content-area p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-graduation-cap me-2"></i>Usuarios inscritos en: {{ $course->name }}</h2>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver a Usuarios
                    </a>
                </div>

                <!-- Información del curso -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-info-circle me-2"></i>Información del Curso</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>{{ $course->name }}</h5>
                                @if($course->description)
                                    <p class="text-muted">{{ $course->description }}</p>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="text-end">
                                    <h4 class="text-primary">{{ $users->total() }}</h4>
                                    <small>Usuarios Inscritos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de usuarios -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-users me-2"></i>Usuarios Inscritos</h5>
                    </div>
                    <div class="card-body">
                        @if($users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Email</th>
                                            <th>Rol</th>
                                            <th>Tipo</th>
                                            <th>Intentos</th>
                                            <th>Progreso</th>
                                            <th>Último Acceso</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            @php
                                                $courseProgress = $user->getCourseProgress($course->id);
                                                $userAttempts = $user->attempts->filter(function($attempt) use ($course) {
                                                    return $attempt->test && 
                                                           $attempt->test->topic && 
                                                           $attempt->test->topic->courses->contains('id', $course->id);
                                                });
                                                $lastAttempt = $userAttempts->sortByDesc('created_at')->first();
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle me-2">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $user->name }}</div>
                                                            <small class="text-muted">{{ $user->username }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $user->email ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $user->role->name }}</span>
                                                </td>
                                                <td>
                                                    @if($user->access_token)
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-key me-1"></i>Token
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-user-circle me-1"></i>Cuenta
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <span class="badge bg-primary">{{ $userAttempts->count() }}</span>
                                                        <span class="badge bg-success">{{ $userAttempts->where('passed', true)->count() }}</span>
                                                        <span class="badge bg-danger">{{ $userAttempts->where('passed', false)->count() }}</span>
                                                    </div>
                                                    <small class="text-muted">Total / Aprobados / Reprobados</small>
                                                </td>
                                                <td>
                                                    <div class="progress mb-1" style="height: 8px;">
                                                        <div class="progress-bar 
                                                            @if($courseProgress['progress_percentage'] >= 100) bg-success
                                                            @elseif($courseProgress['progress_percentage'] >= 70) bg-info  
                                                            @elseif($courseProgress['progress_percentage'] >= 40) bg-warning
                                                            @else bg-danger @endif"
                                                            role="progressbar" 
                                                            style="width: {{ $courseProgress['progress_percentage'] }}%">
                                                        </div>
                                                    </div>
                                                    <small>{{ $courseProgress['progress_percentage'] }}% 
                                                        ({{ $courseProgress['passed_tests'] }}/{{ $courseProgress['total_tests'] }})</small>
                                                </td>
                                                <td>
                                                    @if($lastAttempt)
                                                        <small>{{ $lastAttempt->created_at->format('d/m/Y H:i') }}</small>
                                                    @else
                                                        <small class="text-muted">Sin intentos</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('admin.users.show', $user) }}" 
                                                           class="btn btn-outline-info" title="Ver detalles completos">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                                           class="btn btn-outline-warning" title="Editar usuario">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginación -->
                            <div class="d-flex justify-content-center mt-3">
                                {{ $users->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                <h5>No hay usuarios inscritos</h5>
                                <p class="text-muted">Este curso aún no tiene usuarios que hayan tomado evaluaciones.</p>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                                    <i class="fas fa-users me-1"></i>Ver Todos los Usuarios
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Estadísticas del curso -->
                @if($users->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3>{{ $users->total() }}</h3>
                                <p>Total Inscritos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                @php
                                    $completedUsers = 0;
                                    $inProgressUsers = 0;
                                    $totalProgress = 0;
                                    
                                    foreach($users as $user) {
                                        $progress = $user->getCourseProgress($course->id);
                                        $totalProgress += $progress['progress_percentage'];
                                        
                                        if ($progress['progress_percentage'] >= 100) {
                                            $completedUsers++;
                                        } elseif ($progress['progress_percentage'] > 0) {
                                            $inProgressUsers++;
                                        }
                                    }
                                    
                                    $averageProgress = $users->count() > 0 ? $totalProgress / $users->count() : 0;
                                @endphp
                                <h3>{{ $completedUsers }}</h3>
                                <p>Completaron</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3>{{ $inProgressUsers }}</h3>
                                <p>En Progreso</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h3>{{ round($averageProgress, 1) }}%</h3>
                                <p>Progreso Promedio</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}
</style>
@endsection