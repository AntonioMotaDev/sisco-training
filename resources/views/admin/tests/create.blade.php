@extends('layouts.app')

@section('title', 'Crear Cuestionario - SISCO Training')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">
        <div class="container-fluid px-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Crear cuestionario para el tema</h1>
                    <p class="text-muted mb-0">Tema: <span class="fw-bold">{{ $topic->name }}</span></p>
                </div>
                <a href="{{ route('admin.courses.show', $topic->courses->first()->id ?? 1) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver al curso
                </a>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.tests.store', $topic->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del cuestionario</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                        </div>
                        <hr>
                        <h5 class="mb-3">Preguntas</h5>
                        <div id="questions-list">
                            <!-- Aquí se agregan dinámicamente las preguntas -->
                        </div>
                        <button type="button" class="btn btn-outline-info mb-3" id="add-question-btn">
                            <i class="fas fa-plus me-1"></i>Agregar pregunta
                        </button>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Guardar cuestionario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let questionIndex = 0;
function questionTemplate(idx) {
    return `
    <div class="card mb-3 question-item">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold">Pregunta #${idx+1}</span>
                <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn"><i class="fas fa-trash"></i></button>
            </div>
            <div class="mb-2">
                <input type="text" class="form-control" name="questions[${idx}][text]" placeholder="Texto de la pregunta" required>
            </div>
            <div class="mb-2">
                <select class="form-select" name="questions[${idx}][type]">
                    <option value="single">Opción única</option>
                    <option value="multiple">Opción múltiple</option>
                    <option value="free">Respuesta libre</option>
                </select>
            </div>
            <div class="answers-list mb-2">
                <!-- Aquí se agregarán las respuestas -->
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm add-answer-btn">
                <i class="fas fa-plus"></i> Agregar respuesta
            </button>
        </div>
    </div>
    `;
}

function answerTemplate(qidx, aidx) {
    return `
    <div class="input-group mb-2 answer-item">
        <input type="text" class="form-control" name="questions[${qidx}][answers][${aidx}][text]" placeholder="Respuesta" required>
        <span class="input-group-text">
            <input type="checkbox" name="questions[${qidx}][answers][${aidx}][is_correct]" value="1" title="Correcta">
        </span>
        <button type="button" class="btn btn-outline-danger btn-sm remove-answer-btn"><i class="fas fa-trash"></i></button>
    </div>
    `;
}

document.addEventListener('DOMContentLoaded', function () {
    const questionsList = document.getElementById('questions-list');
    const addQuestionBtn = document.getElementById('add-question-btn');

    addQuestionBtn.addEventListener('click', function () {
        const qHtml = document.createElement('div');
        qHtml.innerHTML = questionTemplate(questionIndex);
        questionsList.appendChild(qHtml);
        addQuestionLogic(qHtml, questionIndex);
        questionIndex++;
    });

    function addQuestionLogic(qDiv, qidx) {
        // Add answer logic
        const answersList = qDiv.querySelector('.answers-list');
        const addAnswerBtn = qDiv.querySelector('.add-answer-btn');
        let answerIndex = 0;
        addAnswerBtn.addEventListener('click', function () {
            const aHtml = document.createElement('div');
            aHtml.innerHTML = answerTemplate(qidx, answerIndex);
            answersList.appendChild(aHtml);
            addAnswerLogic(aHtml);
            answerIndex++;
        });
        // Remove question
        qDiv.querySelector('.remove-question-btn').addEventListener('click', function () {
            qDiv.remove();
        });
    }
    function addAnswerLogic(aDiv) {
        aDiv.querySelector('.remove-answer-btn').addEventListener('click', function () {
            aDiv.remove();
        });
    }
});
</script>
@endpush
