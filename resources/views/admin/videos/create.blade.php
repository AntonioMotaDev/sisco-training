@extends('layouts.app')

@section('title', 'Agregar Video - SISCO Training')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">
        <div class="container-fluid px-4 py-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Agregar Nuevo Video</h1>
                    <p class="text-muted">Completa la información para agregar un video</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.dashboard') }}">Cursos</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('videos.index') }}">Videos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Agregar Video</li>
                    </ol>
                </nav>
            </div>

            <!-- Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Por favor corrige los siguientes errores:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Form Column -->
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary-blue text-white">
                            <h5 class="mb-0"><i class="fas fa-video me-2"></i>Información del Video</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('videos.store') }}" method="POST" id="videoForm">
                                @csrf
                                
                                <!-- Seleccionar Tema -->
                                <div class="mb-3">
                                    <label for="topic_id" class="form-label">Tema del Curso <span class="text-danger">*</span></label>
                                    <select class="form-select @error('topic_id') is-invalid @enderror" id="topic_id" name="topic_id" required>
                                        <option value="">Seleccionar tema...</option>
                                        @if(isset($topics) && $topics->count() > 0)
                                            @foreach($topics as $topic)
                                                <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                                                    [ {{ $topic->id }} ] - {{ $topic->name }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option disabled>No hay temas disponibles</option>
                                            <option disabled>
                                                <a href="{{ route('topics.create') }}" class="text-primary">➕ Crear nuevo tema</a>
                                            </option>
                                        @endif
                                    </select>
                                    @error('topic_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Selecciona el tema al que pertenece este video</div>
                                </div>

                                <!-- URL del Video de YouTube -->
                                <div class="mb-3">
                                    <label for="url" class="form-label">URL del Video de YouTube <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control @error('url') is-invalid @enderror" 
                                           id="url" name="url" value="{{ old('url') }}" required
                                           placeholder="https://www.youtube.com/watch?v=...">
                                    @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle text-info me-1"></i>
                                        Pega la URL (enlace) completa del video de YouTube. 
                                        Formatos admitidos: <code class="text-primary-blue">youtube.com/watch?v=...</code> o <code class="text-primary-blue">youtu.be/...</code>
                                    </div>
                                </div>

                                <!-- Nombre del Video -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre del Video <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required
                                           placeholder="Ej: Introducción a los métodos de control de plagas">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Proporciona un nombre descriptivo para el video</div>
                                </div>

                                <!-- Código del Video (Opcional) -->
                                <input type="hidden" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code') }}">
                                
                                <!-- Campo oculto para la duración total en segundos -->
                                <input type="hidden" name="length_seconds" id="length_seconds" value="{{ old('length_seconds', 0) }}">

                                <!-- Información de Duración (Solo lectura) -->
                                <div class="mb-3">
                                    <label class="form-label">Duración del Video</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        <input type="text" class="form-control" 
                                               id="duration_display" readonly
                                               placeholder="Se detectará automáticamente..."
                                               value="">
                                        <button type="button" class="btn btn-outline-info" id="fetchDurationBtn">
                                            <i class="fas fa-sync-alt me-1"></i>Obtener Duración
                                        </button>
                                    </div>
                                    <div class="form-text">
                                        <i class="fas fa-magic text-info me-1"></i>
                                        La duración se obtendrá automáticamente desde YouTube
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" id="clearFormBtn">
                                        <i class="fas fa-eraser me-2"></i>Limpiar Formulario
                                    </button>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('videos.index') }}" class="btn btn-danger">
                                            <i class="fas fa-times me-2"></i>Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Guardar Video
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Preview Column -->
                <div class="col-lg-4">
                    <!-- Vista previa del video -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-eye me-2"></i>Vista Previa</h6>
                        </div>
                        <div class="card-body" id="videoPreview">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-video fa-3x mb-3"></i>
                                <p class="mb-0">Ingresa una URL de YouTube para ver la vista previa</p>
                            </div>
                            <!-- Aqui se mostrara la informacion del video -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .video-preview-container {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        overflow: hidden;
        background: #000;
    }
    
    .video-preview-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    .alert-sm {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }

    .form-text {
        font-size: 0.875rem;
    }

    .code-preview {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.5rem;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlInput = document.getElementById('url');
    const nameInput = document.getElementById('name');
    const previewContainer = document.getElementById('videoPreview');
    const previewBtn = document.getElementById('previewBtn');
    const hoursInput = document.getElementById('hours');
    const minutesInput = document.getElementById('minutes');
    const secondsInput = document.getElementById('seconds');
    const lengthSecondsInput = document.getElementById('length_seconds');

    // Función para extraer el ID del video de YouTube
    function getYouTubeID(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    // Función para actualizar la vista previa
    function updatePreview() {
        const url = urlInput.value.trim();
        const codeInput = document.getElementById('code');
        
        if (!url) {
            previewContainer.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-video fa-3x mb-3"></i>
                    <p class="mb-0">Ingresa una URL de YouTube para ver la vista previa</p>
                </div>
            `;
            // Limpiar el código si no hay URL
            codeInput.value = '';
            clearDuration();
            return;
        }

        const videoId = getYouTubeID(url);
        if (videoId) {
            // Actualizar el campo code con el video ID
            codeInput.value = videoId;
            
            previewContainer.innerHTML = `
                <div class="video-preview-container">
                    <iframe src="https://www.youtube.com/embed/${videoId}" 
                            title="Vista previa del video"
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                    </iframe>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>ID del Video:</strong> 
                        <span class="code-preview">${videoId}</span>
                    </small>
                </div>
            `;
            
            // Obtener duración automáticamente
            fetchVideoDuration(videoId);
            
            // Si no hay nombre, intentar sugerir uno basado en la URL
            if (!nameInput.value.trim()) {
                nameInput.focus();
            }
        } else {
            // Limpiar el código si la URL no es válida
            codeInput.value = '';
            clearDuration();
            
            previewContainer.innerHTML = `
                <div class="text-center text-warning py-4">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <p class="mb-0">URL de YouTube no válida</p>
                    <small class="text-muted">Verifica que la URL sea correcta</small>
                </div>
            `;
        }
    }

    // Función para obtener la duración del video desde YouTube
    async function fetchVideoDuration(videoId) {
        const durationDisplay = document.getElementById('duration_display');
        const lengthSecondsInput = document.getElementById('length_seconds');
        const fetchBtn = document.getElementById('fetchDurationBtn');
        
        // Mostrar estado de carga
        durationDisplay.value = 'Obteniendo duración...';
        fetchBtn.disabled = true;
        fetchBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Obteniendo...';
        
        try {
            const response = await fetch('/youtube/video-info', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ video_id: videoId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                durationDisplay.value = data.formatted_duration;
                lengthSecondsInput.value = data.duration_seconds;
                durationDisplay.classList.add('text-success');
                
                // Si no hay nombre del video, sugerir el título de YouTube
                if (!nameInput.value.trim() && data.title) {
                    nameInput.value = data.title;
                    nameInput.classList.add('border-info');
                    setTimeout(() => {
                        nameInput.classList.remove('border-info');
                    }, 2000);
                }
            } else {
                durationDisplay.value = 'No disponible: ' + data.message;
                durationDisplay.classList.add('text-warning');
                lengthSecondsInput.value = 0;
            }
        } catch (error) {
            console.error('Error al obtener duración:', error);
            durationDisplay.value = 'Error al obtener duración';
            durationDisplay.classList.add('text-danger');
            lengthSecondsInput.value = 0;
        } finally {
            fetchBtn.disabled = false;
            fetchBtn.innerHTML = '<i class="fas fa-sync-alt me-1"></i>Obtener Duración';
        }
    }

    // Función para limpiar la duración
    function clearDuration() {
        document.getElementById('duration_display').value = '';
        document.getElementById('duration_display').className = 'form-control';
        document.getElementById('length_seconds').value = 0;
    }

    // Función para calcular duración total en segundos
    function updateDurationSeconds() {
        const hours = parseInt(hoursInput.value) || 0;
        const minutes = parseInt(minutesInput.value) || 0;
        const seconds = parseInt(secondsInput.value) || 0;
        
        const totalSeconds = (hours * 3600) + (minutes * 60) + seconds;
        lengthSecondsInput.value = totalSeconds;
    }

    // Event listeners
    urlInput.addEventListener('input', function() {
        // Actualizar automáticamente la vista previa cuando se escriba una URL
        clearTimeout(this.previewTimeout);
        this.previewTimeout = setTimeout(updatePreview, 500);
    });

    // Botón manual para obtener duración
    const fetchDurationBtn = document.getElementById('fetchDurationBtn');
    fetchDurationBtn.addEventListener('click', function() {
        const videoId = getYouTubeID(urlInput.value.trim());
        if (videoId) {
            fetchVideoDuration(videoId);
        } else {
            alert('Por favor, ingresa una URL de YouTube válida primero.');
        }
    });

    // Event listeners para la duración
    [hoursInput, minutesInput, secondsInput].forEach(input => {
        input.addEventListener('input', updateDurationSeconds);
        input.addEventListener('blur', function() {
            // Validar rangos
            const min = parseInt(this.getAttribute('min')) || 0;
            const max = parseInt(this.getAttribute('max')) || Infinity;
            let value = parseInt(this.value) || 0;
            
            if (value < min) value = min;
            if (value > max) value = max;
            
            this.value = value;
            updateDurationSeconds();
        });
    });

    // El código se genera automáticamente desde el video ID de YouTube
    // No se necesita lógica adicional ya que se maneja en updatePreview()

    // Validación del formulario
    document.getElementById('videoForm').addEventListener('submit', function(e) {
        const url = urlInput.value.trim();
        const name = nameInput.value.trim();
        const topicId = document.getElementById('topic_id').value;

        if (!url || !name || !topicId) {
            e.preventDefault();
            
            // Mostrar mensaje de error
            let errorMsg = 'Por favor, completa los siguientes campos obligatorios:\n';
            if (!topicId) errorMsg += '- Tema del curso\n';
            if (!url) errorMsg += '- URL del video\n';
            if (!name) errorMsg += '- Nombre del video\n';
            
            alert(errorMsg);
            return false;
        }

        // Validar que la URL sea de YouTube válida
        const videoId = getYouTubeID(url);
        if (!videoId) {
            e.preventDefault();
            alert('Por favor, ingresa una URL de YouTube válida.');
            urlInput.focus();
            return false;
        }

        // Actualizar duración antes de enviar
        updateDurationSeconds();
        
        return true;
    });

    // Funcionalidad del botón limpiar formulario
    const clearFormBtn = document.getElementById('clearFormBtn');
    clearFormBtn.addEventListener('click', function() {
        if (confirm('¿Estás seguro de que quieres limpiar todos los campos del formulario?')) {
            // Limpiar todos los campos
            document.getElementById('videoForm').reset();
            
            // Limpiar específicamente los campos ocultos
            document.getElementById('code').value = '';
            document.getElementById('length_seconds').value = 0;
            
            // Limpiar la duración
            clearDuration();
            
            // Limpiar la vista previa
            previewContainer.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-video fa-3x mb-3"></i>
                    <p class="mb-0">Ingresa una URL de YouTube para ver la vista previa</p>
                </div>
            `;
            
            // Focus en el primer campo
            document.getElementById('topic_id').focus();
        }
    });

    // Inicializar la duración si hay valores previos
    updateDurationSeconds();
    
    // Auto-focus en el primer campo vacío al cargar la página
    window.addEventListener('load', function() {
        const topicSelect = document.getElementById('topic_id');
        if (!topicSelect.value) {
            topicSelect.focus();
        } else if (!urlInput.value.trim()) {
            urlInput.focus();
        } else if (!nameInput.value.trim()) {
            nameInput.focus();
        }
    });
});
</script>
@endpush
