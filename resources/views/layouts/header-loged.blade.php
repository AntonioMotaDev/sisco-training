<header class="header bg-white shadow-sm">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center py-2">
            <div class="logo" id="headerLogo">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center"> 
                    <img src="{{ asset('images/logos/SISCOTRAINING-LOGO.png') }}" alt="SISCO Training" class="logo-image" style="height: 40px;">
                </a>
            </div>
            
            <nav class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn dropdown-toggle d-flex align-items-center" 
                            type="button" 
                            id="userDropdown" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        <div class="d-flex align-items-center nav-links">
                            <i class="fas fa-user m-2"></i>
                            <span class="me-2 d-none d-sm-inline">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down fs-12"></i>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" 
                        aria-labelledby="userDropdown">
                        <li class="px-3 py-1 text-muted small">
                            <span class="d-block">Conectado como</span>
                            <strong class="d-block text-truncate" style="max-width: 200px;">
                                {{ Auth::user()->email }}
                            </strong>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" 
                               href="{{ route('profile.settings') }}">
                                <i class="fas fa-user-cog me-2 text-muted"></i>
                                <span>Configurar Perfil</span>
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="dropdown-item d-flex align-items-center py-2 text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>

<style>
/* Header y transición del logo cuando se abre el offcanvas */
.header {
    position: sticky;
    top: 0;
    z-index: 1060; /* Mayor que el offcanvas (1055) */
    background: white;
}

.logo {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1050; /* Mayor que el offcanvas para que quede visible */
    position: relative;
}

.logo.shifted {
    transform: translateX(280px);
}

/* Solo aplicar el desplazamiento en pantallas medianas y pequeñas */
@media (min-width: 992px) {
    .logo.shifted {
        transform: none;
    }
}

.dropdown-toggle {
    text-decoration: none;
    color: #2d3748;
    transition: all 0.2s ease;
}

.dropdown-toggle::after {
    display: none;
}

.dropdown-toggle:hover {
    color: #4a5568;
}

.dropdown-menu {
    margin-top: 0.5rem;
    min-width: 240px;
    border-radius: 0.5rem;
    border: 1px solid rgba(0,0,0,0.1);
}

.dropdown-item {
    padding: 0.5rem 1rem;
    color: #4a5568;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f7fafc;
    color: #2d3748;
}

.dropdown-item.text-danger:hover {
    background-color: #fff5f5;
}

.avatar {
    transition: transform 0.2s ease;
}

.dropdown-toggle:hover .avatar {
    transform: scale(1.05);
}

.fs-12 {
    font-size: 12px;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .dropdown-menu {
        min-width: 200px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const logo = document.getElementById('headerLogo');
    const offcanvasElement = document.getElementById('adminSidebarOffcanvas');
    
    // Solo ejecutar si ambos elementos existen (páginas admin)
    if (offcanvasElement && logo) {
        // Función para verificar si estamos en pantalla pequeña/mediana
        function isSmallScreen() {
            return window.innerWidth < 992;
        }
        
        // Evento cuando se abre el offcanvas
        offcanvasElement.addEventListener('shown.bs.offcanvas', function () {
            if (isSmallScreen()) {
                logo.classList.add('shifted');
            }
        });
        
        // Evento cuando se cierra el offcanvas
        offcanvasElement.addEventListener('hidden.bs.offcanvas', function () {
            logo.classList.remove('shifted');
        });
        
        // Evento cuando se redimensiona la ventana
        window.addEventListener('resize', function() {
            if (!isSmallScreen()) {
                logo.classList.remove('shifted');
            }
        });
    }
});
</script>

