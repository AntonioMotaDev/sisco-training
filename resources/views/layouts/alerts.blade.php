
@php
    $hasAlert = session('success') || session('error') || session('warning') || session('info') || $errors->any();
@endphp

@if ($hasAlert)
    <div id="alert-overlay" style="position:fixed;z-index:1055;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;">
        <div id="alert-modal" class="modal-dialog" style="max-width:400px;">
            <div class="modal-content">
                <div class="modal-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                            {{ session('warning') }}
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Cerrar al hacer clic fuera del modal o automáticamente después de 3 segundos
        document.addEventListener('DOMContentLoaded', function() {
            var overlay = document.getElementById('alert-overlay');
            var modal = document.getElementById('alert-modal');
            function closeAlert() {
                if (overlay) overlay.remove();
            }
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) closeAlert();
            });
            setTimeout(closeAlert, 3000);
        });
    </script>

@endif