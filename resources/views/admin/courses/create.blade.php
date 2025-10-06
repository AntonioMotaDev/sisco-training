@extends('layouts.app')

@section('title', 'Crear Curso - SISCO Training')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Crear Nuevo Curso</h1>
            <p class="text-muted">Completa la información para crear un nuevo curso</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('courses.dashboard') }}">Cursos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crear Curso</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Form Column -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Información del Curso</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Título del Curso -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Título del Curso <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Duración y Nivel -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duración (horas)</label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                           id="duration" name="duration" value="{{ old('duration') }}" min="1">
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="level" class="form-label">Nivel</label>
                                    <select class="form-select @error('level') is-invalid @enderror" id="level" name="level">
                                        <option value="">Seleccionar nivel</option>
                                        <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Principiante</option>
                                        <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Intermedio</option>
                                        <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Avanzado</option>
                                    </select>
                                    @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Imagen del Curso -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Imagen del Curso</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</div>
                        </div>

                        <!-- Categoría -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Categoría</label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                                <option value="">Seleccionar categoría</option>
                                <option value="control_plagas" {{ old('category') == 'control_plagas' ? 'selected' : '' }}>Control de Plagas</option>
                                <option value="fumigacion" {{ old('category') == 'fumigacion' ? 'selected' : '' }}>Fumigación</option>
                                <option value="seguridad" {{ old('category') == 'seguridad' ? 'selected' : '' }}>Seguridad</option>
                                <option value="equipos" {{ old('category') == 'equipos' ? 'selected' : '' }}>Equipos y Herramientas</option>
                                <option value="normativas" {{ old('category') == 'normativas' ? 'selected' : '' }}>Normativas</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Objetivos de Aprendizaje -->
                        <div class="mb-3">
                            <label for="objectives" class="form-label">Objetivos de Aprendizaje</label>
                            <textarea class="form-control @error('objectives') is-invalid @enderror" 
                                      id="objectives" name="objectives" rows="3" 
                                      placeholder="Describe los objetivos que los estudiantes lograrán al completar este curso...">{{ old('objectives') }}</textarea>
                            @error('objectives')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prerequisitos -->
                        <div class="mb-3">
                            <label for="prerequisites" class="form-label">Prerequisitos</label>
                            <textarea class="form-control @error('prerequisites') is-invalid @enderror" 
                                      id="prerequisites" name="prerequisites" rows="2" 
                                      placeholder="Conocimientos o cursos previos requeridos...">{{ old('prerequisites') }}</textarea>
                            @error('prerequisites')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Estado del Curso -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Curso activo (visible para estudiantes)
                                </label>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('courses.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Crear Curso
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-info">Consejos para crear un buen curso:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Usa un título claro y descriptivo
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Incluye una descripción detallada
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Define objetivos específicos
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Establece prerequisitos claros
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Usa imágenes atractivas
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Próximos Pasos</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Después de crear el curso podrás:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-video text-primary me-2"></i>
                            Agregar videos
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-question-circle text-info me-2"></i>
                            Crear cuestionarios
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-users text-success me-2"></i>
                            Asignar estudiantes
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-chart-line text-warning me-2"></i>
                            Monitorear progreso
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-gray-800 {
    color: #5a5c69 !important;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
}

.form-label {
    font-weight: 500;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}
</style>
@endsection 