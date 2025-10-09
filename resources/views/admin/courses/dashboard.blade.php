@extends('layouts.app')

@section('title', 'Dashboard de Cursos - SISCO Training')

@section('content')
    <div class="admin-layout">
        @include('admin.navigation')
        <div class="admin-content">
            <div class="container-fluid px-4 py-4">
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

        <div class="mb-4 text-end">
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Crear Curso
            </a>
        </div>

        <!-- Management Cards -->
        <div class="row">
            <!-- Gestionar Cursos -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary-blue text-white">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Gestionar Cursos</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Crear, editar y administrar todos los cursos disponibles en la plataforma.</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>Ver Cursos
                            </a>
                            <a href="{{ route('admin.courses.create') }}" class="btn btn-outline">
                                <i class="fas fa-plus me-2"></i>Crear Nuevo Curso
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestionar Temas -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary-blue text-white">
                        <h5 class="mb-0"><i class="fas fa-book-open me-2"></i>Gestionar Temas</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Crear, editar y administrar todos los temas disponibles en la plataforma.</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('topics.index') }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>Ver Temas
                            </a>
                            <a href="{{ route('topics.create') }}" class="btn btn-outline">
                                <i class="fas fa-plus me-2"></i>Crear Nuevo Tema
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 