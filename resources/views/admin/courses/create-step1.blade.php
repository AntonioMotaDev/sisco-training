@extends('layouts.app')

@section('title', 'Crear Curso - Paso 1 - SISCO Training')

@section('content')
    <div class="admin-layout">
        @include('admin.navigation')
        <div class="admin-content">
            <div class="container-fluid px-4 py-4">
                <!-- Header with Progress -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">Crear Nuevo Curso</h1>
                        <p class="text-muted">Paso 1 de 3 - Información básica del curso</p>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.courses.dashboard') }}">Cursos</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Crear Curso</li>
                        </ol>
                    </nav>
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary-blue">Paso 1</span>
                        <span class="badge bg-secondary">Paso 2</span>
                        <span class="badge bg-secondary">Paso 3</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary-blue" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-primary fw-bold">Información del Curso</small>
                        <small class="text-muted">Crear Temas</small>
                        <small class="text-muted">Cuestionarios (Opcional)</small>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary-blue text-white">
                                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Información Básica del Curso</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.courses.create.step1.store') }}" method="POST" id="step1Form">
                                    @csrf
                                    
                                    <!-- Nombre del Curso -->
                                    <div class="mb-4">
                                        <label for="name" class="form-label fs-6 fw-semibold">
                                            <i class="fas fa-graduation-cap me-2 text-primary-blue"></i>
                                            Nombre del Curso <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="Ej: Introducción a Laravel"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Ingresa un nombre descriptivo y atractivo para tu curso.
                                        </div>
                                    </div>

                                    <!-- Descripción del Curso -->
                                    <div class="mb-4">
                                        <label for="description" class="form-label fs-6 fw-semibold">
                                            <i class="fas fa-align-left me-2 text-primary-blue"></i>
                                            Descripción del Curso
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="5" 
                                                  placeholder="Describe el objetivo y contenido del curso. ¿Qué aprenderán los estudiantes?">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="fas fa-lightbulb me-1"></i>
                                            Describe qué aprenderán y por qué es importante este curso.
                                        </div>
                                    </div>

                                    <!-- Preview Card -->
                                    <div class="card bg-light border-0 mb-4" id="coursePreview" style="display: none;">
                                        <div class="card-body">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-eye me-1"></i>
                                                Vista Previa
                                            </h6>
                                            <div class="preview-course-card p-3 border rounded bg-white">
                                                <h5 class="mb-2" id="previewName">Nombre del curso aparecerá aquí</h5>
                                                <p class="text-muted mb-0" id="previewDescription">La descripción aparecerá aquí</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex justify-content-between align-items-center">
                                                                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            Siguiente Paso
                            <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Help Card -->
                        <div class="card mt-4 border-info">
                            <div class="card-body">
                                <h6 class="text-primary-blue mb-2">
                                    <i class="fas fa-question-circle me-1"></i>
                                    ¿Necesitas ayuda?
                                </h6>
                                <p class="mb-2 small">
                                    <strong>Paso 1:</strong> Define la información básica de tu curso.
                                </p>
                                <p class="mb-2 small">
                                    <strong>Paso 2:</strong> Crearás los temas que compondrán el curso.
                                </p>
                                <p class="mb-0 small">
                                    <strong>Paso 3:</strong> Opcionalmente podrás agregar cuestionarios a cada tema.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const preview = document.getElementById('coursePreview');
    const previewName = document.getElementById('previewName');
    const previewDescription = document.getElementById('previewDescription');

    function updatePreview() {
        const name = nameInput.value.trim();
        const description = descriptionInput.value.trim();

        if (name || description) {
            preview.style.display = 'block';
            previewName.textContent = name || 'Nombre del curso aparecerá aquí';
            previewDescription.textContent = description || 'La descripción aparecerá aquí';
        } else {
            preview.style.display = 'none';
        }
    }

    nameInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);

    // Form validation
    document.getElementById('step1Form').addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        
        if (!name) {
            e.preventDefault();
            nameInput.focus();
            
            // Show error animation
            nameInput.classList.add('is-invalid');
            setTimeout(() => {
                nameInput.classList.remove('is-invalid');
            }, 3000);
        }
    });
});
</script>
@endsection