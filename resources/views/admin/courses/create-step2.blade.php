@extends('layouts.app')

@section('title', 'Crear Curso - Paso 2 - SISCO Training')

@section('content')
    <div class="admin-layout">
        {{-- @include('admin.navigation') --}}
        <div class="">
            <div class="container-fluid px-4 py-4">
                <!-- Header with Progress -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">Crear Nuevo Curso</h1>
                        <p class="text-muted">Paso 2 de 3 - Crear temas del curso</p>
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
                        <span class="badge bg-success">Paso 1 ✓</span>
                        <span class="badge bg-primary-blue">Paso 2</span>
                        <span class="badge bg-secondary">Paso 3</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary-blue" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-success fw-bold">Información del Curso</small>
                        <small class="text-primary-blue fw-bold">Crear Temas</small>
                        <small class="text-muted">Cuestionarios (Opcional)</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Course Info Sidebar -->
                    <div class="col-lg-4">
                        <div class="card mb-4 ">
                            <div class="card-header bg-primary-blue text-white">
                                <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Información del Curso</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-2 text-olive">{{ $courseData['name'] }}</h5>
                                <p class="text-muted mb-0">{{ $courseData['description'] ?: 'Sin descripción' }}</p>
                            </div>
                        </div>

                        <!-- Tips Card -->
                        <div class="card border-info">
                            <div class="card-body">
                                <h6 class="text-info mb-3">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Consejos para crear temas
                                </h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <small>Organiza el contenido de forma lógica</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <small>Usa nombres descriptivos y claros</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <small>El orden se asigna automáticamente</small>
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <small>Los códigos se generan automáticamente</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Topics Form -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary-blue text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-list-ul me-2"></i>Temas del Curso</h5>
                                <span class="badge bg-light text-dark" id="topicCount">0 temas</span>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.courses.create.step2.store') }}" method="POST" id="step2Form">
                                    @csrf
                                    
                                    <div id="topicsContainer">
                                        <!-- Topics will be added here dynamically -->
                                    </div>

                                    <!-- Add Topic Button -->
                                    <div class="text-center mb-4">
                                        <button type="button" class="btn btn-outline-primary btn-lg" id="addTopicBtn">
                                            <i class="fas fa-plus me-2"></i>
                                            Agregar Tema
                                        </button>
                                        <p class="text-muted mt-2 mb-0">
                                            <small>Agrega al menos un tema para continuar</small>
                                        </p>
                                    </div>

                                    <!-- Form Actions -->
                                    <hr class="my-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>
                                            Paso Anterior
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-lg px-4" id="nextStepBtn" disabled>
                                            Siguiente Paso
                                            <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Topic Template -->
    <template id="topicTemplate">
        <div class="topic-item mb-3 p-3 border rounded bg-light position-relative" data-topic-index="">
            <div class="d-flex align-items-start">
                <div class="topic-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                    <span class="fw-bold"></span>
                </div>
                <div class="flex-grow-1">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tema <span class="text-danger">*</span></label>
                            <select class="form-select topic-select mb-2">
                                <option value="">-- Selecciona un tema existente o crea uno nuevo --</option>
                                @foreach($existingTopics as $existingTopic)
                                    <option value="{{ $existingTopic->id }}" data-description="{{ $existingTopic->description }}">{{ $existingTopic->name }}</option>
                                @endforeach
                                <option value="__nuevo__">Crear tema nuevo...</option>
                            </select>
                            <input type="text" class="form-control topic-name mt-2 d-none" name="topics[][name]" placeholder="Nombre del nuevo tema">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea class="form-control topic-description" name="topics[][description]" rows="2" placeholder="Breve descripción del tema"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-sort-numeric-up me-1"></i>
                                Orden: <span class="topic-order"></span>
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-code me-1"></i>
                                Código: <span class="topic-code">Se generará automáticamente</span>
                            </small>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger remove-topic-btn" title="Eliminar tema">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const topicsContainer = document.getElementById('topicsContainer');
    const addTopicBtn = document.getElementById('addTopicBtn');
    const topicTemplate = document.getElementById('topicTemplate');
    const topicCountBadge = document.getElementById('topicCount');
    const nextStepBtn = document.getElementById('nextStepBtn');
    const form = document.getElementById('step2Form');
    let topicCounter = 0;

    // Add initial topic
    addTopic();
    addTopicBtn.addEventListener('click', addTopic);

    function addTopic() {
        topicCounter++;
        const template = topicTemplate.content.cloneNode(true);
        const topicItem = template.querySelector('.topic-item');
        topicItem.setAttribute('data-topic-index', topicCounter);
        // Update topic number
        const topicNumber = template.querySelector('.topic-number span');
        topicNumber.textContent = topicCounter;
        // Update order display
        const topicOrder = template.querySelector('.topic-order');
        topicOrder.textContent = topicCounter;

        // Select, name and description inputs
        const select = template.querySelector('.topic-select');
        const nameInput = template.querySelector('.topic-name');
        const descriptionInput = template.querySelector('.topic-description');

        // Update input names with proper indexing
        nameInput.name = `topics[${topicCounter-1}][name]`;
        descriptionInput.name = `topics[${topicCounter-1}][description]`;

        // Show/hide name input depending on select
        select.addEventListener('change', function() {
            if (this.value === '__nuevo__') {
                nameInput.classList.remove('d-none');
                nameInput.value = '';
                descriptionInput.value = '';
                descriptionInput.readOnly = false;
                nameInput.required = true;
            } else if (this.value) {
                nameInput.classList.add('d-none');
                // Buscar el texto y descripción del option
                const selectedOption = this.options[this.selectedIndex];
                nameInput.value = selectedOption.text;
                descriptionInput.value = selectedOption.getAttribute('data-description') || '';
                descriptionInput.readOnly = true;
                nameInput.required = false;
            } else {
                nameInput.classList.add('d-none');
                nameInput.value = '';
                descriptionInput.value = '';
                descriptionInput.readOnly = false;
                nameInput.required = false;
            }
            updateNextButton();
        });

        // Add event listeners
        const removeBtn = template.querySelector('.remove-topic-btn');
        removeBtn.addEventListener('click', () => removeTopic(topicItem));
        nameInput.addEventListener('input', (e) => updateTopicCode(e.target));
        topicsContainer.appendChild(template);
        updateTopicCount();
        updateNextButton();
        // Focus on the select
        select.focus();
    }

    function removeTopic(topicItem) {
        if (topicsContainer.children.length <= 1) {
            alert('Debe haber al menos un tema en el curso.');
            return;
        }
        topicItem.remove();
        reorderTopics();
        updateTopicCount();
        updateNextButton();
    }

    function reorderTopics() {
        const topics = topicsContainer.querySelectorAll('.topic-item');
        topics.forEach((topic, index) => {
            const newOrder = index + 1;
            topic.querySelector('.topic-number span').textContent = newOrder;
            topic.querySelector('.topic-order').textContent = newOrder;
            const nameInput = topic.querySelector('.topic-name');
            const descriptionInput = topic.querySelector('.topic-description');
            nameInput.name = `topics[${index}][name]`;
            descriptionInput.name = `topics[${index}][description]`;
            topic.setAttribute('data-topic-index', newOrder);
        });
    }

    function updateTopicCode(nameInput) {
        const topicItem = nameInput.closest('.topic-item');
        const codeSpan = topicItem.querySelector('.topic-code');
        const name = nameInput.value.trim();
        if (name) {
            const code = name.toUpperCase().replace(/[^A-Z\s]/g, '').split(' ').slice(0, 3).join('').substring(0, 3);
            codeSpan.textContent = code ? `${code}-XXX` : 'Se generará automáticamente';
        } else {
            codeSpan.textContent = 'Se generará automáticamente';
        }
    }

    function updateTopicCount() {
        const count = topicsContainer.children.length;
        topicCountBadge.textContent = `${count} tema${count !== 1 ? 's' : ''}`;
    }

    function updateNextButton() {
        const topics = topicsContainer.querySelectorAll('.topic-item');
        let hasValidTopics = false;
        topics.forEach(topic => {
            const select = topic.querySelector('.topic-select');
            const nameInput = topic.querySelector('.topic-name');
            if ((select.value && select.value !== '__nuevo__') || (select.value === '__nuevo__' && nameInput.value.trim())) {
                hasValidTopics = true;
            }
        });
        nextStepBtn.disabled = !hasValidTopics || topics.length === 0;
    }

    topicsContainer.addEventListener('input', updateNextButton);
    form.addEventListener('submit', function(e) {
        const topics = topicsContainer.querySelectorAll('.topic-item');
        let hasEmpty = false;
        topics.forEach(topic => {
            const select = topic.querySelector('.topic-select');
            const nameInput = topic.querySelector('.topic-name');
            if (select.value === '__nuevo__' && !nameInput.value.trim()) {
                nameInput.classList.add('is-invalid');
                hasEmpty = true;
            } else {
                nameInput.classList.remove('is-invalid');
            }
        });
        if (hasEmpty) {
            e.preventDefault();
            alert('Por favor, completa el nombre de todos los temas nuevos antes de continuar.');
        }
    });
});
</script>
@endsection