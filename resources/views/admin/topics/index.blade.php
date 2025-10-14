    @extends('layouts.app')

@section('title', 'Gestión de Temas - SISCO Training')

@section('content')
    <div class="admin-layout">
        @include('admin.navigation')
        <div class="admin-content">
            <div class="container-fluid px-4 py-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">Gestión de Temas</h1>
                        <p class="text-muted">Administra todos los temas disponibles en la plataforma</p>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.courses.dashboard') }}">Cursos</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Temas</li>
                        </ol>
                    </nav>
                </div>

                <!-- Action Buttons -->
                <div class="mb-4 d-flex justify-content-end align-items-center">
                    <div>
                        <a href="{{ route('topics.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Crear Nuevo Tema
                        </a>
                    </div>
                </div>

                <!-- Topics Table -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary-blue">
                                    <i class="fas fa-book-open me-2"></i>Lista de Temas
                                    <span class="text-secondary ms-2">{{ $topics->total() }}</span>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($topics->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Cursos</th>
                                            <th>Videos</th>
                                            <th>Creación</th>
                                            <th width="120">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="align-middle ">
                                        @foreach($topics as $topic)
                                            <tr>
                                                <td 
                                                    class="fw-medium text-olive">{{ $topic->name }}
                                                    <p class="text-muted" style="font-size: x-small">Código: <span class="text-primary-blue">{{ $topic->code }}</span></p>
                                                </td>
                                                <td>
                                                    @if($topic->description)
                                                        <span class="text-muted">{{ Str::limit($topic->description, 50) }}</span>
                                                    @else
                                                        <span class="text-muted fst-italic">Sin descripción</span>
                                                    @endif
                                                </td>
                                                <td class="text-center text-primary-blue" style="text-decoration: none">
                                                    <a href="{{ route('admin.courses.index', ['topic_id' => $topic->id]) }}">
                                                        <i class="fas fa-book"></i>
                                                        {{ $topic->courses_count }}
                                                    </a>
                                                </td>
                                                <td class="text-center text-primary-blue"  style="text-decoration: none">
                                                    <a href="{{ route('videos.index', ['topic_id' => $topic->id]) }}">
                                                        <i class="fas fa-video"></i>
                                                        <span  style="text-decoration: none">{{ $topic->videos_count }}</span>
                                                    </a>
                                                </td>
                                                <td class="text-muted">{{ $topic->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('topics.show', $topic) }}" title="Ver Detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('topics.edit', $topic) }}" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button 
                                                            class="btn btn-sm btn-outline-danger delete-topic" 
                                                            data-topic-id="{{ $topic->id }}"
                                                            data-topic-name="{{ $topic->name }}"
                                                            title="Eliminar"
                                                        >
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
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
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay temas registrados</h5>
                                <p class="text-muted mb-4">Comienza creando tu primer tema para la plataforma</p>
                                <a href="{{ route('topics.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Crear Primer Tema
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el tema <strong id="topicNameToDelete"></strong>?</p>
                    <p class="text-danger"><small><i class="fas fa-exclamation-triangle me-1"></i>Esta acción no se puede deshacer.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-trash me-2"></i>Eliminar Tema
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Handle delete confirmation
    document.querySelectorAll('.delete-topic').forEach(button => {
        button.addEventListener('click', function() {
            const topicId = this.dataset.topicId;
            const topicName = this.dataset.topicName;
            
            document.getElementById('topicNameToDelete').textContent = topicName;
            document.getElementById('deleteForm').action = `/topics/${topicId}`;
            
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });
});


function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${icon} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insert alert at the top of the container
    const container = document.querySelector('.container-fluid');
    const firstChild = container.querySelector('.d-flex');
    firstChild.insertAdjacentHTML('beforebegin', alertHtml);
}
</script>
@endpush