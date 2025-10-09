@extends('layouts.app')

@section('title', 'Crear Curso - Paso 3 - SISCO Training')

@section('content')
    <div class="admin-layout">
        {{-- @include('admin.navigation') --}}
        <div class="">
            <div class="container-fluid px-4 py-4">
                <!-- Header with Progress -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">Crear Nuevo Curso</h1>
                        <p class="text-muted">Paso 3 de 3 - Cuestionarios (Opcional)</p>
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
                        <span class="badge bg-success">Paso 2 ✓</span>
                        <span class="badge bg-primary-blue">Paso 3</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-success fw-bold">Información del Curso</small>
                        <small class="text-success fw-bold">Crear Temas</small>
                        <small class="text-primary-blue fw-bold">Cuestionarios (Opcional)</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Course Summary Sidebar -->
                    <div class="col-lg-4">
                        <!-- Course Info -->
                        <div class="card mb-3">
                            <div class="card-header bg-primary-blue text-white">
                                <h6 class="mb-0"><i class="fas fa-book me-2"></i>Curso</h6>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-2 text-olive">{{ $courseData['name'] }}</h6>
                                <p class="text-muted small mb-0">{{ $courseData['description'] ?: 'Sin descripción' }}</p>
                            </div>
                        </div>

                        <!-- Topics Summary -->
                        <div class="card mb-3">
                            <div class="card-header bg-primary-blue text-white">
                                <h6 class="mb-0"><i class="fas fa-list-ul me-2"></i>Temas ({{ count($topicsData) }})</h6>
                            </div>
                            <div class="card-body p-2">
                                @foreach ($topicsData as $index => $topic)
                                    <div class="d-flex align-items-center p-2 border-bottom">
                                        <span class="badge bg-primary-blue me-2">{{ $index + 1 }}</span>
                                        <div>
                                            <div class="fw-semibold small text-olive">{{ $topic['name'] }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $topic['code'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="card border-info">
                            <div class="card-body">
                                <h6 class="text-info mb-3">
                                    <i class="fas fa-question-circle me-1"></i>
                                    Sobre los Cuestionarios
                                </h6>
                                <ul class="list-unstyled mb-0 small">
                                    <li class="mb-2">
                                        <i class="fas fa-info text-info me-2"></i>
                                        Los cuestionarios son opcionales
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-info text-info me-2"></i>
                                        Puedes agregar preguntas simples
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-info text-info me-2"></i>
                                        Puedes crear cuestionarios más tarde
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-info text-info me-2"></i>
                                        También puedes omitir este paso
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tests Configuration -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary-blue text-white">
                                <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Selecciona Cuestionarios Existentes</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.courses.create.finish') }}" method="POST" id="step3Form">
                                    @csrf
                                    <div class="alert alert-info mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="skipTests" checked>
                                            <label class="form-check-label fw-semibold" for="skipTests">
                                                <i class="fas fa-fast-forward me-2"></i>
                                                Omitir selección de cuestionarios por ahora (podrás agregarlos más tarde)
                                            </label>
                                        </div>
                                    </div>
                                    <div id="testsContainer" style="display: none;">
                                        @foreach ($topicsData as $index => $topic)
                                            <div class="topic-tests mb-4 p-3 border rounded">
                                                <div class="d-flex align-items-center mb-3">
                                                    <span class="badge bg-primary-blue me-2">{{ $index + 1 }}</span>
                                                    <h6 class="mb-0 me-auto">{{ $topic['name'] }}</h6>
                                                </div>
                                                <div class="tests-list" data-topic-index="{{ $index }}">
                                                    @php $tests = $testsByTopicName[$topic['name']] ?? collect(); @endphp
                                                    @if($tests->count())
                                                        <div class="mb-2">Selecciona uno o más cuestionarios para este tema:</div>
                                                        @foreach($tests as $test)
                                                            <div class="form-check mb-1">
                                                                <input class="form-check-input" type="checkbox" name="topic_{{ $index }}_tests[]" value="{{ $test->id }}" id="test_{{ $index }}_{{ $test->id }}">
                                                                <label class="form-check-label" for="test_{{ $index }}_{{ $test->id }}">
                                                                    <strong>{{ $test->name }}</strong>
                                                                    @if($test->description)
                                                                        <span class="text-muted small"> - {{ $test->description }}</span>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="alert alert-warning mb-0">
                                                            No hay cuestionarios existentes para este tema.<br>
                                                            Puedes crearlos después de finalizar el curso.
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr class="my-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('admin.courses.create.step2') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>
                                            Paso Anterior
                                        </a>
                                        <button type="submit" class="btn btn-success btn-lg px-5">
                                            <i class="fas fa-check me-2"></i>
                                            Finalizar y Crear Curso
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

    <!-- Test Template -->
    <template id="testTemplate">
        <div class="test-item mb-3 p-3 border rounded bg-light">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <div class="row mb-2">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Nombre del Cuestionario</label>
                            <input type="text" class="form-control form-control-sm test-name" placeholder="Ej: Evaluación del tema">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Descripción</label>
                            <input type="text" class="form-control form-control-sm test-description" placeholder="Opcional">
                        </div>
                    </div>
                    
                    <div class="questions-container">
                        <!-- Questions will be added here -->
                    </div>
                    
                    <button type="button" class="btn btn-sm btn-outline-secondary add-question-btn">
                        <i class="fas fa-plus me-1"></i>
                        Agregar Pregunta
                    </button>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger remove-test-btn ms-2" title="Eliminar cuestionario">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </template>

    <!-- Question Template -->
    <template id="questionTemplate">
        <div class="question-item mb-2 p-2 border rounded bg-white">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1 me-2">
                    <input type="text" class="form-control form-control-sm question-text" placeholder="Escribe tu pregunta aquí...">
                </div>
                <select class="form-select form-select-sm me-2" style="width: auto;">
                    <option value="multiple_choice">Opción múltiple</option>
                    <option value="true_false">Verdadero/Falso</option>
                    <option value="short_answer">Respuesta corta</option>
                </select>
                <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn" title="Eliminar pregunta">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const skipTestsCheckbox = document.getElementById('skipTests');
    const testsContainer = document.getElementById('testsContainer');
    skipTestsCheckbox.addEventListener('change', function() {
        testsContainer.style.display = this.checked ? 'none' : 'block';
    });
});
</script>
@endsection