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
            
            <form action="{{ route('admin.tests.store', $topic->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <div class="card">
                        <div class="card-body">
                        <div class="mb-3">
                            {{-- <label for="name" class="form-label">Nombre del cuestionario</label> --}}
                            <input type="text" class="form-control text-olive fs-4" id="name" name="name" placeholder="Nombre del cuestionario" required>
                        </div>
                        <div class="mb-3">
                            {{-- <label for="description" class="form-label">Descripción</label> --}}
                            <textarea class="form-control" id="description" name="description" rows="2" placeholder="Descripción del cuestionario"></textarea>
                        </div>
                        <div class="mb-3 w-50">
                            <label for="minimum_approved_grade" class="form-label">Calificación mínima aprobatoria</label>
                            <input type="number" min="0" step="1.0" class="form-control" id="minimum_approved_grade" name="minimum_approved_grade" placeholder="Ejemplo: 70.00" required>
                        </div>
                    </div>

                    </div>
                    
                    <hr>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3 text-olive">Preguntas</h4>
                            <div id="questions-list">
                                <!-- Aquí se agregan dinámicamente las preguntas -->
                            </div>
                            <div class="row text-center">
                                <button type="button" class="btn btn-outline mb-3" id="add-question-btn">
                                    <i class="fas fa-plus me-1"></i>Agregar pregunta
                                </button>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Guardar cuestionario
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>

function questionTemplate() {
    return `
    <div class="mb-3 question-item border-0">
        <hr>
        <div class="container p-3 rounded bg-light shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold question-number"></span>
                <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn"><i class="fas fa-trash"></i></button>
            </div>
            <div class="row mb-2">
                <div class="col-md-6 mb-2 mb-md-0">
                    <input type="text" class="form-control question-text" placeholder="Texto de la pregunta" required>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <input type="number" min="0" step="0.01" class="form-control question-score" placeholder="Valor" required>
                </div>
                <div class="col-md-3">
                    <select class="form-select question-type-select">
                        <option value="single_choice">Opción única</option>
                        <option value="multiple_choice">Opción múltiple</option>
                        <option value="free_text">Respuesta libre</option>
                    </select>
                </div>
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

function answerTemplate(type = 'multiple_choice', qidx = 0) {
    // type: 'single_choice' o 'multiple_choice'
    const inputType = type === 'single_choice' ? 'radio' : 'checkbox';
    // Para radio, el name debe ser igual para todas las respuestas de la misma pregunta
    const nameAttr = type === 'single_choice' ? `name="questions[${qidx}][correct]"` : '';
    return `
    <div class="input-group mb-2 answer-item">
        <input type="text" class="form-control answer-text" placeholder="Respuesta" required>
        <span class="input-group-text">
            <input type="${inputType}" class="answer-correct" value="1" title="Correcta" ${nameAttr} autocomplete="off">
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
        qHtml.innerHTML = questionTemplate();
        questionsList.appendChild(qHtml);
        addQuestionLogic(qHtml);
        renumberQuestions();
    });

    function addQuestionLogic(qDiv) {
        // Add answer logic
        const answersList = qDiv.querySelector('.answers-list');
        const addAnswerBtn = qDiv.querySelector('.add-answer-btn');
        const typeSelect = qDiv.querySelector('.question-type-select');
        let answerType = typeSelect.value;

        // Mensaje de ayuda para marcar respuesta correcta
        let helpMsg = qDiv.querySelector('.answer-help-msg');
        if (!helpMsg) {
            helpMsg = document.createElement('div');
            helpMsg.className = 'answer-help-msg text-muted mb-2 text-muted text-center';
            helpMsg.style.fontSize = '0.95em';
            answersList.parentNode.insertBefore(helpMsg, answersList);
        }

        function setHelpMsg() {
            if (typeSelect.value === 'single_choice' || typeSelect.value === 'multiple_choice') {
                helpMsg.textContent = 'Para elegir la(s) respuesta(s) correcta(s), márcala(s) con el círculo o la casilla.';
                helpMsg.style.display = '';
            } else {
                helpMsg.textContent = '';
                helpMsg.style.display = 'none';
            }
        }

        // Agrega 3 respuestas por defecto si es opción única o múltiple
        function addDefaultAnswers() {
            answersList.innerHTML = '';
            for (let i = 0; i < 3; i++) {
                const aHtml = document.createElement('div');
                aHtml.innerHTML = answerTemplate(typeSelect.value, getQuestionIndex(qDiv));
                answersList.appendChild(aHtml);
                addAnswerLogic(aHtml);
            }
        }

        // Cambia el tipo de input de las respuestas según el tipo de pregunta
        function updateAnswerInputs() {
            const answerInputs = answersList.querySelectorAll('.answer-correct');
            if (typeSelect.value === 'single_choice') {
                // Todos los radios deben tener el mismo name por pregunta
                const qidx = getQuestionIndex(qDiv);
                answerInputs.forEach((input, idx) => {
                    input.type = 'radio';
                    input.name = `questions[${qidx}][correct]`;
                });
            } else if (typeSelect.value === 'multiple_choice') {
                answerInputs.forEach((input, idx) => {
                    input.type = 'checkbox';
                    input.name = '';
                });
            }
        }

        // Permitir deseleccionar radio (opción única)
        answersList.addEventListener('mousedown', function(e) {
            if (typeSelect.value === 'single_choice' && e.target.classList.contains('answer-correct')) {
                if (e.target.checked) {
                    e.preventDefault();
                    setTimeout(() => { e.target.checked = false; }, 0);
                }
            }
        });

        addAnswerBtn.addEventListener('click', function () {
            const aHtml = document.createElement('div');
            aHtml.innerHTML = answerTemplate(typeSelect.value, getQuestionIndex(qDiv));
            answersList.appendChild(aHtml);
            addAnswerLogic(aHtml);
            updateAnswerInputs();
            renumberQuestions();
        });
    // Obtener el índice de la pregunta en el DOM
    function getQuestionIndex(qDiv) {
        const questionItems = Array.from(questionsList.querySelectorAll('.question-item'));
        return questionItems.indexOf(qDiv);
    }
        // Remove question
        qDiv.querySelector('.remove-question-btn').addEventListener('click', function () {
            qDiv.remove();
            renumberQuestions();
        });

        // Mostrar/ocultar respuestas según tipo y setear ayuda
        function toggleAnswersArea() {
            if (typeSelect.value === 'free_text') {
                answersList.style.display = 'none';
                addAnswerBtn.style.display = 'none';
                helpMsg.style.display = 'none';
                answersList.innerHTML = '';
            } else {
                answersList.style.display = '';
                addAnswerBtn.style.display = '';
                setHelpMsg();
                if (answersList.children.length === 0) {
                    addDefaultAnswers();
                }
                updateAnswerInputs();
            }
        }
        typeSelect.addEventListener('change', function() {
            toggleAnswersArea();
            renumberQuestions();
        });
        toggleAnswersArea();
        setHelpMsg();
    }
    function addAnswerLogic(aDiv) {
        aDiv.querySelector('.remove-answer-btn').addEventListener('click', function () {
            aDiv.remove();
            renumberQuestions();
        });
    }

    // Validación antes de enviar el formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        let valid = true;
        const questionItems = questionsList.querySelectorAll('.question-item');
        // Limpiar mensajes previos
        questionItems.forEach(qDiv => {
            let errorMsg = qDiv.querySelector('.answer-error-msg');
            if (errorMsg) errorMsg.remove();
        });
        questionItems.forEach((qDiv, qidx) => {
            const typeSelect = qDiv.querySelector('.question-type-select');
            if (typeSelect && (typeSelect.value === 'single_choice' || typeSelect.value === 'multiple_choice')) {
                const answerInputs = qDiv.querySelectorAll('.answer-correct');
                let anyChecked = false;
                answerInputs.forEach(input => { if (input.checked) anyChecked = true; });
                if (!anyChecked) {
                    valid = false;
                    // Mostrar mensaje visual junto a la pregunta
                    let errorMsg = document.createElement('div');
                    errorMsg.className = 'answer-error-msg text-danger mb-2';
                    errorMsg.style.fontSize = '0.95em';
                    errorMsg.textContent = 'Debes marcar al menos una respuesta como correcta.';
                    // Insertar antes del área de respuestas
                    const answersList = qDiv.querySelector('.answers-list');
                    if (answersList) answersList.parentNode.insertBefore(errorMsg, answersList);
                }
            }
        });
        if (!valid) {
            e.preventDefault();
            // Scroll a la primera pregunta con error
            const firstError = document.querySelector('.answer-error-msg');
            if (firstError) firstError.scrollIntoView({behavior: 'smooth', block: 'center'});
        }
    });

    // Renumera preguntas y actualiza los name de los campos
    function renumberQuestions() {
        const questionItems = questionsList.querySelectorAll('.question-item');
        questionItems.forEach((qDiv, qidx) => {
            // Número de pregunta
            const numberSpan = qDiv.querySelector('.question-number');
            if (numberSpan) numberSpan.textContent = `Pregunta #${qidx + 1}`;
            // Campos de pregunta
            const textInput = qDiv.querySelector('.question-text');
            if (textInput) textInput.name = `questions[${qidx}][text]`;
            const scoreInput = qDiv.querySelector('.question-score');
            if (scoreInput) scoreInput.name = `questions[${qidx}][score_value]`;
            const typeSelect = qDiv.querySelector('.question-type-select');
            if (typeSelect) typeSelect.name = `questions[${qidx}][type]`;
            // Respuestas
            const answerItems = qDiv.querySelectorAll('.answer-item');
            answerItems.forEach((aDiv, aidx) => {
                const answerText = aDiv.querySelector('.answer-text');
                if (answerText) answerText.name = `questions[${qidx}][answers][${aidx}][text]`;
                const answerCorrect = aDiv.querySelector('.answer-correct');
                if (typeSelect && typeSelect.value === 'single_choice') {
                    answerCorrect.type = 'radio';
                    answerCorrect.name = `questions[${qidx}][correct]`;
                } else {
                    answerCorrect.type = 'checkbox';
                    answerCorrect.name = `questions[${qidx}][answers][${aidx}][is_correct]`;
                }
            });
        });
    }
});
</script>
@endpush
