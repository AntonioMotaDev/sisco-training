<nav id="sidebar" class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-white border-end">
    <div class="d-flex align-items-center mb-4">
        <button class="btn btn-sm ms-auto d-lg-none" id="sidebarCloseBtn" aria-label="Cerrar menú">
            <i class="fas fa-times">cerrar</i>
        </button>
    </div>
    <ul class="nav nav-pills flex-column mb-auto gap-2">
        <li class="nav-item">
            <a href="{{ route('courses.dashboard') }}" class="nav-link {{ request()->routeIs('courses.dashboard') ? 'active' : '' }}">
                <i class="fas fa-book me-2"></i> Cursos
            </a>
        </li>
        <li>
            <a href="{{ route('courses.videos.dashboard') }}" class="nav-link {{ request()->routeIs('courses.videos.dashboard') ? 'active' : '' }}">
                <i class="fas fa-video me-2"></i> Videos
            </a>
        </li>
        <li>
            <a href="{{ route('courses.quizzes.dashboard') }}" class="nav-link {{ request()->routeIs('courses.tests.dashboard') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list me-2"></i> Cuestionarios
            </a>
        </li>
        <li>
            <a href="{{ route('courses.users.index') }}" class="nav-link {{ request()->routeIs('courses.users.dashboard') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i> Usuarios
            </a>
        </li>
        <li>
            <a href="{{ route('courses.stats.dashboard') }}" class="nav-link {{ request()->routeIs('courses.stats.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-bar me-2"></i> Estadísticas
            </a>
        </li>
    </ul>
</nav>
