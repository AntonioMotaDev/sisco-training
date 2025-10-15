@extends('layouts.app')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">    
        <div class="container-fluid px-4">
            <div class="content-area p-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.dashboard') }}">Usuarios</a></li>
                        <li class="breadcrumb-item active">Lista de usuarios</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="col-6">
                        <h3 class="text-muted mb-0"><i class="fas fa-users me-2"></i>Dashboard de Usuarios</h3>
                        <p class="text-muted mb-0">Administra los usuarios de la plataforma</p>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('admin.users.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Crear Usuario
                    </a>
                </div>

                <!-- Filtros y Lista de usuarios -->
                <div class="card mb-4">
                    <div class="card-header bg-primary-blue text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Usuarios ({{ $users->total() }} total)</h5>
                        </div>
                        <div>
                            <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros -->
                        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label for="search" class="form-label">Buscar</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Nombre, usuario o email">
                            </div>

                            <div class="col-md-2">
                                <label for="type" class="form-label">Tipo</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="">Todos</option>
                                    <option value="token" {{ request('type') === 'token' ? 'selected' : '' }}>Con Token</option>
                                    <option value="account" {{ request('type') === 'account' ? 'selected' : '' }}>Con Cuenta</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="role" class="form-label">Rol</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="">Todos</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="status" class="form-label">Estado</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Todos</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirados</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i>Buscar
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Limpiar
                                </a>
                            </div>
                        </form>

                        <!-- Lista de usuarios -->
                        @if($users->count() > 0)
                            <div class="table-responsive shadow-sm rounded">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Email</th>
                                            <th>Rol</th>
                                            <th>Tipo</th>
                                            <th>Cursos</th>
                                            <th>Creado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $userItem)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle me-2">
                                                            {{ strtoupper(substr($userItem->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $userItem->name }}</div>
                                                            <small class="text-muted">{{ $userItem->username }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $userItem->email ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="text-muted">{{ $userItem->role ? $userItem->role->name : 'Sin rol' }}</span>
                                                </td>
                                                <td>
                                                    @if($userItem->access_token)
                                                        <span class="text-muted">
                                                            <i class="fas fa-key me-1"></i>Token
                                                        </span>
                                                    @else
                                                        <span class="text-muted">
                                                            <i class="fas fa-user-circle me-1"></i>Cuenta
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $coursesCount = $userItem->takenCourses()->count();
                                                    @endphp
                                                    <span class="text-muted">{{ $coursesCount }}</span>
                                                </td>
                                                <td class="text-muted">
                                                    <small>{{ $userItem->created_at->format('d/m/Y') }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('admin.users.show', $userItem) }}" 
                                                           class="btn btn-outline-primary" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.users.edit', $userItem) }}" 
                                                           class="btn btn-outline-secondary" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if($userItem->access_token)
                                                            <form action="{{ route('admin.users.renew-token', $userItem) }}" 
                                                                  method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-outline-success" 
                                                                        title="Renovar token"
                                                                        onclick="return confirm('¿Renovar el token de acceso?')">
                                                                    <i class="fas fa-sync"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('admin.users.destroy', $userItem) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" 
                                                                    title="Eliminar"
                                                                    onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginación -->
                            <div class="d-flex justify-content-center mt-3">
                                {{ $users->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5>No se encontraron usuarios</h5>
                                <p class="text-muted">No hay usuarios que coincidan con los criterios de búsqueda.</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Crear Primer Usuario
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: var(--tufts-blue);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}
</style>
@endsection