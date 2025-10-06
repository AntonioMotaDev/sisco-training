@extends('layouts.app')

@section('title', 'Acceder con Token - SISCO Training')

@section('content')
<div class="login-container">
    <div class="col-md-6">
        <div class="card login-card">
            <div class="login-header">
                <h1 class="login-title">SISCO Training</h1>
                <p class="login-subtitle">Acceso con Token de Seguridad</p>
            </div>

            <div class="login-tabs">
                <button class="login-tab" onclick="window.location.href='{{ route('login') }}'">
                    Usuario y Contraseña
                </button>
                <button class="login-tab active" onclick="window.location.href='{{ route('login.token') }}'">
                    Token de Acceso
                </button>
            </div>

            <!-- Mostrar mensajes de error o éxito -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
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
                <strong>Instrucciones:</strong><br>
                Ingrese el token de acceso que le fue proporcionado por el administrador. Los tokens tienen una duración limitada y son de un solo uso.
            </div>

            <form action="{{ route('login.token.submit') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="access_token" class="form-label">Token de Acceso</label>
                    <input 
                        type="text" 
                        id="access_token" 
                        name="access_token" 
                        class="form-control" 
                        value="{{ old('access_token') }}"
                        required
                        placeholder="Ingrese su token de acceso"
                        style="font-family: monospace; letter-spacing: 1px;"
                    >
                    <small style="color: var(--dark-gray); font-size: 0.85rem;">
                        El token debe tener 64 caracteres alfanuméricos
                    </small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Acceder con Token
                    </button>
                </div>
            </form>

            <div class="text-center mt-0">
                <p class="mb-2">¿No tienes un token?</p>
                <a href="{{ route('request.token') }}" class="btn btn-secondary">
                    Solicitar Token de Acceso
                </a>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" style="color: var(--tufts-blue); text-decoration: none;">
                    ← Volver al inicio de sesión normal
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 w-100">
        <img src="{{ asset('images/login-image.png') }}" alt="Bienvenido a SISCOPLAGAS" class="m-0">
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tokenField = document.getElementById('access_token');
        
        if (tokenField) {
            tokenField.focus();
            
            // Formatear el token mientras se escribe
            tokenField.addEventListener('input', function() {
                // Remover espacios y convertir a minúsculas
                let value = this.value.replace(/\s/g, '').toLowerCase();
                
                // Limitar a 64 caracteres
                if (value.length > 64) {
                    value = value.substring(0, 64);
                }
                
                this.value = value;
            });
            
            // Permitir pegar tokens con espacios
            tokenField.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const cleaned = paste.replace(/\s/g, '').toLowerCase().substring(0, 64);
                this.value = cleaned;
            });
        }
    });
</script>
@endpush
@endsection 