<header class="header bg-white shadow-sm">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center py-2">
            <div class="logo">
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
.header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: white;
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

