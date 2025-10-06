@extends('layouts.app')

@section('title', 'Dashboard - SISCO Training')

@section('content')
<div class="container-fluid px-4 py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row w-100">
        <div class="col-md-4">
            <!-- Tokens de acceso -->
            <div class="tokens mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2 p-0">
                    <h5 class="mb-0">Tokens de acceso</h5>
                    <a href="#" class="text-primary text-decoration-none">Ver todos</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle table-sm w-auto">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col" class="code">Código del Token</th>
                                <th scope="col">Duración</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nombre Apellido</td>
                                <td class="code">
                                    <div class="d-flex align-items-start">
                                        <span class="text-primary" id="token-code">{{ Str::limit('CodigoDelToken1234567', 18) }}</span>
                                        <button class="btn p-0 ms-2" id="copy-token-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Copiar token" onclick="copyToClipboard(document.getElementById('token-code').innerText, this)">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="token-date">
                                    DD/MM/AAA - HH:MM
                                    <br>  
                                    DD/MM/AAA - HH:MM
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#" class="btn btn-outline-secondary p-0">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-outline-danger p-0">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Repite para más tokens -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Crear o Gestionar Cursos -->
            <div class="row">
                <div class="card h-100 border-0 shadow-sm p-0">
                    <div class="card-body">
                        <h5 class="card-title mb-4">CREAR O GESTIONAR CURSOS</h5>
                        <p class="card-text text-muted mb-4">
                        Administrar los cursos de SISCO Training, enlazar videos con nuevos cuestionarios
                        o editar los ya existentes.
                    </p>
                    <a href="{{ route('courses.dashboard') }}" class="btn w-100">  
                        Ir a los cursos
                    </a>    
                </div>
            </div>  
        </div>
        
    </div>
    
    <div class="col-md-8 d-flex flex-column gap-4">
        <!-- Cursos en progreso -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-0">Cursos en progreso</h5>
            </div>
        </div>

        <!-- Placeholder para más tarjetas -->
        <div class="card  border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 200px;">
                <div class="text-center text-muted">
                    <i class="fas fa-plus-circle fa-3x mb-3"></i>
                    <p>Contenido adicional</p>
                </div>
            </div>
        </div>
    </div>



</div>

@endsection 

@push('scripts')
    <script>
        function copyToClipboard(text, btn) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(function() {
                    if (btn) {
                        btn.setAttribute('data-bs-original-title', '¡Copiado!');
                        var tooltip = bootstrap.Tooltip.getOrCreateInstance(btn);
                        tooltip.show();
                        setTimeout(function() {
                            btn.setAttribute('data-bs-original-title', 'Copiar token');
                        }, 1200);
                    }
                });
            } else {
                // Fallback para navegadores antiguos
                var textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                if (btn) {
                    btn.setAttribute('data-bs-original-title', '¡Copiado!');
                    var tooltip = bootstrap.Tooltip.getOrCreateInstance(btn);
                    tooltip.show();
                    setTimeout(function() {
                        btn.setAttribute('data-bs-original-title', 'Copiar token');
                    }, 1200);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            var copyBtn = document.getElementById('copy-token-btn');
            if (copyBtn) {
                new bootstrap.Tooltip(copyBtn);
            }
        });
    </script> 

@endpush