@extends('layouts.app')

@section('title', 'Editar Curso - SISCO Training')

<link rel="stylesheet" href="{{ asset('css/course-edit.css') }}">

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">
        <div class="container-fluid px-4 py-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Editar Curso</h1>
                    <p class="text-muted">Modifica la información del curso y gestiona sus temas</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.dashboard') }}">Cursos</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.show', $course->id) }}">{{ $course->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
                    </ol>
                </nav>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-1"></i> Todos los cursos
                    </a>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" id="addTopicBtn" class="btn btn-outline-olive">
                        <i class="fas fa-plus me-1"></i> Agregar Tema
                    </button>
                    <button type="submit" form="editCourseForm" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </div>

            <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" id="editCourseForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Información Básica del Curso -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-primary-blue text-white">
                                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Información del Curso</h5>
                            </div>
                            <div class="card-body">
                                <!-- Nombre del Curso -->
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="fas fa-graduation-cap me-2 text-primary-blue"></i>
                                        Nombre del Curso <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $course->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Descripción del Curso -->
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-semibold">
                                        <i class="fas fa-align-left me-2 text-primary-blue"></i>
                                        Descripción
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4">{{ old('description', $course->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Estadísticas del Curso -->
                                <div class="mt-4">
                                    <h6 class="text-muted mb-3">Estadísticas</h6>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="bg-light p-3 rounded text-center">
                                                <div class="h4 mb-0 text-primary">{{ $course->topicsOrdered->count() }}</div>
                                                <small class="text-muted">Temas</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-light p-3 rounded text-center">
                                                <div class="h4 mb-0 text-success">{{ $course->topicsOrdered->sum(fn($topic) => $topic->videos->count()) }}</div>
                                                <small class="text-muted">Videos</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-light p-3 rounded text-center">
                                                <div class="h4 mb-0 text-info">{{ $course->getDuration() }}</div>
                                                <small class="text-muted">Minutos</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-light p-3 rounded text-center">
                                                <div class="h4 mb-0 text-warning">{{ $course->getStudentsCount() }}</div>
                                                <small class="text-muted">Estudiantes</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Temas -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Temas del Curso</h5>
                                <span class="badge bg-primary-blue">{{ $course->topicsOrdered->count() }} tema(s)</span>
                            </div>
                            <div class="card-body p-0">
                                <div id="topicsContainer">
                                    @foreach($course->topicsOrdered as $index => $topic)
                                        <div class="topic-item border-bottom" data-topic-index="{{ $index }}">
                                            <div class="p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <span class="badge bg-secondary me-2">{{ $index + 1 }}</span>
                                                            <h6 class="mb-0">Tema {{ $index + 1 }}</h6>
                                                            <button type="button" class="btn btn-link text-danger p-0 ms-auto remove-topic-btn" title="Eliminar tema">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- ID oculto del tema (para temas existentes) -->
                                                        <input type="hidden" name="topics[{{ $index }}][id]" value="{{ $topic->id }}">
                                                        
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Nombre del Tema <span class="text-danger">*</span></label>
                                                                <input type="text" 
                                                                       class="form-control" 
                                                                       name="topics[{{ $index }}][name]" 
                                                                       value="{{ old('topics.'.$index.'.name', $topic->name) }}" 
                                                                       required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Código</label>
                                                                <input type="text" 
                                                                       class="form-control" 
                                                                       name="topics[{{ $index }}][code]" 
                                                                       value="{{ old('topics.'.$index.'.code', $topic->code) }}"
                                                                       placeholder="Ej: TOP-001">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Descripción</label>
                                                            <textarea class="form-control" 
                                                                      name="topics[{{ $index }}][description]" 
                                                                      rows="2">{{ old('topics.'.$index.'.description', $topic->description) }}</textarea>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Orden en el Curso</label>
                                                                <input type="number" 
                                                                       class="form-control" 
                                                                       name="topics[{{ $index }}][order_in_course]" 
                                                                       value="{{ old('topics.'.$index.'.order_in_course', $topic->pivot->order_in_course ?? $index + 1) }}" 
                                                                       min="1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Videos del Tema -->
                                                <div class="border-top pt-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h6 class="mb-0 text-primary">
                                                            <i class="fas fa-video me-1"></i>
                                                            Videos del Tema
                                                        </h6>
                                                        <button type="button" class="btn btn-sm btn-outline-primary add-video-btn" data-topic-index="{{ $index }}">
                                                            <i class="fas fa-plus me-1"></i> Agregar Video
                                                        </button>
                                                    </div>
                                                    
                                                    <div class="videos-container" data-topic-index="{{ $index }}">
                                                        @forelse($topic->videos as $videoIndex => $video)
                                                            <div class="video-item bg-light p-3 rounded mb-2" data-video-index="{{ $videoIndex }}">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div class="flex-grow-1">
                                                                        <div class="row">
                                                                            <div class="col-md-6 mb-2">
                                                                                <label class="form-label small fw-semibold">Nombre del Video</label>
                                                                                <input type="text" 
                                                                                       class="form-control form-control-sm video-name-input" 
                                                                                       name="videos[{{ $topic->id }}][{{ $videoIndex }}][name]" 
                                                                                       value="{{ old('videos.'.$topic->id.'.'.$videoIndex.'.name', $video->name) }}"
                                                                                       placeholder="Nombre del video">
                                                                                <input type="hidden" name="videos[{{ $topic->id }}][{{ $videoIndex }}][id]" value="{{ $video->id }}">
                                                                            </div>
                                                                            <div class="col-md-6 mb-2">
                                                                                <label class="form-label small fw-semibold">URL del Video</label>
                                                                                <div class="input-group">
                                                                                    <input type="url" 
                                                                                           class="form-control form-control-sm video-url-input" 
                                                                                           name="videos[{{ $topic->id }}][{{ $videoIndex }}][url]" 
                                                                                           value="{{ old('videos.'.$topic->id.'.'.$videoIndex.'.url', $video->url) }}"
                                                                                           placeholder="https://youtube.com/watch?v=...">
                                                                                    <button type="button" class="btn btn-outline-info btn-sm fetch-video-info-btn" title="Obtener información automáticamente">
                                                                                        <i class="fas fa-magic"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6 mb-2">
                                                                                <label class="form-label small fw-semibold">Código</label>
                                                                                <input type="text" 
                                                                                       class="form-control form-control-sm video-code-input" 
                                                                                       name="videos[{{ $topic->id }}][{{ $videoIndex }}][code]" 
                                                                                       value="{{ old('videos.'.$topic->id.'.'.$videoIndex.'.code', $video->code) }}"
                                                                                       placeholder="VID-001">
                                                                            </div>
                                                                            <div class="col-md-6 mb-2">
                                                                                <label class="form-label small fw-semibold">Duración (segundos)</label>
                                                                                <div class="input-group">
                                                                                    <input type="number" 
                                                                                           class="form-control form-control-sm video-duration-input" 
                                                                                           name="videos[{{ $topic->id }}][{{ $videoIndex }}][length_seconds]" 
                                                                                           value="{{ old('videos.'.$topic->id.'.'.$videoIndex.'.length_seconds', $video->length_seconds) }}"
                                                                                           min="0">
                                                                                    <span class="input-group-text duration-display" title="Duración formateada">
                                                                                        <i class="fas fa-clock"></i>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" class="btn btn-link text-danger p-0 ms-2 remove-video-btn" title="Eliminar video">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="text-muted text-center py-3 no-videos-message">
                                                                <i class="fas fa-video-slash me-1"></i>
                                                                No hay videos agregados a este tema
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Mensaje cuando no hay temas -->
                                @if($course->topicsOrdered->count() === 0)
                                    <div class="text-center py-5" id="noTopicsMessage">
                                        <i class="fas fa-list-ul fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay temas en este curso</h5>
                                        <p class="text-muted">Agrega el primer tema haciendo clic en "Agregar Tema"</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Templates para elementos dinámicos -->
<template id="topicTemplate">
    <div class="topic-item border-bottom" data-topic-index="">
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-secondary me-2 topic-number"></span>
                        <h6 class="mb-0">Tema</h6>
                        <button type="button" class="btn btn-link text-danger p-0 ms-auto remove-topic-btn" title="Eliminar tema">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    
                    <!-- Selector de tipo de tema -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipo de Tema <span class="text-danger">*</span></label>
                        <select class="form-select topic-type-selector" required>
                            <option selected disabled value="">Seleccionar...</option>
                            <option value="new">Crear nuevo tema</option>
                            <option value="existing">Seleccionar tema existente</option>
                        </select>
                    </div>

                    <!-- Campos para tema existente -->
                    <div class="existing-topic-fields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Seleccionar Tema Existente <span class="text-danger">*</span></label>
                            <select class="form-select existing-topic-selector" name="topics[][existing_id]">
                                <option value="">Cargando temas...</option>
                            </select>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Se asociará el tema seleccionado a este curso
                            </div>
                        </div>
                    </div>

                    <!-- Campos para nuevo tema -->
                    <div class="new-topic-fields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nombre del Tema <span class="text-danger">*</span></label>
                                <input type="text" class="form-control topic-name-input" name="topics[][name]">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Código</label>
                                <input type="text" class="form-control topic-code-input" name="topics[][code]" placeholder="Ej: TOP-001">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea class="form-control topic-description-input" name="topics[][description]" rows="2"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Orden en el Curso</label>
                                <input type="number" class="form-control topic-order-input" name="topics[][order_in_course]" min="1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Videos del Tema -->
            <div class="border-top pt-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-primary-blue">
                        <i class="fas fa-video me-1"></i>
                        Videos del Tema
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-primary add-video-btn">
                        <i class="fas fa-plus me-1"></i> Agregar Video
                    </button>
                </div>
                
                <div class="videos-container">
                    <div class="text-muted text-center py-3 no-videos-message">
                        <i class="fas fa-video-slash me-1"></i>
                        No hay videos agregados a este tema
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="videoTemplate">
    <div class="video-item bg-light p-3 rounded mb-2" data-video-index="">
        <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-semibold">Nombre del Video</label>
                        <input type="text" class="form-control form-control-sm video-name-input" name="" placeholder="Nombre del video">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-semibold">URL del Video</label>
                        <div class="input-group">
                            <input type="url" class="form-control form-control-sm video-url-input" name="" placeholder="https://youtube.com/watch?v=...">
                            <button type="button" class="btn btn-outline-info btn-sm fetch-video-info-btn" title="Obtener información automáticamente">
                                <i class="fas fa-magic"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-semibold">Código</label>
                        <input type="text" class="form-control form-control-sm video-code-input" name="" placeholder="VID-001">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-semibold">Duración (segundos)</label>
                        <div class="input-group">
                            <input type="number" class="form-control form-control-sm video-duration-input" name="" min="0">
                            <span class="input-group-text duration-display" title="Duración formateada">
                                <i class="fas fa-clock"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-link text-danger p-0 ms-2 remove-video-btn" title="Eliminar video">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let topicCounter = {{ $course->topicsOrdered->count() }};
    let videoCounters = {};
    
    // Temas existentes para selección
    const existingTopics = @json($existingTopics);
    
    // Inicializar contadores de videos
    @foreach($course->topicsOrdered as $index => $topic)
        videoCounters[{{ $index }}] = {{ $topic->videos->count() }};
    @endforeach

    // === FUNCIONES PARA TEMAS ===
    
    // Función para poblar selector de temas existentes
    function populateExistingTopicsSelector(selector) {
        selector.innerHTML = '<option value="">Seleccionar tema...</option>';
        existingTopics.forEach(topic => {
            const option = document.createElement('option');
            option.value = topic.id;
            option.textContent = `[${topic.id}] ${topic.name}`;
            selector.appendChild(option);
        });
    }

    // Función para manejar cambio de tipo de tema
    function handleTopicTypeChange(topicItem) {
        const typeSelector = topicItem.querySelector('.topic-type-selector');
        const existingFields = topicItem.querySelector('.existing-topic-fields');
        const newFields = topicItem.querySelector('.new-topic-fields');
        const existingTopicSelector = topicItem.querySelector('.existing-topic-selector');
        
        typeSelector.addEventListener('change', function() {
            const value = this.value;
            
            if (value === 'existing') {
                existingFields.style.display = 'block';
                newFields.style.display = 'none';
                populateExistingTopicsSelector(existingTopicSelector);
                
                // Agregar indicador visual
                topicItem.setAttribute('data-topic-type', 'existing');
                
                // Remover required de campos nuevos
                newFields.querySelectorAll('input, textarea, select').forEach(input => {
                    input.removeAttribute('required');
                });
                
                // Agregar required al selector de tema existente
                existingTopicSelector.setAttribute('required', true);
                
            } else if (value === 'new') {
                existingFields.style.display = 'none';
                newFields.style.display = 'block';
                
                // Agregar indicador visual
                topicItem.setAttribute('data-topic-type', 'new');
                
                // Agregar required a campos nuevos
                const nameInput = newFields.querySelector('.topic-name-input');
                if (nameInput) nameInput.setAttribute('required', true);
                
                // Remover required del selector de tema existente
                existingTopicSelector.removeAttribute('required');
                
            } else {
                existingFields.style.display = 'none';
                newFields.style.display = 'none';
                
                // Remover indicador visual
                topicItem.removeAttribute('data-topic-type');
                
                // Remover required de todos los campos
                existingFields.querySelectorAll('input, textarea, select').forEach(input => {
                    input.removeAttribute('required');
                });
                newFields.querySelectorAll('input, textarea, select').forEach(input => {
                    input.removeAttribute('required');
                });
            }
        });
    }

    // Función para llenar automáticamente campos cuando se selecciona tema existente
    function handleExistingTopicSelection(topicItem) {
        const existingTopicSelector = topicItem.querySelector('.existing-topic-selector');
        
        existingTopicSelector.addEventListener('change', function() {
            const selectedTopicId = this.value;
            const selectedTopic = existingTopics.find(topic => topic.id == selectedTopicId);
            
            if (selectedTopic) {
                // Actualizar el título del tema con información del tema existente
                const topicTitle = topicItem.querySelector('h6');
                if (topicTitle) {
                    topicTitle.innerHTML = `Tema (Existente): ${selectedTopic.name}`;
                }
                
                // Mostrar información del tema seleccionado
                showNotification(`Tema seleccionado: ${selectedTopic.name}`, 'info');
            }
        });
    }

    // Agregar nuevo tema
    document.getElementById('addTopicBtn').addEventListener('click', function() {
        const template = document.getElementById('topicTemplate');
        const clone = template.content.cloneNode(true);
        const topicsContainer = document.getElementById('topicsContainer');
        
        // Actualizar atributos y nombres
        const topicItem = clone.querySelector('.topic-item');
        topicItem.setAttribute('data-topic-index', topicCounter);
        
        // Actualizar número del tema
        const topicNumber = clone.querySelector('.topic-number');
        topicNumber.textContent = topicCounter + 1;
        
        const topicTitle = clone.querySelector('h6');
        topicTitle.textContent = `Tema ${topicCounter + 1}`;
        
        // Actualizar nombres de los inputs
        const inputs = clone.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace('[]', `[${topicCounter}]`);
            }
        });
        
        // Actualizar checkbox
        const checkbox = clone.querySelector('input[type="checkbox"]');
        if (checkbox) {
            checkbox.id = `approved_${topicCounter}`;
            const label = clone.querySelector('label[for]');
            if (label) {
                label.setAttribute('for', `approved_${topicCounter}`);
            }
        }
        
        // Configurar orden por defecto
        const orderInput = clone.querySelector('input[name*="order_in_course"]');
        if (orderInput) {
            orderInput.value = topicCounter + 1;
        }
        
        // Configurar botón de agregar video
        const addVideoBtn = clone.querySelector('.add-video-btn');
        addVideoBtn.setAttribute('data-topic-index', topicCounter);
        
        // Configurar contenedor de videos
        const videosContainer = clone.querySelector('.videos-container');
        videosContainer.setAttribute('data-topic-index', topicCounter);
        
        // Inicializar contador de videos para este tema
        videoCounters[topicCounter] = 0;
        
        topicsContainer.appendChild(clone);
        
        // Agregar clase de animación
        const newTopicItem = topicsContainer.lastElementChild;
        newTopicItem.classList.add('newly-added');
        setTimeout(() => newTopicItem.classList.remove('newly-added'), 300);
        
        // Inicializar funcionalidad de selección de temas para el nuevo tema
        handleTopicTypeChange(newTopicItem);
        handleExistingTopicSelection(newTopicItem);
        
        // Ocultar mensaje de "no hay temas"
        const noTopicsMessage = document.getElementById('noTopicsMessage');
        if (noTopicsMessage) {
            noTopicsMessage.style.display = 'none';
        }
        
        topicCounter++;
        updateTopicNumbers();
    });

    // Eliminar tema
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-topic-btn')) {
            const topicItem = e.target.closest('.topic-item');
            if (confirm('¿Estás seguro de que quieres eliminar este tema y todos sus videos?')) {
                topicItem.remove();
                updateTopicNumbers();
                
                // Mostrar mensaje si no hay temas
                const remainingTopics = document.querySelectorAll('.topic-item');
                if (remainingTopics.length === 0) {
                    const noTopicsMessage = document.getElementById('noTopicsMessage');
                    if (noTopicsMessage) {
                        noTopicsMessage.style.display = 'block';
                    }
                }
            }
        }
    });

    // Agregar video
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-video-btn')) {
            const btn = e.target.closest('.add-video-btn');
            const topicIndex = btn.getAttribute('data-topic-index');
            const videosContainer = document.querySelector(`.videos-container[data-topic-index="${topicIndex}"]`);
            
            const template = document.getElementById('videoTemplate');
            const clone = template.content.cloneNode(true);
            
            // Inicializar contador si no existe
            if (!videoCounters[topicIndex]) {
                videoCounters[topicIndex] = 0;
            }
            
            const videoIndex = videoCounters[topicIndex];
            
            // Actualizar atributos del video
            const videoItem = clone.querySelector('.video-item');
            videoItem.setAttribute('data-video-index', videoIndex);
            
            // Actualizar nombres de los inputs
            const inputs = clone.querySelectorAll('input');
            inputs.forEach(input => {
                if (input.name === '') {
                    if (input.classList.contains('video-name-input')) {
                        input.name = `new_videos[${topicIndex}][${videoIndex}][name]`;
                    } else if (input.classList.contains('video-url-input')) {
                        input.name = `new_videos[${topicIndex}][${videoIndex}][url]`;
                    } else if (input.classList.contains('video-code-input')) {
                        input.name = `new_videos[${topicIndex}][${videoIndex}][code]`;
                    } else if (input.classList.contains('video-duration-input')) {
                        input.name = `new_videos[${topicIndex}][${videoIndex}][length_seconds]`;
                    }
                }
            });
            
            // Ocultar mensaje de "no videos"
            const noVideosMessage = videosContainer.querySelector('.no-videos-message');
            if (noVideosMessage) {
                noVideosMessage.style.display = 'none';
            }
            
            videosContainer.appendChild(clone);
            
            // Agregar clase de animación
            const newVideoItem = videosContainer.lastElementChild.previousElementSibling;
            if (newVideoItem && newVideoItem.classList.contains('video-item')) {
                newVideoItem.classList.add('newly-added');
                setTimeout(() => newVideoItem.classList.remove('newly-added'), 300);
            }
            
            videoCounters[topicIndex]++;
        }
    });

    // Eliminar video
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-video-btn')) {
            const videoItem = e.target.closest('.video-item');
            const videosContainer = videoItem.closest('.videos-container');
            
            if (confirm('¿Estás seguro de que quieres eliminar este video?')) {
                videoItem.remove();
                
                // Mostrar mensaje si no hay videos
                const remainingVideos = videosContainer.querySelectorAll('.video-item');
                if (remainingVideos.length === 0) {
                    const noVideosMessage = videosContainer.querySelector('.no-videos-message');
                    if (noVideosMessage) {
                        noVideosMessage.style.display = 'block';
                    }
                }
            }
        }
    });

    // Función para actualizar números de temas
    function updateTopicNumbers() {
        const topicItems = document.querySelectorAll('.topic-item');
        topicItems.forEach((item, index) => {
            const number = item.querySelector('.topic-number');
            const title = item.querySelector('h6');
            const orderInput = item.querySelector('input[name*="order_in_course"]');
            
            if (number) number.textContent = index + 1;
            if (title) title.textContent = `Tema ${index + 1}`;
            if (orderInput && !orderInput.value) orderInput.value = index + 1;
            
            // Actualizar data-topic-index
            item.setAttribute('data-topic-index', index);
            
            // Actualizar nombres de inputs
            const inputs = item.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                if (input.name && input.name.includes('topics[')) {
                    input.name = input.name.replace(/topics\[\d+\]/, `topics[${index}]`);
                }
            });
            
            // Actualizar id y for del checkbox
            const checkbox = item.querySelector('input[type="checkbox"][name*="is_approved"]');
            const label = item.querySelector('label[for*="approved_"]');
            if (checkbox && label) {
                checkbox.id = `approved_${index}`;
                label.setAttribute('for', `approved_${index}`);
            }
        });
    }

    // Validación del formulario
    document.getElementById('editCourseForm').addEventListener('submit', function(e) {
        let hasErrors = false;
        const errorMessages = [];
        
        // Validar cada tema
        document.querySelectorAll('.topic-item').forEach((topicItem, index) => {
            const typeSelector = topicItem.querySelector('.topic-type-selector');
            const existingTopicSelector = topicItem.querySelector('.existing-topic-selector');
            const nameInput = topicItem.querySelector('.topic-name-input');
            
            if (!typeSelector.value) {
                hasErrors = true;
                errorMessages.push(`Tema ${index + 1}: Debe seleccionar el tipo de tema`);
                typeSelector.classList.add('is-invalid');
            } else {
                typeSelector.classList.remove('is-invalid');
                
                if (typeSelector.value === 'existing') {
                    if (!existingTopicSelector.value) {
                        hasErrors = true;
                        errorMessages.push(`Tema ${index + 1}: Debe seleccionar un tema existente`);
                        existingTopicSelector.classList.add('is-invalid');
                    } else {
                        existingTopicSelector.classList.remove('is-invalid');
                    }
                } else if (typeSelector.value === 'new') {
                    if (!nameInput.value.trim()) {
                        hasErrors = true;
                        errorMessages.push(`Tema ${index + 1}: El nombre del nuevo tema es obligatorio`);
                        nameInput.classList.add('is-invalid');
                    } else {
                        nameInput.classList.remove('is-invalid');
                    }
                }
            }
        });
        
        if (hasErrors) {
            e.preventDefault();
            const errorText = errorMessages.join('\n• ');
            alert('Por favor corrige los siguientes errores:\n• ' + errorText);
            return false;
        }
        
        // Confirmar si hay muchos cambios
        const totalTopics = document.querySelectorAll('.topic-item').length;
        const totalVideos = document.querySelectorAll('.video-item').length;
        
        if (totalTopics > 5 || totalVideos > 10) {
            if (!confirm('Estás a punto de guardar un curso con muchos elementos. ¿Estás seguro de continuar?')) {
                e.preventDefault();
                return false;
            }
        }
        
        // Mostrar indicador de carga
        const submitBtn = document.querySelector('button[type="submit"][form="editCourseForm"]');
        if (submitBtn) {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        }
    });

    // === FUNCIONES DE YOUTUBE ===
    
    // Función para extraer el ID del video de YouTube
    function getYouTubeID(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    // Función para formatear duración en segundos a mm:ss o hh:mm:ss
    function formatDuration(seconds) {
        if (!seconds || seconds <= 0) return '';
        
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        
        if (hours > 0) {
            return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        } else {
            return `${minutes}:${secs.toString().padStart(2, '0')}`;
        }
    }

    // Función para obtener información del video desde YouTube API
    async function fetchVideoInfo(videoId, videoItem) {
        const nameInput = videoItem.querySelector('.video-name-input');
        const codeInput = videoItem.querySelector('.video-code-input');
        const durationInput = videoItem.querySelector('.video-duration-input');
        const durationDisplay = videoItem.querySelector('.duration-display');
        const fetchBtn = videoItem.querySelector('.fetch-video-info-btn');
        
        // Mostrar estado de carga
        fetchBtn.disabled = true;
        fetchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
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
                // Rellenar automáticamente los campos
                if (!nameInput.value.trim() && data.title) {
                    nameInput.value = data.title;
                    nameInput.classList.add('border-success');
                    setTimeout(() => nameInput.classList.remove('border-success'), 2000);
                }
                
                if (!codeInput.value.trim()) {
                    codeInput.value = videoId;
                    codeInput.classList.add('border-success');
                    setTimeout(() => codeInput.classList.remove('border-success'), 2000);
                }
                
                if (data.duration_seconds) {
                    durationInput.value = data.duration_seconds;
                    durationDisplay.innerHTML = formatDuration(data.duration_seconds);
                    durationInput.classList.add('border-success');
                    setTimeout(() => durationInput.classList.remove('border-success'), 2000);
                } else {
                    durationDisplay.innerHTML = '<i class="fas fa-clock"></i>';
                }
                
                showNotification('Información obtenida exitosamente', 'success');
            } else {
                showNotification('Error: ' + data.message, 'warning');
                durationDisplay.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i>';
            }
        } catch (error) {
            console.error('Error al obtener información del video:', error);
            showNotification('Error al conectar con YouTube API', 'danger');
            durationDisplay.innerHTML = '<i class="fas fa-times text-danger"></i>';
        } finally {
            fetchBtn.disabled = false;
            fetchBtn.innerHTML = '<i class="fas fa-magic"></i>';
        }
    }

    // Event listener para botones de obtener información
    document.addEventListener('click', function(e) {
        if (e.target.closest('.fetch-video-info-btn')) {
            const btn = e.target.closest('.fetch-video-info-btn');
            const videoItem = btn.closest('.video-item');
            const urlInput = videoItem.querySelector('.video-url-input');
            const url = urlInput.value.trim();
            
            if (!url) {
                showNotification('Por favor, ingresa una URL de YouTube primero', 'warning');
                urlInput.focus();
                return;
            }
            
            const videoId = getYouTubeID(url);
            if (!videoId) {
                showNotification('URL de YouTube no válida', 'danger');
                urlInput.focus();
                return;
            }
            
            fetchVideoInfo(videoId, videoItem);
        }
    });

    // Auto-obtener información cuando se ingresa una URL válida
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('video-url-input')) {
            const urlInput = e.target;
            const videoItem = urlInput.closest('.video-item');
            const codeInput = videoItem.querySelector('.video-code-input');
            
            // Limpiar timeout anterior
            if (urlInput.autoFetchTimeout) {
                clearTimeout(urlInput.autoFetchTimeout);
            }
            
            // Auto-llenar código con video ID
            const url = urlInput.value.trim();
            if (url) {
                const videoId = getYouTubeID(url);
                if (videoId && !codeInput.value.trim()) {
                    codeInput.value = videoId;
                }
                
                // Auto-obtener información después de 2 segundos de inactividad
                urlInput.autoFetchTimeout = setTimeout(() => {
                    if (videoId) {
                        fetchVideoInfo(videoId, videoItem);
                    }
                }, 2000);
            } else {
                // Limpiar código si no hay URL
                if (!codeInput.value.trim() || codeInput.value === codeInput.placeholder) {
                    codeInput.value = '';
                }
            }
        }
    });

    // Actualizar displays de duración cuando cambie el input
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('video-duration-input')) {
            const durationInput = e.target;
            const videoItem = durationInput.closest('.video-item');
            const durationDisplay = videoItem.querySelector('.duration-display');
            
            const seconds = parseInt(durationInput.value) || 0;
            if (seconds > 0) {
                durationDisplay.innerHTML = formatDuration(seconds);
            } else {
                durationDisplay.innerHTML = '<i class="fas fa-clock"></i>';
            }
        }
    });

    // Inicializar displays de duración para videos existentes
    document.querySelectorAll('.video-duration-input').forEach(input => {
        const seconds = parseInt(input.value) || 0;
        if (seconds > 0) {
            const videoItem = input.closest('.video-item');
            const durationDisplay = videoItem.querySelector('.duration-display');
            if (durationDisplay) {
                durationDisplay.innerHTML = formatDuration(seconds);
            }
        }
    });

    // === FIN FUNCIONES DE YOUTUBE ===

    // Función para mostrar notificaciones (opcional)
    function showNotification(message, type = 'info') {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
});
</script>
@endsection