@extends('layouts.app')

@section('title', 'Dashboard de Estadísticas - SISCO Training')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="{{ asset('css/stats-dashboard.css') }}" rel="stylesheet">

@section('content')
<div class="admin-layout">
    @include('admin.navigation')
    <div class="admin-content">
        <div class="container-fluid px-4 py-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-chart-bar me-2 text-primary-blue"></i>
                        Dashboard de Estadísticas
                    </h1>
                    <p class="text-muted">
                        Análisis completo del rendimiento de la plataforma
                        <span class="badge bg-light text-dark ms-2" id="lastUpdateBadge">
                            <i class="fas fa-clock me-1"></i>
                            Actualizado: {{ now()->format('d/m/Y H:i') }}
                        </span>
                        <span class="badge bg-success text-white ms-1" id="liveIndicator" style="display: none;">
                            <i class="fas fa-circle fa-xs me-1"></i>
                            En vivo
                        </span>
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-primary" onclick="window.location.reload()" title="Actualizar estadísticas">
                        <i class="fas fa-sync-alt me-2"></i>
                        Actualizar
                    </button>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard-admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Estadísticas</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Estadísticas principales -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card h-100" data-bs-toggle="tooltip" data-bs-placement="top" 
                         title="Total de cursos creados en la plataforma. {{ $activeCourses }} de {{ $totalCourses }} están activos.">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stats-label" style="color: var(--tufts-blue);">
                                        Total Cursos
                                    </div>
                                    <div class="stats-number">{{ $totalCourses }}</div>
                                    <div class="stats-subtitle">{{ $activeCourses }} activos</div>
                                </div>
                                <div class="stats-icon gradient-primary">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card h-100" data-bs-toggle="tooltip" data-bs-placement="top" 
                         title="Número total de usuarios registrados en la plataforma de aprendizaje.">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stats-label" style="color: var(--yellow-green);">
                                        Total Usuarios
                                    </div>
                                    <div class="stats-number">{{ $totalUsers }}</div>
                                    <div class="stats-subtitle">Registrados</div>
                                </div>
                                <div class="stats-icon gradient-success">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card h-100" data-bs-toggle="tooltip" data-bs-placement="top" 
                         title="Total de intentos de exámenes realizados. {{ $passedAttempts }} han sido exitosos de {{ $totalAttempts }} intentos totales.">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stats-label" style="color: var(--picton-blue);">
                                        Intentos de Examen
                                    </div>
                                    <div class="stats-number">{{ $totalAttempts }}</div>
                                    <div class="stats-subtitle text-success">{{ $passedAttempts }} exitosos</div>
                                </div>
                                <div class="stats-icon gradient-info">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card h-100" data-bs-toggle="tooltip" data-bs-placement="top" 
                         title="Porcentaje general de aprobación de todos los exámenes en la plataforma.">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="stats-label" style="color: var(--olive);">
                                        Tasa de Éxito
                                    </div>
                                    <div class="stats-number">
                                        {{ $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 1) : 0 }}%
                                    </div>
                                    <div class="stats-subtitle">De aprobación</div>
                                </div>
                                <div class="stats-icon gradient-warning">
                                    <i class="fas fa-percentage"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido estadístico -->
            <div class="row mb-4">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card stats-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--dark-gray);">
                                        Total Temas
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTopics }}</div>
                                </div>
                                <div class="text-primary">
                                    <i class="fas fa-book fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card stats-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--dark-gray);">
                                        Total Videos
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalVideos }}</div>
                                </div>
                                <div class="text-success">
                                    <i class="fas fa-video fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card stats-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: var(--dark-gray);">
                                        Total Preguntas
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalQuestions }}</div>
                                </div>
                                <div class="text-warning">
                                    <i class="fas fa-question-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficas principales -->
            <div class="row mb-4">
                <!-- Gráfica de intentos mensuales -->
                <div class="col-lg-8">
                    <div class="card stats-card">
                        <div class="card-header bg-primary-blue text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-chart-line me-2"></i>
                                Evolución de Actividad (Últimos 6 meses)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="monthlyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribución de usuarios por rol -->
                <div class="col-lg-4">
                    <div class="card stats-card">
                        <div class="card-header bg-yellow-green text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-pie-chart me-2"></i>
                                Distribución de Usuarios
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="usersRoleChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de cursos -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card stats-card">
                        <div class="card-header bg-primary-blue text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-graduation-cap me-2"></i>
                                Rendimiento por Curso
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-stats table-hover">
                                    <thead>
                                        <tr>
                                            <th>Curso</th>
                                            <th class="text-center">Temas</th>
                                            <th class="text-center">Videos</th>
                                            <th class="text-center">Tests</th>
                                            <th class="text-center">Intentos</th>
                                            <th class="text-center">Éxito</th>
                                            <th class="text-center">Tasa de Éxito</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($courseStats as $course)
                                        <tr>
                                            <td class="fw-bold">{{ $course['name'] }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-stats" style="background-color: var(--tufts-blue); color: white;">{{ $course['topics_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-stats" style="background-color: var(--yellow-green); color: white;">{{ $course['videos_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-stats" style="background-color: var(--yellow); color: var(--olive);">{{ $course['tests_count'] }}</span>
                                            </td>
                                            <td class="text-center fw-bold">{{ $course['total_attempts'] }}</td>
                                            <td class="text-center text-success fw-bold">{{ $course['passed_attempts'] }}</td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="progress progress-custom flex-grow-1 me-2">
                                                        <div class="progress-bar 
                                                            @if($course['success_rate'] >= 80) bg-success 
                                                            @elseif($course['success_rate'] >= 60) bg-warning 
                                                            @else bg-danger 
                                                            @endif" 
                                                            role="progressbar" 
                                                            style="width: {{ $course['success_rate'] }}%"></div>
                                                    </div>
                                                    <small class="fw-bold 
                                                        @if($course['success_rate'] >= 80) text-success 
                                                        @elseif($course['success_rate'] >= 60) text-warning 
                                                        @else text-danger 
                                                        @endif">
                                                        {{ $course['success_rate'] }}%
                                                    </small>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top usuarios y tests más difíciles -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card stats-card">
                        <div class="card-header bg-olive text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-trophy me-2"></i>
                                Top Usuarios (Intentos Exitosos)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-stats table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Usuario</th>
                                            <th class="text-center">Intentos Exitosos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topUsers as $index => $user)
                                        <tr>
                                            <td class="fw-bold">
                                                @if($index === 0)
                                                    <i class="fas fa-trophy" style="color: gold;"></i>
                                                @elseif($index === 1)
                                                    <i class="fas fa-medal" style="color: silver;"></i>
                                                @elseif($index === 2)
                                                    <i class="fas fa-medal" style="color: #cd7f32;"></i>
                                                @else
                                                    {{ $index + 1 }}
                                                @endif
                                            </td>
                                            <td class="fw-semibold">{{ $user->name }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-stats" style="background-color: var(--yellow-green); color: white;">{{ $user->passed_attempts_count }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card stats-card">
                        <div class="card-header bg-danger text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Tests Más Difíciles
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-stats table-sm">
                                    <thead>
                                        <tr>
                                            <th>Test</th>
                                            <th>Tema</th>
                                            <th class="text-center">Intentos</th>
                                            <th class="text-center">Tasa Éxito</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($difficultTests->take(8) as $test)
                                        <tr>
                                            <td class="fw-semibold">{{ Str::limit($test['name'], 25) }}</td>
                                            <td class="text-muted small">{{ Str::limit($test['topic'], 20) }}</td>
                                            <td class="text-center fw-bold">{{ $test['attempts'] }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-stats
                                                    @if($test['success_rate'] < 50) badge-success-rate-low
                                                    @elseif($test['success_rate'] < 70) badge-success-rate-medium
                                                    @else badge-success-rate-high
                                                    @endif">
                                                    {{ $test['success_rate'] }}%
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Configuración común para los gráficos
    Chart.defaults.font.family = "'Roboto Slab', 'Rockwell', serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.padding = 15;
    
    // Paleta de colores usando las variables CSS
    const colors = {
        primary: getComputedStyle(document.documentElement).getPropertyValue('--tufts-blue').trim(),
        secondary: getComputedStyle(document.documentElement).getPropertyValue('--picton-blue').trim(),
        success: getComputedStyle(document.documentElement).getPropertyValue('--yellow-green').trim(),
        warning: getComputedStyle(document.documentElement).getPropertyValue('--yellow').trim(),
        danger: getComputedStyle(document.documentElement).getPropertyValue('--red').trim(),
        olive: getComputedStyle(document.documentElement).getPropertyValue('--olive').trim()
    };

    // Función para animar números
    function animateNumbers() {
        const numbers = document.querySelectorAll('.stats-number');
        numbers.forEach(number => {
            const target = parseInt(number.textContent.replace(/[^\d]/g, ''));
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                if (number.textContent.includes('%')) {
                    number.textContent = Math.floor(current) + '%';
                } else {
                    number.textContent = Math.floor(current);
                }
            }, 40);
        });
    }

    // Animar las barras de progreso
    function animateProgressBars() {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 500);
        });
    }

    // Función para actualizar estadísticas en tiempo real
    function updateRealtimeStats() {
        // Mostrar indicador de actualización
        const liveIndicator = document.getElementById('liveIndicator');
        if (liveIndicator) {
            liveIndicator.style.display = 'inline';
        }
        
        // Añadir clase updating a las cards
        document.querySelectorAll('.stats-card').forEach(card => {
            card.classList.add('updating');
        });

        fetch('/api/admin/stats/realtime', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error fetching stats:', data.error);
                return;
            }
            
            // Actualizar números en las tarjetas principales
            updateStatsCard('totalCourses', data.totalCourses, data.activeCourses + ' activos');
            updateStatsCard('totalUsers', data.totalUsers, 'Registrados');
            updateStatsCard('totalAttempts', data.totalAttempts, data.passedAttempts + ' exitosos');
            updateStatsCard('successRate', data.successRate + '%', 'De aprobación');
            
            // Actualizar timestamp
            const lastUpdateBadge = document.getElementById('lastUpdateBadge');
            if (lastUpdateBadge) {
                lastUpdateBadge.innerHTML = '<i class="fas fa-clock me-1"></i>Actualizado: ' + data.lastUpdate;
            }
        })
        .catch(error => {
            console.error('Error updating stats:', error);
        })
        .finally(() => {
            // Ocultar indicador y remover clase updating después de 1 segundo
            setTimeout(() => {
                if (liveIndicator) {
                    liveIndicator.style.display = 'none';
                }
                document.querySelectorAll('.stats-card').forEach(card => {
                    card.classList.remove('updating');
                });
            }, 1000);
        });
    }

    function updateStatsCard(type, mainValue, subtitle) {
        const cards = document.querySelectorAll('.stats-card');
        cards.forEach(card => {
            const label = card.querySelector('.stats-label').textContent.trim();
            let match = false;
            
            switch(type) {
                case 'totalCourses':
                    match = label.includes('Total Cursos');
                    break;
                case 'totalUsers':
                    match = label.includes('Total Usuarios');
                    break;
                case 'totalAttempts':
                    match = label.includes('Intentos de Examen');
                    break;
                case 'successRate':
                    match = label.includes('Tasa de Éxito');
                    break;
            }
            
            if (match) {
                const numberElement = card.querySelector('.stats-number');
                const subtitleElement = card.querySelector('.stats-subtitle');
                
                // Animación de cambio
                numberElement.style.transform = 'scale(1.1)';
                numberElement.textContent = mainValue;
                subtitleElement.textContent = subtitle;
                
                setTimeout(() => {
                    numberElement.style.transform = 'scale(1)';
                }, 200);
            }
        });
    }

    // Configurar actualización automática (cada 30 segundos)
    const autoUpdateInterval = setInterval(updateRealtimeStats, 30000);

    // Botón de actualización manual
    document.querySelector('button[onclick="window.location.reload()"]').onclick = function(e) {
        e.preventDefault();
        
        // Cambiar icono a spinner
        const icon = this.querySelector('i');
        const originalClass = icon.className;
        icon.className = 'fas fa-spinner fa-spin me-2';
        this.disabled = true;
        
        updateRealtimeStats();
        
        // Restaurar botón después de 2 segundos
        setTimeout(() => {
            icon.className = originalClass;
            this.disabled = false;
        }, 2000);
    };

    // Ejecutar animaciones iniciales
    setTimeout(animateNumbers, 300);
    setTimeout(animateProgressBars, 800);

    // Limpiar interval al salir de la página
    window.addEventListener('beforeunload', () => {
        if (autoUpdateInterval) {
            clearInterval(autoUpdateInterval);
        }
    });

    // Gráfica de actividad mensual
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyAttempts);
    const monthlyUsers = @json($monthlyUsers);
    
    // Preparar datos para los últimos 6 meses
    const months = [];
    const attemptsData = [];
    const usersData = [];
    
    for (let i = 5; i >= 0; i--) {
        const date = new Date();
        date.setMonth(date.getMonth() - i);
        const monthKey = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
        const monthLabel = date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
        
        months.push(monthLabel);
        attemptsData.push(monthlyData[monthKey] || 0);
        usersData.push(monthlyUsers[monthKey] || 0);
    }

    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Intentos de Exámenes',
                    data: attemptsData,
                    borderColor: colors.primary,
                    backgroundColor: colors.primary + '40',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8
                },
                {
                    label: 'Nuevos Usuarios',
                    data: usersData,
                    borderColor: colors.success,
                    backgroundColor: colors.success + '40',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.success,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 13,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: colors.primary,
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        stepSize: 1,
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            elements: {
                line: {
                    borderWidth: 3
                }
            }
        }
    });

    // Gráfica de distribución de usuarios por rol
    const roleCtx = document.getElementById('usersRoleChart').getContext('2d');
    const roleData = @json($usersByRole);
    
    new Chart(roleCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(roleData),
            datasets: [{
                data: Object.values(roleData),
                backgroundColor: [
                    colors.primary,
                    colors.success,
                    colors.warning,
                    colors.secondary,
                    colors.olive
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBackgroundColor: [
                    colors.primary + 'CC',
                    colors.success + 'CC',
                    colors.warning + 'CC',
                    colors.secondary + 'CC',
                    colors.olive + 'CC'
                ],
                hoverBorderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12,
                            weight: 'bold'
                        },
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const dataset = data.datasets[0];
                                    const total = dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((dataset.data[i] / total) * 100).toFixed(1);
                                    return {
                                        text: `${label} (${percentage}%)`,
                                        fillStyle: dataset.backgroundColor[i],
                                        strokeStyle: dataset.backgroundColor[i],
                                        pointStyle: 'circle',
                                        hidden: isNaN(dataset.data[i]),
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: colors.primary,
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return `${context.label}: ${context.parsed} usuarios (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '65%',
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 2000
            }
        }
    });
});
</script>
@endsection