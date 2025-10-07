@extends('layouts.app')

@section('title', 'Prueba Sidebar - SISCO Training')

@section('content')
    <div class="admin-layout">
        @include('admin.navigation')
        <div class="admin-content">
            <div class="container-fluid px-4 py-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">Prueba del Sidebar Responsive</h1>
                        <p class="text-muted">Verifica que el sidebar funcione correctamente</p>
                    </div>
                </div>

                <!-- Contenido de prueba -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Instrucciones de Prueba</h5>
                                <ul>
                                    <li><strong>Pantallas grandes (≥992px):</strong> El sidebar debe estar siempre visible a la izquierda y este contenido debe ajustarse automáticamente.</li>
                                    <li><strong>Pantallas medianas/pequeñas (&lt;992px):</strong> Debe aparecer solo el botón "Menú" y al hacer clic debe abrirse el offcanvas con los enlaces de navegación.</li>
                                    <li><strong>Responsive:</strong> Al redimensionar la ventana, el comportamiento debe cambiar automáticamente.</li>
                                </ul>
                                
                                <div class="alert alert-info mt-3">
                                    <strong>Nota:</strong> Redimensiona la ventana del navegador para probar ambos comportamientos.
                                </div>
                                
                                <!-- Contenido extra para probar scroll -->
                                <div class="mt-4">
                                    <h6>Contenido de relleno para probar scroll:</h6>
                                    @for($i = 1; $i <= 20; $i++)
                                        <p>Este es el párrafo número {{ $i }} para generar contenido y poder probar que el sidebar no interfiere con el contenido principal y que todo se comporta correctamente en diferentes tamaños de pantalla.</p>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection