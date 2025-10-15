@extends('layouts.app')

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">    
        <!-- Main content -->
        <div class="container-fluid px-4 py-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.dashboard') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active">Crear Usuario</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-muted">Crear Nuevo Usuario</h1>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Volver
                </a>
            </div>

            <!-- Contenido principal -->
            <div class="row justify-content-center">
                <div class="col-md">
                    <div class="card">
                        <div class="card-header bg-primary-blue text-white">
                            <h5><i class="fas fa-user-plus me-2"></i>Información del Usuario</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
                                @csrf

                                <!-- Tipo de usuario -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Tipo de Usuario</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check border rounded p-3 h-100" id="typeAccountDiv">
                                                <input class="form-check-input" type="radio" name="type" id="typeAccount" 
                                                        value="account" {{ old('type', 'account') === 'account' ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="typeAccount">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-user-circle fa-2x text-primary-blue me-3"></i>
                                                        <div>
                                                            <h6 class="mb-1">Usuario con Cuenta</h6>
                                                            <small class="text-muted">
                                                                Usuario con email y contraseña que puede iniciar sesión normalmente
                                                            </small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>      
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check border rounded p-3 h-100" id="typeTokenDiv">
                                                <input class="form-check-input" type="radio" name="type" id="typeToken" 
                                                        value="token" {{ old('type') === 'token' ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="typeToken">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-key fa-2x text-primary-blue me-3"></i>
                                                        <div>
                                                            <h6 class="mb-1">Usuario con Token</h6>
                                                            <small class="text-muted">
                                                                Usuario que accede con un token de 16 caracteres, para acceso temporal
                                                            </small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Formulario para Usuario con Cuenta -->
                                <div id="accountForm">
                                    <div class="row">
                                        <!-- Información básica -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nombre Completo *</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                        id="name" name="name" value="{{ old('name') }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="username" class="form-label">Nombre de Usuario *</label>
                                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                                        id="username" name="username" value="{{ old('username') }}" required>
                                                @error('username')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email *</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                        id="email" name="email" value="{{ old('email') }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="role_id" class="form-label">Rol *</label>
                                                <select class="form-select @error('role_id') is-invalid @enderror" 
                                                        id="role_id" name="role_id" required>
                                                    <option value="">Seleccionar rol</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('role_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="password" class="form-label">Contraseña *</label>
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                        id="password" name="password" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="password_confirmation" class="form-label">Confirmar Contraseña *</label>
                                                <input type="password" class="form-control" 
                                                        id="password_confirmation" name="password_confirmation" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Formulario para Usuario con Token -->
                                <div id="tokenForm" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name_token" class="form-label">Nombre Completo *</label>
                                                <input type="text" class="form-control" 
                                                        id="name_token" name="name_token" value="{{ old('name_token') }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="token_duration" class="form-label">Duración del Token *</label>
                                                <select class="form-select" id="token_duration" name="token_duration">
                                                    <option value="1" {{ old('token_duration', '30') == '1' ? 'selected' : '' }}>1 día</option>
                                                    <option value="3" {{ old('token_duration', '30') == '3' ? 'selected' : '' }}>3 días</option>
                                                    <option value="7" {{ old('token_duration', '30') == '7' ? 'selected' : '' }}>7 días</option>
                                                    <option value="14" {{ old('token_duration', '30') == '14' ? 'selected' : '' }}>14 días</option>
                                                    <option value="30" {{ old('token_duration', '30') == '30' ? 'selected' : '' }}>30 días</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="generated_token" class="form-label">Token Generado</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control bg-light" 
                                                            id="generated_token" name="generated_token" 
                                                            value="" readonly>
                                                    <button class="btn btn-outline-secondary" type="button" 
                                                            onclick="copyToken()" id="copyTokenBtn" disabled>
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                    <button class="btn btn-outline-primary" type="button" 
                                                            onclick="generateNewToken()">
                                                        <i class="fas fa-sync"></i>
                                                    </button>
                                                </div>
                                                <small class="form-text text-muted">
                                                    El token se genera automáticamente al seleccionar esta opción
                                                </small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="role_id_token" class="form-label">Rol *</label>
                                                <select class="form-select" id="role_id_token" name="role_id_token">
                                                    <option value="" disabled selected>Seleccionar rol</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información adicional según el tipo -->
                                <div class="alert alert-info" id="tokenInfo" style="display: none;">
                                    <h6><i class="fas fa-info-circle me-2"></i>Información sobre Token</h6>
                                    <ul class="mb-0">
                                        <li>Se generará automáticamente un token de 16 caracteres únicos</li>
                                        <li>El token tendrá la duración que selecciones</li>
                                        <li>Podrás renovar el token cuando sea necesario desde el panel de administración</li>
                                        <li>No se requiere email ni contraseña para usuarios con token</li>
                                        <li>El usuario podrá acceder usando únicamente el token generado</li>
                                    </ul>
                                </div>

                                <div class="alert alert-warning" id="accountInfo">
                                    <h6><i class="fas fa-info-circle me-2"></i>Información sobre Cuenta</h6>
                                    <ul class="mb-0">
                                        <li>El usuario podrá iniciar sesión con email y contraseña</li>
                                        <li>El email es obligatorio para usuarios con cuenta</li>
                                        <li>Tendrá acceso completo según su rol asignado</li>
                                        <li>Puede cambiar su contraseña desde su perfil</li>
                                    </ul>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-danger">
                                        <i class="fas fa-times me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Crear Usuario
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const typeAccount = document.getElementById('typeAccount');
    const typeAccountDiv = document.getElementById('typeAccountDiv');
    const typeToken = document.getElementById('typeToken');
    const typeTokenDiv = document.getElementById('typeTokenDiv');
    const tokenInfo = document.getElementById('tokenInfo');
    const accountInfo = document.getElementById('accountInfo');
    const accountForm = document.getElementById('accountForm');
    const tokenForm = document.getElementById('tokenForm');
    const generatedTokenInput = document.getElementById('generated_token');
    const copyTokenBtn = document.getElementById('copyTokenBtn');

    // Función para generar token aleatorio
    function generateToken() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let token = '';
        for (let i = 0; i < 16; i++) {
            token += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return token;
    }

    // Función para actualizar el formulario según el tipo
    function updateFormByType() {
        // Reset border colors
        typeAccountDiv.style.borderColor = '';
        typeTokenDiv.style.borderColor = '';

        if (typeToken.checked) {
            // Usuario con token
            accountForm.style.display = 'none';
            tokenForm.style.display = 'block';
            tokenInfo.style.display = 'block';
            accountInfo.style.display = 'none';
            typeTokenDiv.classList.add('border-primary');
            typeAccountDiv.classList.remove('border-primary');
            
            // Generar token automáticamente
            if (!generatedTokenInput.value) {
                generateNewToken();
            }
            
            // Hacer campos de token requeridos
            document.getElementById('name_token').setAttribute('required', 'required');
            document.getElementById('role_id_token').setAttribute('required', 'required');
            
            // Remover requerimiento de campos de cuenta
            document.getElementById('name').removeAttribute('required');
            document.getElementById('username').removeAttribute('required');
            document.getElementById('email').removeAttribute('required');
            document.getElementById('role_id').removeAttribute('required');
            document.getElementById('password').removeAttribute('required');
            document.getElementById('password_confirmation').removeAttribute('required');
            
        } else {
            // Usuario con cuenta
            accountForm.style.display = 'block';
            tokenForm.style.display = 'none';
            tokenInfo.style.display = 'none';
            accountInfo.style.display = 'block';
            typeAccountDiv.classList.add('border-primary');
            typeTokenDiv.classList.remove('border-primary');
            
            // Hacer campos de cuenta requeridos
            document.getElementById('name').setAttribute('required', 'required');
            document.getElementById('username').setAttribute('required', 'required');
            document.getElementById('email').setAttribute('required', 'required');
            document.getElementById('role_id').setAttribute('required', 'required');
            document.getElementById('password').setAttribute('required', 'required');
            document.getElementById('password_confirmation').setAttribute('required', 'required');
            
            // Remover requerimiento de campos de token
            document.getElementById('name_token').removeAttribute('required');
            document.getElementById('role_id_token').removeAttribute('required');
        }
    }

    // Función para generar nuevo token
    window.generateNewToken = function() {
        const newToken = generateToken();
        generatedTokenInput.value = newToken;
        copyTokenBtn.disabled = false;
    }

    // Función para copiar token al portapapeles
    window.copyToken = function() {
        const tokenValue = generatedTokenInput.value;
        if (tokenValue) {
            navigator.clipboard.writeText(tokenValue).then(function() {
                // Cambiar temporalmente el icono del botón
                const originalHTML = copyTokenBtn.innerHTML;
                copyTokenBtn.innerHTML = '<i class="fas fa-check text-success"></i>';
                setTimeout(function() {
                    copyTokenBtn.innerHTML = originalHTML;
                }, 1500);
            }).catch(function() {
                // Fallback para navegadores que no soportan clipboard API
                generatedTokenInput.select();
                document.execCommand('copy');
            });
        }
    }

    // Event listeners
    typeAccount.addEventListener('change', updateFormByType);
    typeToken.addEventListener('change', updateFormByType);

    // Sincronizar campos cuando se cambia el tipo
    document.getElementById('name_token').addEventListener('input', function() {
        document.getElementById('name').value = this.value;
    });
    
    document.getElementById('name').addEventListener('input', function() {
        document.getElementById('name_token').value = this.value;
    });
    
    document.getElementById('role_id_token').addEventListener('change', function() {
        document.getElementById('role_id').value = this.value;
    });
    
    document.getElementById('role_id').addEventListener('change', function() {
        document.getElementById('role_id_token').value = this.value;
    });

    // Ejecutar al cargar la página
    updateFormByType();
});
</script>

<style>
    .form-check.border:hover {
        border-color: #4c8ec5 !important;
        box-shadow: 0 0 0 0.2rem rgba(76, 142, 197, 0.25);
    }

    .form-check.border.border-primary {
        border-color: #4c8ec5 !important;
        border-width: 2px;
        box-shadow: 0 0 0 0.2rem rgba(76, 142, 197, 0.25);
    }

    #generated_token {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .token-preview {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        margin: 10px 0;
    }

    .fade-transition {
        transition: all 0.3s ease-in-out;
    }
</style>
@endsection