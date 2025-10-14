@extends('layouts.app')

@section('title', 'Cuestionarios del Tema')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">
        <div class="container-fluid px-4 py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Cuestionarios</h1>
                    <p class="text-muted mb-0">Tema: <span class="fw-semibold text-primary">{{ $topic->name }}</span></p>
                </div>
                <a href="{{ route('admin.tests.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Volver a la lista
                </a>
            </div>

                <div class="d-flex justify-content-end mb-4">
                    <a href="{{ route('admin.tests.create', $topic->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nuevo cuestionario
                    </a>
                </div>

            @if($tests->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-clipboard-list fa-4x text-muted opacity-50"></i>
                    </div>
                    <h4 class="text-muted">No hay cuestionarios</h4>
                    <p class="text-muted mb-4">Aún no se han creado cuestionarios para este tema.</p>
                    <a href="{{ route('admin.tests.create', $topic->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Crear primer cuestionario
                    </a>
                </div>
            @else
                <!-- Tests Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-muted">
                                <i class="fas fa-list-ul me-2"></i>Lista de cuestionarios
                            </h6>
                            <small class="text-muted">{{ $tests->count() }} cuestionario(s)</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-semibold ps-4">Cuestionario</th>
                                    <th class="border-0 fw-semibold text-center">Calificación mínima</th>
                                    <th class="border-0 fw-semibold text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($tests as $test)
                                <tr class="align-middle">
                                    <td class="border-0 ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                {{ $iteration = $loop->iteration }}.
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-olive fw-semibold">{{ $test->name }}</h6>
                                                <small class="text-muted">
                                                    {{ Str::limit($test->description, 80) ?: 'Sin descripción disponible' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0 text-center">
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                            {{ $test->minimum_approved_grade }}%
                                        </span>
                                    </td>
                                    <td class="border-0 text-end pe-4">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.tests.show', $test->id) }}" 
                                               class="btn btn-outline" 
                                               title="Ver cuestionario"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.tests.edit', $test->id) }}" 
                                               class="btn btn-outline-secondary" 
                                               title="Editar cuestionario"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            {{-- <button type="button" 
                                                    class="btn btn-outline-info" 
                                                    title="Estadísticas"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-chart-bar"></i>
                                            </button> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
