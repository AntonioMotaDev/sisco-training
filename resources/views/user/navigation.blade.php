<!-- Botón para abrir el offcanvas (solo visible en pantallas pequeñas/medianas) -->
<button class="btn btn-primary m-4 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#userSidebarOffcanvas" aria-controls="userSidebarOffcanvas">
    <i class="fas fa-bars"></i> Menú
</button>

<!-- Sidebar fijo para pantallas grandes -->
<div class="user-sidebar-lg d-none d-lg-block">
    <nav class="sidebar-fixed d-flex flex-column flex-shrink-0 p-3 bg-white border-end h-100">
        <div class="mb-3">
            <h5 class="sidebar-title">Mi Panel de Cursos</h5>
        </div>
        <ul class="nav nav-pills flex-column mb-auto gap-2">
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i> Inicio
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.courses.*') ? 'active' : '' }}">
                    <i class="fas fa-book me-2"></i> Mis Cursos
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile.settings') }}" class="nav-link {{ request()->routeIs('profile.settings') ? 'active' : '' }}">
                    <i class="fas fa-user-cog me-2"></i> Configuración
                </a>
            </li>
        </ul>
        <div class="border-top pt-3">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                    <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </nav>
</div>

<!-- Offcanvas para pantallas pequeñas/medianas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="userSidebarOffcanvas" aria-labelledby="userSidebarOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="userSidebarOffcanvasLabel">Mi Panel de Cursos</h5>
        <p class="text-muted small mb-0">{{ $user->name }} - {{ $user->role->name ?? 'Usuario' }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav nav-pills flex-column gap-2">
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i> Inicio
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.courses.*') ? 'active' : '' }}">
                    <i class="fas fa-book me-2"></i> Mis Cursos
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile.settings') }}" class="nav-link {{ request()->routeIs('profile.settings') ? 'active' : '' }}">
                    <i class="fas fa-user-cog me-2"></i> Configuración
                </a>
            </li>
        </ul>
        <div class="border-top pt-3 mt-3">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                    <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .user-sidebar-lg {
        width: 280px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1000;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .user-layout {
        display: flex;
        min-height: 100vh;
    }

    .user-content {
        flex: 1;
        margin-left: 280px;
        background-color: #f8f9fa;
    }

    @media (max-width: 991.98px) {
        .user-content {
            margin-left: 0;
        }
    }

    .nav-link.active {
        background-color: var(--tufts-blue);
        color: white;
    }

    .nav-link:hover:not(.active) {
        background-color: #e9ecef;
    }

    .sidebar-title {
        color: #495057;
        font-weight: 600;
    }

    .content-area {
        background: white;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
</style>