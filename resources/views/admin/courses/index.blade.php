@extends('layouts.app')

@section('title', 'Lista de Cursos - SISCO Training')

@section('content')
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
                <li class="breadcrumb-item"><a href="{{ route('courses.dashboard') }}">Cursos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lista</li>
            </ol>
        </nav>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Buscar cursos...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-control">
                        <option value="">Todas las categorías</option>
                        <option value="control_plagas">Control de Plagas</option>
                        <option value="fumigacion">Fumigación</option>
                        <option value="seguridad">Seguridad</option>
                        <option value="equipos">Equipos y Herramientas</option>
                        <option value="normativas">Normativas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control">
                        <option value="">Todos los niveles</option>
                        <option value="beginner">Principiante</option>
                        <option value="intermediate">Intermedio</option>
                        <option value="advanced">Avanzado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-filter me-2"></i>Filtrar
                        </button>
                        <a href="{{ route('courses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nuevo Curso
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="row">
        <!-- Course Card 1 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <img src="https://via.placeholder.com/400x200/4c8ec5/ffffff?text=Control+de+Plagas" 
                     class="card-img-top" alt="Course image">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary">Control de Plagas</span>
                        <span class="badge bg-success">Activo</span>
                    </div>
                    <h5 class="card-title">Introducción al Control de Plagas</h5>
                    <p class="card-text flex-grow-1">
                        Conceptos fundamentales sobre identificación, prevención y control de plagas urbanas.
                    </p>
                    <div class="course-meta mb-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>8 horas
                            <i class="fas fa-signal ms-3 me-1"></i>Principiante
                            <i class="fas fa-users ms-3 me-1"></i>45 estudiantes
                        </small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" title="Videos">
                                <i class="fas fa-video"></i>
                            </button>
                            <button class="btn btn-outline-info btn-sm" title="Tests">
                                <i class="fas fa-clipboard-list"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Card 2 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <img src="https://via.placeholder.com/400x200/2daee1/ffffff?text=Fumigación" 
                     class="card-img-top" alt="Course image">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-info">Fumigación</span>
                        <span class="badge bg-success">Activo</span>
                    </div>
                    <h5 class="card-title">Técnicas Avanzadas de Fumigación</h5>
                    <p class="card-text flex-grow-1">
                        Métodos modernos y seguros para la aplicación de productos químicos en el control de plagas.
                    </p>
                    <div class="course-meta mb-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>12 horas
                            <i class="fas fa-signal ms-3 me-1"></i>Intermedio
                            <i class="fas fa-users ms-3 me-1"></i>32 estudiantes
                        </small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" title="Videos">
                                <i class="fas fa-video"></i>
                            </button>
                            <button class="btn btn-outline-info btn-sm" title="Tests">
                                <i class="fas fa-clipboard-list"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Card 3 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <img src="https://via.placeholder.com/400x200/b7cf49/ffffff?text=Seguridad" 
                     class="card-img-top" alt="Course image">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-warning">Seguridad</span>
                        <span class="badge bg-success">Activo</span>
                    </div>
                    <h5 class="card-title">Seguridad en el Trabajo</h5>
                    <p class="card-text flex-grow-1">
                        Protocolos de seguridad, uso de EPP y manejo seguro de productos químicos.
                    </p>
                    <div class="course-meta mb-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>6 horas
                            <i class="fas fa-signal ms-3 me-1"></i>Principiante
                            <i class="fas fa-users ms-3 me-1"></i>78 estudiantes
                        </small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" title="Videos">
                                <i class="fas fa-video"></i>
                            </button>
                            <button class="btn btn-outline-info btn-sm" title="Tests">
                                <i class="fas fa-clipboard-list"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Card 4 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <img src="https://via.placeholder.com/400x200/878d29/ffffff?text=Equipos" 
                     class="card-img-top" alt="Course image">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-secondary">Equipos</span>
                        <span class="badge bg-warning">Borrador</span>
                    </div>
                    <h5 class="card-title">Equipos y Herramientas</h5>
                    <p class="card-text flex-grow-1">
                        Mantenimiento, calibración y uso correcto de equipos de fumigación.
                    </p>
                    <div class="course-meta mb-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>10 horas
                            <i class="fas fa-signal ms-3 me-1"></i>Intermedio
                            <i class="fas fa-users ms-3 me-1"></i>0 estudiantes
                        </small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" title="Videos">
                                <i class="fas fa-video"></i>
                            </button>
                            <button class="btn btn-outline-info btn-sm" title="Tests">
                                <i class="fas fa-clipboard-list"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Card 5 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <img src="https://via.placeholder.com/400x200/6c757d/ffffff?text=Normativas" 
                     class="card-img-top" alt="Course image">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-dark">Normativas</span>
                        <span class="badge bg-success">Activo</span>
                    </div>
                    <h5 class="card-title">Normativas y Regulaciones</h5>
                    <p class="card-text flex-grow-1">
                        Marco legal y normativo para el control de plagas en diferentes sectores.
                    </p>
                    <div class="course-meta mb-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>4 horas
                            <i class="fas fa-signal ms-3 me-1"></i>Principiante
                            <i class="fas fa-users ms-3 me-1"></i>23 estudiantes
                        </small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" title="Videos">
                                <i class="fas fa-video"></i>
                            </button>
                            <button class="btn btn-outline-info btn-sm" title="Tests">
                                <i class="fas fa-clipboard-list"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Card 6 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <img src="https://via.placeholder.com/400x200/4c8ec5/ffffff?text=Casos+Prácticos" 
                     class="card-img-top" alt="Course image">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary">Control de Plagas</span>
                        <span class="badge bg-success">Activo</span>
                    </div>
                    <h5 class="card-title">Casos Prácticos</h5>
                    <p class="card-text flex-grow-1">
                        Resolución de casos reales en control de plagas urbanas y rurales.
                    </p>
                    <div class="course-meta mb-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>16 horas
                            <i class="fas fa-signal ms-3 me-1"></i>Avanzado
                            <i class="fas fa-users ms-3 me-1"></i>18 estudiantes
                        </small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" title="Videos">
                                <i class="fas fa-video"></i>
                            </button>
                            <button class="btn btn-outline-info btn-sm" title="Tests">
                                <i class="fas fa-clipboard-list"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Courses pagination">
            <ul class="pagination">
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection 