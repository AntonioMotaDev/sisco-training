@extends('layouts.app')

@section('title', 'Solicitar Token - SISCO Training')

@section('content')
<div class="login-container">
    <div class="col-md-6 w-100 ">
        <img src="{{ asset('images/login-image.png') }}" alt="Bienvenido a SISCOPLAGAS" class=" m-0">
    </div>
    <div class="col-md-6">
        <div class="card login-card" style="max-width: 500px;">
            <div class="login-header">
                <h1 class="login-title">SISCO Training</h1>
            <p class="login-subtitle">Solicitar Token de Acceso</p>
        </div>

        <!-- Mostrar mensajes de error o éxito -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                @if(session('token_data'))
                    <hr style="margin: 1rem 0; border: none; border-top: 1px solid #c3e6cb;">
                    <strong>Detalles del Token:</strong><br>
                    <strong>Usuario:</strong> {{ session('token_data.user') }}<br>
                    <strong>Duración:</strong> {{ session('token_data.duration') }} minutos<br>
                    <strong>Expira:</strong> {{ session('token_data.expires_at') }}<br>
                    <div style="margin-top: 1rem; padding: 0.5rem; background: rgba(255,255,255,0.8); border-radius: 4px; word-break: break-all; font-family: monospace; font-size: 0.9rem;">
                        <strong>Token:</strong> {{ session('token_data.token') }}
                    </div>
                @endif
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="alert alert-info">
            <strong>Información importante:</strong><br>
            • Los tokens son temporales y tienen una duración específica<br>
            • Solo los usuarios registrados pueden solicitar tokens<br>
            • Guarde el token en un lugar seguro<br>
            • Contacte al administrador si necesita ayuda
        </div>

        <form action="{{ route('request.token.submit') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="username" class="form-label">Nombre de Usuario</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="form-control" 
                    value="{{ old('username') }}"
                    required
                    placeholder="Ingrese su nombre de usuario registrado"
                >
                <small style="color: var(--dark-gray); font-size: 0.85rem;">
                    Debe ser un usuario válido en el sistema
                </small>
            </div>

            <div class="form-group">
                <label for="duration" class="form-label">Duración del Token (minutos)</label>
                <select id="duration" name="duration" class="form-control" required>
                    <option value="">Seleccione la duración</option>
                    <option value="30" {{ old('duration') == '30' ? 'selected' : '' }}>30 minutos</option>
                    <option value="60" {{ old('duration') == '60' ? 'selected' : '' }}>1 hora</option>
                    <option value="120" {{ old('duration') == '120' ? 'selected' : '' }}>2 horas</option>
                    <option value="240" {{ old('duration') == '240' ? 'selected' : '' }}>4 horas</option>
                    <option value="480" {{ old('duration') == '480' ? 'selected' : '' }}>8 horas</option>
                    <option value="1440" {{ old('duration') == '1440' ? 'selected' : '' }}>24 horas</option>
                </select>
                <small style="color: var(--dark-gray); font-size: 0.85rem;">
                    Seleccione el tiempo que necesita para completar sus tareas
                </small>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-secondary" style="width: 100%;">
                    Generar Token de Acceso
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="mb-2">¿Ya tienes un token?</p>
            <a href="{{ route('login.token') }}" class="btn btn-outline">
                Acceder con Token
            </a>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" style="color: var(--tufts-blue); text-decoration: none;">
                ← Volver al inicio de sesión
            </a>
        </div>

        <div class="text-center mt-4" style="border-top: 1px solid var(--border-color); padding-top: 1rem;">
            <p style="color: var(--dark-gray); font-size: 0.85rem;">
                <strong>Nota:</strong> Para obtener credenciales de usuario, contacte al administrador del sistema.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const usernameField = document.getElementById('username');
        if (usernameField) {
            usernameField.focus();
        }

        // Si hay un token en la sesión, seleccionar el texto para facilitar copia
        const tokenElement = document.querySelector('[style*="font-family: monospace"]');
        if (tokenElement) {
            tokenElement.addEventListener('click', function() {
                const range = document.createRange();
                range.selectNodeContents(this);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
            });
        }
    });
</script>
@endpush
@endsection 