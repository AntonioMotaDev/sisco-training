@extends('layouts.app')

@section('title', 'Lista de Cursos - SISCO Training')

@section('content')
    <div class="admin-layout">
        @include('admin.navigation')
        <div class="admin-content">
            <div class="container-fluid px-4 py-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0">Lista de Cursos</h1>
                        <p class="text-muted">Administra todos los cursos de SISCO Training</p>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Cursos</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Lista</li>
                        </ol>
                    </nav>
                </div>

                <div class="mb-0 d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <form method="GET" action="{{ route('admin.courses.index') }}" class="w-75 me-md-3 mb-2 mb-md-0">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Buscar cursos..." value="{{ request('q') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary ms-md-2 w-25">
                        <i class="fas fa-plus me-2"></i>Nuevo Curso
                    </a>
                </div>

                <!-- Courses Grid -->
                <div class="row">
                    @if(isset($courses) && $courses->count())
                        @foreach($courses as $curso)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <a href="{{ route('admin.courses.show', $curso->id) }}" class="text-decoration-none text-dark">
                                    <div class="card h-100 card-hover-link">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $curso->name }}</h5>
                                            <p class="card-text flex-grow-1">
                                                {{ $curso->description ?? 'Sin descripción.' }}
                                            </p>
                                            <div class="course-meta mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i> ~ {{ $curso->getDuration() ?? 'N/A' }} Min
                                                    <i class="fas fa-users ms-3 me-1"></i>{{ $curso->getStudentsCount() ?? 0 }} estudiantes
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="alert alert-info text-center">No hay cursos registrados.</div>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $courses->links() }}
                </div>

                <style>
                .card-hover-link:hover {
                    box-shadow: 0 0 0 4px #0d6efd33;
                    border-color: #0d6efd;
                    transition: box-shadow 0.2s, border-color 0.2s;
                }
                .card-hover-link {
                    cursor: pointer;
                }
                </style>
            </div>
        </div>
    </div>
@endsection 