@extends('layouts.app')

@section('title', 'Dashboard Videos - SISCO Training')

@section('content')
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
                <li class="breadcrumb-item"><a href="{{ route('courses.dashboard') }}">Cursos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Videos</li>
            </ol>
        </nav>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end mb-4">
        <div class="btn-group">
            <button type="button" class="btn btn-primary">
                <i class="fas fa-upload me-2"></i>Subir Video
            </button>
            <button type="button" class="btn btn-outline-primary">
                <i class="fas fa-link me-2"></i>Agregar URL
            </button>
        </div>
    </div>

    <!-- Videos Grid -->
    <div class="row">
        <!-- Video Card 1 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="video-thumbnail position-relative">
                    <img src="https://via.placeholder.com/400x225/4e73df/ffffff?text=Video+1" 
                         class="card-img-top" alt="Video thumbnail">
                    <div class="play-overlay">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="video-duration">10:30</div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Introducción al Control de Plagas</h5>
                    <p class="card-text text-muted">Conceptos básicos sobre identificación y control de plagas urbanas.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-eye me-1"></i>245 vistas
                        </small>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Card 2 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="video-thumbnail position-relative">
                    <img src="https://via.placeholder.com/400x225/1cc88a/ffffff?text=Video+2" 
                         class="card-img-top" alt="Video thumbnail">
                    <div class="play-overlay">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="video-duration">15:45</div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Técnicas de Fumigación</h5>
                    <p class="card-text text-muted">Métodos modernos y seguros para aplicación de pesticidas.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-eye me-1"></i>189 vistas
                        </small>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Card 3 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="video-thumbnail position-relative">
                    <img src="https://via.placeholder.com/400x225/36b9cc/ffffff?text=Video+3" 
                         class="card-img-top" alt="Video thumbnail">
                    <div class="play-overlay">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="video-duration">08:22</div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Seguridad en el Trabajo</h5>
                    <p class="card-text text-muted">Protocolos de seguridad y uso de equipo de protección personal.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-eye me-1"></i>312 vistas
                        </small>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Card 4 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="video-thumbnail position-relative">
                    <img src="https://via.placeholder.com/400x225/f6c23e/ffffff?text=Video+4" 
                         class="card-img-top" alt="Video thumbnail">
                    <div class="play-overlay">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="video-duration">12:18</div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Equipos y Herramientas</h5>
                    <p class="card-text text-muted">Mantenimiento y uso correcto de equipos de fumigación.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-eye me-1"></i>156 vistas
                        </small>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Card 5 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="video-thumbnail position-relative">
                    <img src="https://via.placeholder.com/400x225/e74a3b/ffffff?text=Video+5" 
                         class="card-img-top" alt="Video thumbnail">
                    <div class="play-overlay">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="video-duration">20:05</div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Normativas y Regulaciones</h5>
                    <p class="card-text text-muted">Marco legal y normativo para el control de plagas.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-eye me-1"></i>98 vistas
                        </small>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Card 6 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="video-thumbnail position-relative">
                    <img src="https://via.placeholder.com/400x225/6f42c1/ffffff?text=Video+6" 
                         class="card-img-top" alt="Video thumbnail">
                    <div class="play-overlay">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="video-duration">14:33</div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Casos Prácticos</h5>
                    <p class="card-text text-muted">Resolución de casos reales en control de plagas urbanas.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-eye me-1"></i>267 vistas
                        </small>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar">
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
        <nav aria-label="Videos pagination">
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

<style>
.text-gray-800 {
    color: #5a5c69 !important;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
}

.video-thumbnail {
    position: relative;
    overflow: hidden;
}

.video-thumbnail img {
    transition: transform 0.3s ease;
}

.video-thumbnail:hover img {
    transform: scale(1.05);
}

.play-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 3rem;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.video-thumbnail:hover .play-overlay {
    opacity: 1;
}

.video-duration {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
}

.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

.btn-outline-primary {
    color: #4e73df;
    border-color: #4e73df;
}

.btn-outline-primary:hover {
    background-color: #4e73df;
    border-color: #4e73df;
}
</style>
@endsection 