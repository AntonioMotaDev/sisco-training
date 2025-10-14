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
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
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
                                <input type="text" class="form-control text-primary-blue fs-4" id="name" name="name" placeholder="Nombre del cuestionario" required>
                            </div>
                            <div class="mb-3">
                                {{-- <label for="description" class="form-label">Descripción</label> --}}
                                <textarea class="form-control" id="description" name="description" rows="2" placeholder="Descripción del cuestionario"></textarea>
                            </div>
                            <div class="mb-3 row align-items-end">
                                <div class="col-md-6">
                                    <label for="minimum_approved_grade" class="form-label">Calificación mínima aprobatoria</label>
                                    <input type="number" min="0" step="1.0" class="form-control" id="minimum_approved_grade" name="minimum_approved_grade" placeholder="Ejemplo: 70.00" required>
                                </div>
                                <div class="col-md-6 text-end">
                                    <label class="form-label">Valor total de preguntas</label>
                                    <div>
                                        <span id="total-score" class="fw-bold fs-5">0</span> / 100
                                        <span id="score-warning" class="text-danger ms-2" style="display:none;font-size:0.95em"></span>
                                    </div>
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
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Guardar cuestionario
                            </button>
                        </div>

                    </div>
                </form>

                <div id="alert" class="alert alert-warning d-none" role="alert">
                    Por favor, complete todos los campos requeridos antes de enviar el cuestionario.
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const totalScoreSpan = document.getElementById('total-score');
            const scoreWarning = document.getElementById('score-warning');
            const questionsList = document.getElementById('questions-list');
            const addQuestionBtn = document.getElementById('add-question-btn');
            const alertElement = document.getElementById('alert');

            // Obtener el índice de la pregunta en el DOM
            function getQuestionIndex(qDiv) {
                const questionItems = Array.from(questionsList.querySelectorAll('.question-item'));
                return questionItems.indexOf(qDiv);
            }

            function questionTemplate(isFirst = false) {
                const removeButtonHTML = isFirst ? 
                    `<button type="button" class="btn btn-sm btn-outline-secondary" disabled><i class="fas fa-lock"></i> Pregunta requerida</button>` :
                    `<button type="button" class="btn btn-sm btn-outline-danger remove-question-btn"><i class="fas fa-trash"></i> Borrar pregunta</button>`;
                
                return `
                <div class="mb-3 question-item border-0">
                    <hr>
                    <div class="container p-3 rounded bg-light shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold question-number"></span>
                            ${removeButtonHTML}
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
                const inputType = type === 'single_choice' ? 'radio' : 'checkbox';
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

            // Agregar primera pregunta por defecto
            function addDefaultQuestion() {
                const qHtml = document.createElement('div');
                qHtml.innerHTML = questionTemplate(true); // true indica que es la primera pregunta
                questionsList.appendChild(qHtml);
                addQuestionLogic(qHtml, true); // true indica que no se puede eliminar
                renumberQuestions();
                updateTotalScore();
            }

            addQuestionBtn.addEventListener('click', function () {
                const qHtml = document.createElement('div');
                qHtml.innerHTML = questionTemplate(false);
                questionsList.appendChild(qHtml);
                addQuestionLogic(qHtml, false);
                renumberQuestions();
                updateTotalScore();
            });

            // Lógica para cada pregunta
            function addQuestionLogic(qDiv, isRequired = false) {
                const answersList = qDiv.querySelector('.answers-list');
                const addAnswerBtn = qDiv.querySelector('.add-answer-btn');
                const typeSelect = qDiv.querySelector('.question-type-select');

                // Mensaje de ayuda
                let helpMsg = qDiv.querySelector('.answer-help-msg');
                if (!helpMsg) {
                    helpMsg = document.createElement('div');
                    helpMsg.className = 'answer-help-msg mb-2 text-primary-blue text-center';
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

                function addDefaultAnswers() {
                    answersList.innerHTML = '';
                    const qidx = getQuestionIndex(qDiv);
                    for (let i = 0; i < 3; i++) {
                        const aHtml = document.createElement('div');
                        aHtml.innerHTML = answerTemplate(typeSelect.value, qidx);
                        answersList.appendChild(aHtml);
                        addAnswerLogic(aHtml);
                    }
                }

                function updateAnswerInputs() {
                    const qidx = getQuestionIndex(qDiv);
                    const answerInputs = answersList.querySelectorAll('.answer-correct');
                    
                    if (typeSelect.value === 'single_choice') {
                        answerInputs.forEach((input, idx) => {
                            input.type = 'radio';
                            input.name = `questions[${qidx}][correct]`;
                            input.value = idx;
                        });
                    } else if (typeSelect.value === 'multiple_choice') {
                        answerInputs.forEach((input, idx) => {
                            input.type = 'checkbox';
                            input.name = `questions[${qidx}][answers][${idx}][is_correct]`;
                            input.value = '1';
                        });
                    }
                }

                // Permitir deseleccionar radio
                answersList.addEventListener('mousedown', function(e) {
                    if (typeSelect.value === 'single_choice' && e.target.classList.contains('answer-correct')) {
                        if (e.target.checked) {
                            e.preventDefault();
                            setTimeout(() => { e.target.checked = false; }, 0);
                        }
                    }
                });

                addAnswerBtn.addEventListener('click', function () {
                    const qidx = getQuestionIndex(qDiv);
                    const aHtml = document.createElement('div');
                    aHtml.innerHTML = answerTemplate(typeSelect.value, qidx);
                    answersList.appendChild(aHtml);
                    addAnswerLogic(aHtml);
                    updateAnswerInputs();
                    renumberQuestions();
                });
                
                // Remove question (solo si no es requerida)
                const removeBtn = qDiv.querySelector('.remove-question-btn');
                if (removeBtn && !isRequired) {
                    removeBtn.addEventListener('click', function () {
                        qDiv.remove();
                        renumberQuestions();
                        updateTotalScore();
                    });
                }

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

                // Actualizar suma cuando cambia el valor de la pregunta
                const scoreInput = qDiv.querySelector('.question-score');
                if (scoreInput) {
                    scoreInput.addEventListener('input', updateTotalScore);
                }
            }

            // Lógica para cada respuesta
            function addAnswerLogic(aDiv) {
                aDiv.querySelector('.remove-answer-btn').addEventListener('click', function () {
                    aDiv.remove();
                    renumberQuestions();
                    updateTotalScore();
                });
            }

            // Calcular y mostrar el total de valores
            function getTotalScore() {
                let total = 0;
                const questionItems = questionsList.querySelectorAll('.question-item');
                questionItems.forEach(qDiv => {
                    const scoreInput = qDiv.querySelector('.question-score');
                    if (scoreInput && scoreInput.value) {
                        total += parseFloat(scoreInput.value) || 0;
                    }
                });
                return Math.round(total * 100) / 100;
            }

            function updateTotalScore() {
                const total = getTotalScore();
                totalScoreSpan.textContent = total;
                if (total !== 100) {
                    scoreWarning.textContent = 'La suma de los valores de las preguntas debe ser exactamente 100.';
                    scoreWarning.style.display = '';
                } else {
                    scoreWarning.textContent = '';
                    scoreWarning.style.display = 'none';
                }
            }

            // Renumera preguntas y actualiza los names
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
                        const currentType = typeSelect ? typeSelect.value : 'multiple_choice';
                        
                        if (currentType === 'single_choice') {
                            answerCorrect.type = 'radio';
                            answerCorrect.name = `questions[${qidx}][correct]`;
                            answerCorrect.value = aidx;
                        } else if (currentType === 'multiple_choice') {
                            answerCorrect.type = 'checkbox';
                            answerCorrect.name = `questions[${qidx}][answers][${aidx}][is_correct]`;
                            answerCorrect.value = '1';
                        }
                    });
                });
                updateTotalScore();
            }

            // Agregar pregunta por defecto al cargar la página
            addDefaultQuestion();
                                          

            // Validación antes de enviar el formulario
            document.querySelector('form').addEventListener('submit', function(e) {
                let valid = true;
                const questionItems = questionsList.querySelectorAll('.question-item');
                
                // Limpiar mensajes previos
                questionItems.forEach(qDiv => {
                    let errorMsg = qDiv.querySelector('.answer-error-msg');
                    if (errorMsg) errorMsg.remove();
                });

                // Validar que haya al menos una pregunta (esto ya no debería pasar, pero mantenemos la validación)
                if (questionItems.length === 0) {
                    valid = false;
                    scoreWarning.style.display = 'none';
                    alertElement.textContent = 'Error: No hay preguntas en el cuestionario.';
                    alertElement.classList.remove('d-none');
                } else {
                    alertElement.classList.add('d-none');
                    
                    // Validar suma de valores
                    const total = getTotalScore();
                    if (total !== 100) {
                        valid = false;
                        scoreWarning.textContent = 'La suma de los valores de las preguntas debe ser exactamente 100.';
                        scoreWarning.style.display = '';
                    } else {
                        scoreWarning.textContent = '';
                        scoreWarning.style.display = 'none';
                    }
                }

                // Validar respuestas correctas
                questionItems.forEach((qDiv, qidx) => {
                    const typeSelect = qDiv.querySelector('.question-type-select');
                    if (typeSelect && (typeSelect.value === 'single_choice' || typeSelect.value === 'multiple_choice')) {
                        const answerInputs = qDiv.querySelectorAll('.answer-correct');
                        let anyChecked = false;
                        answerInputs.forEach(input => { 
                            if (input.checked) anyChecked = true; 
                        });
                        
                        if (!anyChecked) {
                            valid = false;
                            let errorMsg = document.createElement('div');
                            errorMsg.className = 'answer-error-msg text-danger mb-2';
                            errorMsg.style.fontSize = '0.95em';
                            errorMsg.textContent = 'Debes marcar al menos una respuesta como correcta.';
                            const answersList = qDiv.querySelector('.answers-list');
                            if (answersList) answersList.parentNode.insertBefore(errorMsg, answersList);
                        }
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    const firstError = document.querySelector('.answer-error-msg, #score-warning:not([style*="display: none"])');
                    if (firstError) firstError.scrollIntoView({behavior: 'smooth', block: 'center'});
                }
            });
        });

    </script>
@endsection
