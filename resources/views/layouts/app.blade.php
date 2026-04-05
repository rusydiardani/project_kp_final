<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0d6efd">

    <!-- Bootstrap 5 Local -->
    <link href="{{ asset('vendor/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons Local -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons.css') }}">
    <!-- Custom Premium CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
            <div class="container-xl">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <img src="{{ asset('images/logopemko.png') }}" alt="Logo Pemko" style="height: 40px;">
                    <span>{{ config('app.name', 'Sistem Monitoring') }}</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto ms-3">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link text-nowrap {{ request()->routeIs('dashboard') ? 'active fw-bold text-primary' : '' }}"
                                    href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-nowrap {{ request()->routeIs('services.*') ? 'active fw-bold text-primary' : '' }}"
                                    href="{{ route('services.index') }}">
                                    <i class="bi bi-file-earmark-text me-1"></i> Layanan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-nowrap {{ request()->routeIs('reports.*') ? 'active fw-bold text-primary' : '' }}"
                                    href="{{ route('reports.index') }}">
                                    <i class="bi bi-file-earmark-bar-graph me-1"></i> Laporan
                                </a>
                            </li>
                        @endauth
                        @can('admin')
                            <li class="nav-item">
                                <a class="nav-link text-nowrap {{ request()->routeIs('users.*') ? 'active fw-bold text-primary' : '' }}"
                                    href="{{ route('users.index') }}">
                                    <i class="bi bi-people me-1"></i> Manajemen User
                                </a>
                            </li>
                        @endcan
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">

                        @guest

                        @else
                            <li class="nav-item dropdown ms-3">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                                    href="#" role="button" data-bs-toggle="dropdown">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="rounded-circle"
                                            width="32" height="32" style="object-fit: cover; border: 2px solid white;">
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold small lh-1">{{ Auth::user()->name }}</div>
                                        <div class="text-white opacity-75 small" style="font-size: 0.75rem;">
                                            {{ ucfirst(Auth::user()->role) }}
                                        </div>
                                    </div>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person-circle me-2 text-primary"></i> Edit Profil
                                    </a>
                                    <hr class="dropdown-divider">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                                                                         document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2 text-danger"></i> Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                            <strong>Terdapat Kesalahan:</strong>
                        </div>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer class="text-center py-4 text-muted small mt-auto">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </footer>
    </div>
    <script src="{{ asset('vendor/bootstrap.bundle.min.js') }}"></script>
</body>

</html>