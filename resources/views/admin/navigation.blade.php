<!-- Botón para abrir el offcanvas (solo visible en pantallas pequeñas/medianas) -->
<button class="btn btn-primary m-4 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarOffcanvas" aria-controls="adminSidebarOffcanvas">
    <i class="fas fa-bars"></i> Menú
</button>

<!-- Sidebar fijo para pantallas grandes -->
<div class="admin-sidebar-lg d-none d-lg-block">
    <nav class="sidebar-fixed d-flex flex-column flex-shrink-0 p-3 bg-white border-end h-100">
        <div class="mb-3">
            <h5 class="sidebar-title">Menú de administración</h5>
        </div>
        <ul class="nav nav-pills flex-column mb-auto gap-2">
            <li class="nav-item">
                <a href="{{ route('admin.courses.dashboard') }}" class="nav-link {{ request()->routeIs('courses.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-book me-2"></i> Cursos
                </a>
            </li>
            <li>
                <a href="{{ route('videos.index') }}" class="nav-link {{ request()->routeIs('courses.videos.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-video me-2"></i> Videos
                </a>
            </li>
            <li>
                <a href="{{ route('admin.courses.quizzes.dashboard') }}" class="nav-link {{ request()->routeIs('courses.tests.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list me-2"></i> Cuestionarios
                </a>
            </li>
            <li>
                <a href="{{ route('admin.courses.users.index') }}" class="nav-link {{ request()->routeIs('courses.users.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> Usuarios
                </a>
            </li>
            <li>
                <a href="{{ route('admin.courses.stats.dashboard') }}" class="nav-link {{ request()->routeIs('courses.stats.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar me-2"></i> Estadísticas
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Offcanvas Sidebar para pantallas pequeñas/medianas -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="adminSidebarOffcanvas" aria-labelledby="adminSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="adminSidebarLabel">Menú de administración</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav nav-pills flex-column mb-auto gap-2">
                <li class="nav-item">
                    <a href="{{ route('admin.courses.dashboard') }}" class="nav-link {{ request()->routeIs('courses.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-book me-2"></i> Cursos
                    </a>
                </li>
                <li>
                    <a href="{{ route('videos.index') }}" class="nav-link {{ request()->routeIs('videos.index') ? 'active' : '' }}">
                        <i class="fas fa-video me-2"></i> Videos
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.courses.quizzes.dashboard') }}" class="nav-link {{ request()->routeIs('courses.tests.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list me-2"></i> Cuestionarios
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.courses.users.index') }}" class="nav-link {{ request()->routeIs('courses.users.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i> Usuarios
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.courses.stats.dashboard') }}" class="nav-link {{ request()->routeIs('courses.stats.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar me-2"></i> Estadísticas
                    </a>
                </li>
        </ul>
    </div>
</div>
