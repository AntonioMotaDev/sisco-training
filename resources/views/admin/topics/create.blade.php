@extends('layouts.app')

@section('title', 'Crear Nuevo Tema - SISCO Training')

@section('content')
    <div class="admin-layout">
        @include('admin.navigation')
        <div class="admin-content">
            <div class="container-fluid px-4 py-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">Crear Nuevo Tema</h1>
                        <p class="text-muted">Completa la información para crear un nuevo tema</p>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.courses.dashboard') }}">Cursos</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('topics.index') }}">Temas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Crear</li>
                        </ol>
                    </nav>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-book-open me-2"></i>Información del Tema
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('topics.store') }}" method="POST" id="topicForm">
                                    @csrf
                                    
                                    <!-- Name Field -->
                                    <div class="mb-4">
                                        <label for="name" class="form-label fw-medium">
                                            Nombre del Tema <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            class="form-control @error('name') is-invalid @enderror" 
                                            id="name" 
                                            name="name" 
                                            value="{{ old('name') }}" 
                                            placeholder="Ej: Fundamentos de Programación"
                                            maxlength="255"
                                            required
                                        >
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            El nombre debe ser único y descriptivo (máximo 255 caracteres)
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Code Field -->
                                    <div class="mb-4">
                                        <label for="code" class="form-label fw-medium">
                                            Código del Tema
                                        </label>
                                        <input 
                                            type="text" 
                                            class="form-control @error('code') is-invalid @enderror" 
                                            id="code" 
                                            name="code" 
                                            value="{{ old('code') }}" 
                                            placeholder="Ej: FDP o deja vacío para generar automáticamente"
                                            maxlength="10"
                                        >
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Si no proporcionas un código, se generará automáticamente basado en el nombre
                                        </div>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Description Field -->
                                    <div class="mb-4">
                                        <label for="description" class="form-label fw-medium">
                                            Descripción
                                        </label>
                                        <textarea 
                                            class="form-control @error('description') is-invalid @enderror" 
                                            id="description" 
                                            name="description" 
                                            rows="4" 
                                            placeholder="Describe brevemente el contenido y objetivos de este tema..."
                                            maxlength="1000"
                                        >{{ old('description') }}</textarea>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Proporciona una descripción clara del tema (máximo 1000 caracteres)
                                        </div>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Approval Status -->
                                    <div class="mb-4">
                                        <div class="form-check form-switch">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                id="is_approved" 
                                                name="is_approved" 
                                                value="1"
                                                {{ old('is_approved') ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label fw-medium" for="is_approved">
                                                Aprobar tema inmediatamente
                                            </label>
                                        </div>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Los temas aprobados estarán disponibles para asignar a cursos
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('topics.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-2"></i>Crear Tema
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Help Card -->
                        <div class="card mt-4 border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-lightbulb me-2"></i>Consejos para crear un buen tema
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    <li><strong>Nombre descriptivo:</strong> Usa nombres claros que reflejen el contenido del tema</li>
                                    <li><strong>Código único:</strong> Si no especificas un código, se generará automáticamente</li>
                                    <li><strong>Descripción detallada:</strong> Ayuda a los usuarios a entender qué aprenderán</li>
                                    <li><strong>Estado de aprobación:</strong> Solo los temas aprobados aparecerán en los cursos</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('topicForm');
    const submitBtn = document.getElementById('submitBtn');
    const nameInput = document.getElementById('name');
    const codeInput = document.getElementById('code');
    
    // Auto-generate code based on name if code field is empty
    nameInput.addEventListener('input', function() {
        if (!codeInput.value) {
            const name = this.value;
            if (name.length > 0) {
                // Generate code from first 3 letters of each word
                const words = name.split(' ');
                let code = '';
                words.forEach(word => {
                    if (word.length >= 3) {
                        code += word.substring(0, 3).toUpperCase();
                    } else if (word.length > 0) {
                        code += word.toUpperCase();
                    }
                });
                // Limit to 10 characters
                codeInput.placeholder = `Sugerencia: ${code.substring(0, 10)}`;
            }
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        
        if (name.length < 3) {
            e.preventDefault();
            showAlert('error', 'El nombre del tema debe tener al menos 3 caracteres');
            nameInput.focus();
            return false;
        }
        
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creando...';
    });
});

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${icon} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insert alert at the top of the container
    const container = document.querySelector('.container-fluid');
    const firstChild = container.querySelector('.d-flex');
    firstChild.insertAdjacentHTML('beforebegin', alertHtml);
}
</script>
@endpush