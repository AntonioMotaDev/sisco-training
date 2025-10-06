@extends('layouts.app')

@section('title', 'Iniciar Sesión - SISCO Training')

@section('content')
<div class="login-container">
    <div class="col-md-6">
        <div class="card login-card">
            <div class="login-header">
                <h1 class="login-title">SISCO Training</h1>
                <p class="login-subtitle">Sistema de Capacitaciones Técnicas</p>
            </div>

            <div class="login-tabs">
                <button class="login-tab active" onclick="window.location.href='{{ route('login') }}'">
                    Usuario y Contraseña
                </button>
                <button class="login-tab" onclick="window.location.href='{{ route('login.token') }}'">
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

            <form action="{{ route('login.submit') }}" method="POST">
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
                        autocomplete="username"
                        placeholder="Ingrese su nombre de usuario"
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        required
                        autocomplete="current-password"
                        placeholder="Ingrese su contraseña"
                    >
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <div class="text-center mt-0">
                <p class="mb-2">¿No tienes acceso?</p>
                <a href="{{ route('request.token') }}" class="btn btn-primary">
                    Solicitar Token de Acceso
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
    // Auto-focus en el primer campo
    document.addEventListener('DOMContentLoaded', function() {
        const usernameField = document.getElementById('username');
        if (usernameField) {
            usernameField.focus();
        }
    });
</script>
@endpush
@endsection 