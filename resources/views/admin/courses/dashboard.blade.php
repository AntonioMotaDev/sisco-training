@extends('layouts.app')

@section('title', 'Dashboard de Cursos - SISCO Training')

@section('content')
<div class="container-fluid px-4 py-4">
@include('admin.navigation')    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Dashboard de Cursos</h1>
            <p class="text-muted">Gestiona todos los aspectos de los cursos de SISCO Training</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cursos</li>
            </ol>
        </nav>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Cursos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Videos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">45</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-video fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Cuestionarios
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">28</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Estudiantes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">156</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Cards -->
    <div class="row">
        <!-- Gestionar Cursos -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-book-open me-2"></i>Gestionar Cursos</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Crear, editar y administrar todos los cursos disponibles en la plataforma.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('courses.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>Ver Cursos
                        </a>
                        <a href="{{ route('courses.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i>Crear Nuevo Curso
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestionar Videos -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-video me-2"></i>Gestionar Videos</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Administrar la biblioteca de videos educativos y contenido multimedia.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('courses.videos.dashboard') }}" class="btn btn-success">
                            <i class="fas fa-play me-2"></i>Dashboard Videos
                        </a>
                        <a href="#" class="btn btn-outline-success">
                            <i class="fas fa-upload me-2"></i>Subir Video
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestionar Cuestionarios -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Gestionar Cuestionarios</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Crear y administrar cuestionarios y evaluaciones para los cursos.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('courses.quizzes.dashboard') }}" class="btn btn-info">
                            <i class="fas fa-clipboard-list me-2"></i>Dashboard Cuestionarios
                        </a>
                        <a href="{{ route('courses.quizzes.create') }}" class="btn btn-outline-info">
                            <i class="fas fa-plus me-2"></i>Crear Cuestionario
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestionar Usuarios -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Gestionar Usuarios</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Administrar estudiantes, asignar cursos y monitorear el progreso.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('courses.users.index') }}" class="btn btn-warning">
                            <i class="fas fa-user-cog me-2"></i>Gestionar Usuarios
                        </a>
                        <a href="#" class="btn btn-outline-warning">
                            <i class="fas fa-chart-line me-2"></i>Ver Estadísticas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-3px);
}

.text-xs {
    font-size: 0.7rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
}
</style>
@endsection 