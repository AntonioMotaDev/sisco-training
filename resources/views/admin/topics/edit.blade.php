@extends('layouts.app')

@section('title', 'Editar Tema - SISCO Training')

@section('content')
    <div class="admin-layout">
        @include('admin.navigation')
        <div class="admin-content">
            <div class="container-fluid px-4 py-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">Editar Tema</h1>
                        <p class="text-muted">Modifica la información del tema "{{ $topic->name }}"</p>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.courses.dashboard') }}">Cursos</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('topics.index') }}">Temas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Editar</li>
                        </ol>
                    </nav>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">
                                    <i class="fas fa-edit me-2"></i>Editar Tema: {{ $topic->name }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('topics.update', $topic) }}" method="POST" id="topicForm">
                                    @csrf
                                    @method('PUT')
                                    
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
                                            value="{{ old('name', $topic->name) }}" 
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
                                            value="{{ old('code', $topic->code) }}" 
                                            placeholder="Ej: FDP"
                                            maxlength="10"
                                        >
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            El código debe ser único en el sistema (máximo 10 caracteres)
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
                                        >{{ old('description', $topic->description) }}</textarea>
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
                                                {{ old('is_approved', $topic->is_approved) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label fw-medium" for="is_approved">
                                                Tema aprobado
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
                                        <a href="{{ route('topics.show', $topic) }}" class="btn btn-info">
                                            <i class="fas fa-eye me-2"></i>Ver Detalles
                                        </a>
                                        <button type="submit" class="btn btn-warning" id="submitBtn">
                                            <i class="fas fa-save me-2"></i>Actualizar Tema
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Topic Statistics -->
                        <div class="card mt-4 border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>Estadísticas del Tema
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <div class="border-end">
                                            <h4 class="text-primary">{{ $topic->courses->count() }}</h4>
                                            <small class="text-muted">Cursos Asociados</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-end">
                                            <h4 class="text-secondary">{{ $topic->videos->count() }}</h4>
                                            <small class="text-muted">Videos</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h4 class="text-success">{{ $topic->created_at->diffForHumans() }}</h4>
                                        <small class="text-muted">Creado</small>
                                    </div>
                                </div>
                                
                                @if($topic->courses->count() > 0 || $topic->videos->count() > 0)
                                    <div class="alert alert-warning mt-3 mb-0">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Atención:</strong> Este tema tiene contenido asociado. Los cambios pueden afectar a {{ $topic->courses->count() }} curso(s) y {{ $topic->videos->count() }} video(s).
                                    </div>
                                @endif
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
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...';
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