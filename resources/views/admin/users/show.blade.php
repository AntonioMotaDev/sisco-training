@extends('layouts.app')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">    
        <!-- Main content -->
        <div class="container-fluid px-4 ">
            <div class="content-area p-4">
                <nav aria-label="breadcrumb"></nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.dashboard') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-muted mb-0">Detalles del Usuario</h3>
                    <div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- Información personal -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary-blue text-white">
                            <h5><i class="fas fa-user me-2"></i>Información Personal</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="row d-flex justify-content-start">
                                <div class="col">
                                    <div class="avatar-circle-large mx-auto mb-3">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <h4>{{ $user->name }}</h4>
                                    <p class="text-muted">{{ $user->username }}</p>
                                </div>

                                <div class="col">
                                    <div class="row text-start">
                                        <div class="col-12 mb-2">
                                            <strong>Email:</strong> 
                                            <span class="text-muted">{{ $user->email ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <strong>Rol:</strong>
                                            <span class="text-muted ms-2">{{ $user->role->name }}</span>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <strong>Tipo:</strong>
                                            @if($user->access_token)
                                                <span class="ms-2 text-muted">
                                                    <i class="fas fa-key me-1"></i>Usuario con Token
                                                </span>
                                            @else
                                                <span class="ms-2 text-muted">
                                                    <i class="fas fa-user-circle me-1"></i>Usuario con Cuenta
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-12 mb-2">
                                            <strong>Registrado:</strong>
                                            <span class="text-muted">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del usuario -->
                    <div class="col-lg-4">

                        <!-- Información de token si aplica -->
                        @if($user->access_token)
                        <div class="card mb-4">
                            <div class="card-header bg-primary-blue text-white">
                                <h5><i class="fas fa-key me-2"></i>Información de Token</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Token Actual:</strong>
                                    <div class="input-group mt-2">
                                        <input type="text" class="form-control" value="{{ $user->access_token }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button" 
                                                onclick="copyToClipboard('{{ $user->access_token }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>

                                <form action="{{ route('admin.users.renew-token', $user) }}" method="POST" class="d-grid">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success" 
                                            onclick="return confirm('¿Renovar el token de acceso? Esto generará un nuevo token.')">
                                        <i class="fas fa-sync me-1"></i>Renovar Token
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif

                        <!-- Estadísticas -->
                        <div class="card">
                            <div class="card-header bg-primary-blue text-white">
                                <h5><i class="fas fa-chart-line me-2"></i>Estadísticas</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <h4 class="text-primary">{{ $stats['total_attempts'] }}</h4>
                                        <small>Intentos Totales</small>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h4 class="text-success">{{ $stats['passed_attempts'] }}</h4>
                                        <small>Aprobados</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-danger">{{ $stats['failed_attempts'] }}</h4>
                                        <small>Reprobados</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-info">{{ $stats['success_rate'] }}%</h4>
                                        <small>Tasa Éxito</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progreso en cursos -->
                    <div class="col-lg-8 h-100">
                        <div class="card">
                            <div class="card-header bg-primary-blue text-white">
                                <h5><i class="fas fa-graduation-cap me-2"></i>Progreso en Cursos ({{ count($coursesProgress) }})</h5>
                            </div>
                            <div class="card-body">
                                @if(count($coursesProgress) > 0)
                                    @foreach($coursesProgress as $courseData)
                                        @php
                                            $course = $courseData['course'];
                                            $progress = $courseData['progress'];
                                        @endphp
                                        <div class="card border mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0">{{ $course->name }}</h6>
                                                    <span class="badge bg-primary">{{ $progress['progress_percentage'] }}%</span>
                                                </div>
                                                
                                                @if($course->description)
                                                    <p class="card-text text-muted small mb-2">{{ $course->description }}</p>
                                                @endif

                                                <div class="progress mb-2" style="height: 10px;">
                                                    <div class="progress-bar 
                                                        @if($progress['progress_percentage'] >= 100) bg-success
                                                        @elseif($progress['progress_percentage'] >= 70) bg-info  
                                                        @elseif($progress['progress_percentage'] >= 40) bg-warning
                                                        @else bg-danger @endif"
                                                        role="progressbar" 
                                                        style="width: {{ $progress['progress_percentage'] }}%">
                                                    </div>
                                                </div>

                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <small class="text-muted">Tests Totales</small>
                                                        <div class="fw-semibold">{{ $progress['total_tests'] }}</div>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="text-muted">Aprobados</small>
                                                        <div class="fw-semibold text-success">{{ $progress['passed_tests'] }}</div>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="text-muted">Pendientes</small>
                                                        <div class="fw-semibold text-warning">{{ $progress['total_tests'] - $progress['passed_tests'] }}</div>
                                                    </div>
                                                </div>

                                                @if($progress['progress_percentage'] >= 100)
                                                    <div class="alert alert-success mt-2 mb-0 py-2">
                                                        <i class="fas fa-trophy me-2"></i>¡Curso completado!
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                        <h5>Sin Cursos Inscritos</h5>
                                        <p class="text-muted">Este usuario aún no ha tomado ningún curso.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <!-- botones de accion -->
                <div class="text-end mt-4 d-flex gap-2 justify-content-end flex-wrap">

                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Editar Usuario
                    </a>
                    @if($user->access_token)
                        <form action="{{ route('admin.users.renew-token', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-yellow-green"
                                    onclick="return confirm('¿Renovar el token de acceso?')">
                                <i class="fas fa-sync me-2"></i>Renovar Token
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                            <i class="fas fa-trash me-2"></i>Eliminar Usuario
                        </button>
                    </form>

                </div>
                
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: var(--yellow-green);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 32px;
}
</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Mostrar mensaje de éxito
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check me-2"></i>Token copiado al portapapeles
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remover el elemento después de que se oculte
        toast.addEventListener('hidden.bs.toast', function() {
            document.body.removeChild(toast);
        });
    });
}
</script>
@endsection