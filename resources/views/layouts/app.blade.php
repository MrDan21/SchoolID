<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="theme-light">
    <div class="page" id="app">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md navbar-light d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="{{ url('/') }}">
                        Gestión de Asistencia
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    @guest
                        @if (Route::has('login'))
                            <div class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Iniciar Sesión</a>
                            </div>
                        @endif
                    @else
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                                <span class="avatar avatar-sm" style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }})"></span>
                                <div class="d-none d-xl-block ps-2">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="mt-1 small text-muted">Administrador</div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </header>

        @auth
        <div class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar navbar-light">
                    <div class="container-xl">
                        <ul class="navbar-nav">
                            <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('home') }}" >
                                    <span class="nav-link-title">Inicio</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('students.index') }}" >
                                    <span class="nav-link-title">Alumnos</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('attendances.index') }}" >
                                    <span class="nav-link-title">Bitácora Asistencias</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endauth

        <div class="page-wrapper">
            <!-- Page header -->
            @yield('page-header')
            
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                      <div class="d-flex">
                        <div>{{ session('success') }}</div>
                      </div>
                      <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
</html>
