@extends('layouts.app')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">    
        <!-- Main content -->
        <div class="container-fluid px-4">
            <div class="content-area p-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Usuario</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-muted mb-0">Editar Usuario</h3>
                    <div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary-blue text-white">
                                <h5><i class="fas fa-user-edit me-2"></i>Editando: {{ $user->name }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Información actual del usuario -->
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <div class="avatar-circle-large mx-auto mb-3">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <h5>{{ $user->name }}</h5>
                                                <p class="text-muted">{{ $user->username }}</p>
                                                <div class="text-start">
                                                    @if($user->email)
                                                        <p><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
                                                    @endif
                                                    <p><strong>Rol:</strong> <span class="text-muted">{{ $user->role->name }}</span></p>
                                                    <p><strong>Tipo:</strong> 
                                                        @if($user->access_token)
                                                            <span class="text-muted">
                                                                <i class="fas fa-key me-1"></i>Token
                                                            </span>
                                                        @else
                                                            <span class="text-muted">
                                                                <i class="fas fa-user-circle me-1"></i>Cuenta
                                                            </span>
                                                        @endif
                                                    </p>
                                                    <p><strong>Creado:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Formulario de edición -->
                                    <div class="col-md-8">
                                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Nombre Completo *</label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">Nombre de Usuario *</label>
                                                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                                               id="username" name="username" value="{{ old('username', $user->username) }}" required>
                                                        @error('username')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        @if(!$user->access_token)
                                                            <label for="email" class="form-label">
                                                                Email 
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                                id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                                            @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Nueva Contraseña</label>
                                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                               id="password" name="password">
                                                        <small class="form-text text-muted">
                                                            Deja en blanco si no quieres cambiar la contraseña
                                                        </small>
                                                        @error('password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                                        <input type="password" class="form-control" 
                                                               id="password_confirmation" name="password_confirmation">
                                                        <small class="form-text text-muted">
                                                            Solo necesario si cambias la contraseña
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-danger">
                                                    <i class="fas fa-times me-1"></i>Cancelar
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-1"></i>Guardar Cambios
                                                </button>
                                            </div>
                                        </form>

                                        @if($user->access_token)
                                        <hr>
                                        <div class="mt-4">
                                            <h6 class="text-olive">Token Actual</h6>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" disabled value="{{ $user->access_token }}" readonly>
                                                <button class="btn btn-outline-secondary" type="button" 
                                                        onclick="copyToClipboard('{{ $user->access_token }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.users.renew-token', $user) }}" method="POST" class="w-100 d-flex align-items-end gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <div class="mb-3">
                                                    <label for="token_days" class="form-label">Días para renovar el token</label>
                                                    <select id="token_days" name="token_days" class="form-select">
                                                        <option value="1">1 día</option>
                                                        <option value="3">3 días</option>
                                                        <option value="7">7 días</option>
                                                        <option value="15">15 días</option>
                                                        <option value="30">30 días</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-yellow-green w-100"
                                                        onclick="return confirm('¿Renovar el token? Esto generará un nuevo token de ' + document.getElementById('token_days').value + ' días.')">
                                                    <i class="fas fa-sync me-2"></i>Renovar Token
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: var(--tufts-blue);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 32px;
}
</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Mostrar mensaje de éxito
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check me-2"></i>Token copiado al portapapeles
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remover el elemento después de que se oculte
        toast.addEventListener('hidden.bs.toast', function() {
            document.body.removeChild(toast);
        });
    });
}
</script>
@endsection