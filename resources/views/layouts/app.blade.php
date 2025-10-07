<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SISCO Training')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet"> 
    <link href="{{ asset('css/admin-sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    
    
    @stack('styles')
</head>
<body>

    @if(request()->routeIs('login*') || request()->routeIs('request.token'))
        @include('layouts.header-not-loged')
    @endif

    @if(!request()->routeIs('login*'))
        @include('layouts.header-loged')
    @endif

    <main class="main-content">
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Bootstrap Bundle with Popper -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

    <!-- jQuery (if needed) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script>
        // Initialize all dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
            
            // Add hover functionality to dropdowns
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('mouseenter', function() {
                    const dropdownToggle = this.querySelector('.dropdown-toggle');
                    const dropdownMenu = this.querySelector('.dropdown-menu');
                    if (dropdownToggle && dropdownMenu) {
                        dropdownToggle.setAttribute('aria-expanded', 'true');
                        dropdownMenu.classList.add('show');
                    }
                });
                
                dropdown.addEventListener('mouseleave', function() {
                    const dropdownToggle = this.querySelector('.dropdown-toggle');
                    const dropdownMenu = this.querySelector('.dropdown-menu');
                    if (dropdownToggle && dropdownMenu) {
                        dropdownToggle.setAttribute('aria-expanded', 'false');
                        dropdownMenu.classList.remove('show');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const openBtn = document.getElementById('sidebarOpenBtn');
            const closeBtn = document.getElementById('sidebarCloseBtn');

            if (openBtn) {
                openBtn.addEventListener('click', function () {
                    sidebar.classList.add('show');
                });
            }
            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                });
            }
            // Cierra sidebar al hacer click fuera en móvil
            document.addEventListener('click', function (e) {
                if (window.innerWidth < 992 && sidebar.classList.contains('show')) {
                    if (!sidebar.contains(e.target) && e.target !== openBtn) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        });
    </script> 

    <script src="{{ asset('js/admin_sidebar.js') }}"></script>
    @stack('scripts')
</body>
</html>