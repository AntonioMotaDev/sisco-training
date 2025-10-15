@extends('layouts.app')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">
        <!-- Main content -->
        <div class="container-fluid px-4 py-4">
            <div class="content-area p-4">
                <div>
                    <h1 class="h3 mb-0 text-muted">Dashboard de Usuarios</h1>
                    <p class="text-muted">Gestiona todos los usuarios de SISCO Training</p>
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Crear Usuario
                    </a>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary-blue text-white">
                                <h5><i class="fas fa-users me-2"></i>Listado de Usuarios</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th>Email</th>
                                                <th>Tipo</th>
                                                <th>Estado</th>
                                                <th>Fecha Registro</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($totalUsers) && $totalUsers->count() > 0)
                                            @forelse($totalUsers as $user)
                                                <tr>
                                                    <td>{{ $user->id }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        @if($user->password)
                                                            <span class="badge bg-warning">Cuenta</span>
                                                        @else
                                                            <span class="badge bg-info">Token</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($user->is_active)
                                                            <span class="badge bg-success">Activo</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactivo</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">No hay usuarios registrados</td>
                                                </tr>
                                            @endforelse
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones rápidas -->
                {{-- <div class="row">
                    <div class="col-md">
                        <div class="card">
                            <div class="card-header bg-primary-blue text-white">
                                <h5><i class="fas fa-cogs me-2"></i>Acciones Rápidas</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-list me-2"></i>Ver Todos los Usuarios
                                    </a>
                                    <a href="{{ route('admin.users.index', ['type' => 'token']) }}" class="btn btn-outline-info">
                                        <i class="fas fa-key me-2"></i>Usuarios con Token
                                    </a>
                                    <a href="{{ route('admin.users.index', ['type' => 'account']) }}" class="btn btn-outline-warning">
                                        <i class="fas fa-user-circle me-2"></i>Usuarios con Cuenta
                                    </a>
                                    <a href="{{ route('admin.users.index', ['status' => 'expired']) }}" class="btn btn-outline-danger">
                                        <i class="fas fa-clock me-2"></i>Tokens Expirados
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection