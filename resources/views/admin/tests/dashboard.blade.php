@extends('layouts.app')

@section('title', 'Dashboard de Cuestionarios (Test)')

@section('content')
    <div class="admin-layout">
        @include('admin.navigation')
        <div class="admin-content">
            <div class="container-fluid px-4 py-4">
                <div>
                    <h1 class="h3 mb-0 text-muted">Dashboard de Cuestionarios</h1>
                    <p class="text-muted">Gestiona todos los cuestionarios de SISCO Training</p>
                </div>

                <div class="card">
                    <div class="card-header bg-white text-primary-blue py-3">
                        <h6> <i class="fas fa-book-open me-2"></i> Cuestionarios por Temas </h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tema</th>
                                    <th class="text-center">Cursos</th>
                                    <th>Cuestionarios</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topics as $topic)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.tests.index', ['topic' => $topic->id]) }}" class="text-olive">{{ $topic->name }}</a>
                                        </td>
                                        <td class="text-center text-muted">
                                            <i class="fas fa-book"></i> {{ $topic->courses->count() }}
                                        </td>
                                        <td class="text-center text-muted">
                                            <i class="fas fa-book"></i> {{ $topic->tests->count() }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.tests.create', ['topic' => $topic->id]) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus"></i> Agregar
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay temas disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        @if($topics->hasPages())
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        Mostrando {{ $topics->firstItem() }} a {{ $topics->lastItem() }} de {{ $topics->total() }} resultados
                                    </div>
                                    {{ $topics->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection