@extends('layouts.app')

@section('title', 'Detalles del Tema - SISCO Training')

@section('content')
    <div class="admin-layout">
        @include('admin.navigation')
        <div class="admin-content">
            <div class="container-fluid px-4 py-4">
                <!-- Header -->
                <div class=" justify-content-between align-items-center mb-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.courses.dashboard') }}">Cursos</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('topics.index') }}">Temas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $topic->name }}</li>
                        </ol>
                    </nav>
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">{{ $topic->name }}</h1>
                        <p class="text-muted">Detalles completos del tema</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mb-4 d-flex gap-2 justify-content-end">
                    <a href="{{ route('topics.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver a Lista
                    </a>
                </div>

                <div class="row">
                    <!-- Main Information -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary-blue text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Información General
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>Nombre:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $topic->name }}
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>Código:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <span class="text-primary-blue">{{ $topic->code }}</span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>Descripción:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        @if($topic->description)
                                            <p class="mb-0">{{ $topic->description }}</p>
                                        @else
                                            <em class="text-muted">Sin descripción proporcionada</em>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>Creado:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $topic->created_at->format('d/m/Y H:i') }}
                                        <small class="text-muted">({{ $topic->created_at->diffForHumans() }})</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <strong>Última Actualización:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $topic->updated_at->format('d/m/Y H:i') }}
                                        <small class="text-muted">({{ $topic->updated_at->diffForHumans() }})</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Associated Courses -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary-blue text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    Cursos Asociados 
                                    <span class="badge bg-light text-dark">{{ $topic->courses->count() }}</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($topic->courses->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Curso</th>
                                                    <th>Descripción</th>
                                                    <th>Orden</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($topic->courses as $course)
                                                    <tr>
                                                        <td class="fw-medium">{{ $course->title }}</td>
                                                        <td class="text-muted">{{ Str::limit($course->description, 50) }}</td>
                                                        <td>
                                                            <span class="badge bg-secondary">{{ $course->pivot->order_in_course ?? 'N/A' }}</span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-graduation-cap fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Este tema aún no está asociado a ningún curso</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Associated Videos -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary-blue text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-video me-2"></i>
                                    Videos del Tema 
                                    <span class="badge bg-light text-dark text-end">{{ $topic->videos->count() }}</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($topic->videos->count() > 0)
                                    <div class="row">
                                        @foreach($topic->videos as $video)
                                            <div class="col-md-6 mb-3">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <h6 class="card-title">{{ $video->title }}</h6>
                                                        <p class="card-text text-muted small">
                                                            {{ Str::limit($video->description, 80) }}
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">
                                                                <i class="fas fa-clock me-1"></i>
                                                                {{ $video->duration ?? 'N/A' }}
                                                            </small>
                                                            <a href="{{ route('videos.show', $video) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i> Ver
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-video fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-2">Este tema no tiene videos asociados</p>
                                        <a href="{{ route('videos.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i>Agregar Video
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Statistics -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary-blue text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-pie me-2"></i>Estadísticas
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Cursos Asociados</span>
                                    <span class="badge bg-primary">{{ $topic->courses->count() }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Videos</span>
                                    <span class="badge bg-info">{{ $topic->videos->count() }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Tests</span>
                                    <span class="badge bg-warning text-dark">{{ $topic->tests->count() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary-blue text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('videos.create') }}?topic={{ $topic->id }}" class="btn btn-outline btn-sm">
                                        <i class="fas fa-video me-2"></i>Agregar Video
                                    </a>
                                    <a href="{{ route('admin.tests.create', $topic) }}" class="btn btn-outline btn-sm">
                                        <i class="fas fa-book me-2"></i>Crear Cuestionario
                                    </a>
                                </div>
                            </div>
                        </div>
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
                    <p>¿Estás seguro de que deseas eliminar el tema <strong>{{ $topic->name }}</strong>?</p>
                    <p class="text-danger"><small><i class="fas fa-exclamation-triangle me-1"></i>Esta acción no se puede deshacer.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('topics.destroy', $topic) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
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
function toggleApproval(topicId) {
    fetch(`/topics/${topicId}/toggle-approval`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload to show updated status
        } else {
            showAlert('error', 'Error al actualizar el estado del tema');
        }
    })
    .catch(error => {
        showAlert('error', 'Error de conexión al actualizar el tema');
    });
}

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