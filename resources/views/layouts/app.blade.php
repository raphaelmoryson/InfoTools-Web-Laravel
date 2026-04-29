<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'InfoTools')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" defer></script>

    <!-- Choices.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js" defer></script>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        .navbar {
            width: 220px;
            background-color: #0E2339;
            color: #fff;
            display: flex;
            flex-direction: column;
        }

        .navbar_title {
            padding: 1rem;
            border-bottom: 1px solid #1D4875;
        }

        .navbar_link {
            flex-grow: 1;
            padding: 1rem 0;
        }

        .nav-link {
            color: #fff;
        }

        .nav-link.active {
            background-color: #1D4875;
            font-weight: bold;
        }

        .content {
            flex-grow: 1;
            padding: 2rem;
            background-color: #f8f9fa;
        }

        .dashboard-header {
            margin-bottom: 1.5rem;
        }

        .icon-btn {
            border: none;
            background: none;
            position: relative;
        }

        .icon-btn .badge {
            position: absolute;
            top: -5px;
            right: -5px;
        }
    </style>
</head>

<body>

    <div class="app">

        <div class="navbar">
            <div class="navbar_title text-center">
                <img style="width: 150px;" src="{{ asset('img/logo.png') }}" alt="InfoTools Logo">
            </div>

            <div class="navbar_link">
                <ul class="nav flex-column">

                    @auth
                        <li class="nav-item">
                            <a href="{{ url('/') }}"
                                class="nav-link d-flex align-items-center {{ request()->is('/') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2 me-2"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.index') }}"
                                class="nav-link d-flex align-items-center {{ request()->is('clients*') ? 'active' : '' }}">
                                <i class="bi bi-people me-2"></i> Clients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('appointments.index') }}"
                                class="nav-link d-flex align-items-center {{ request()->is('appointments*') ? 'active' : '' }}">
                                <i class="bi bi-calendar-check me-2"></i> Rendez-vous
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}"
                                class="nav-link d-flex align-items-center {{ request()->is('products*') ? 'active' : '' }}">
                                <i class="bi bi-box-seam me-2"></i> Produits
                            </a>
                        </li>
                    @endauth

                    @auth
                        <li class="nav-item mt-4 px-3">
                            <div class="d-flex align-items-center text-white p-2"
                                style="background-color: #1D4875; border-radius: 5px;">
                                <i class="bi bi-person-circle fs-4 me-2"></i>
                                <div class="text-truncate">
                                    <small class="d-block text-secondary"
                                        style="font-size: 0.75rem; color: #a5b4fc !important;">Connecté en tant que</small>
                                    <span class="fw-bold">{{ auth()->user()->name }}</span>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item mt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="nav-link btn btn-link text-white d-flex align-items-center justify-content-center w-100">
                                    <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    @endauth

                    @guest
                        <li class="nav-item mt-4">
                            <span class="nav-link text-white d-flex align-items-center justify-content-center w-100">
                                Connectez-vous pour accéder aux fonctionnalités
                            </span>
                        </li>
                    @endguest

                </ul>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            {{-- HEADER --}}
            @auth
                @hasSection('title')
                    <div class="dashboard-header d-flex justify-content-between align-items-center">
                        <h1 class="h4">@yield('title')</h1>

                        <div class="d-flex align-items-center gap-3">

                            <!-- Search -->
                            <form role="search" class="d-flex">
                                <input type="text" class="form-control me-2" placeholder="Rechercher...">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>

                            <!-- Notifications -->
                            <button class="icon-btn position-relative btn btn-light rounded-circle">
                                <i class="bi bi-bell"></i>
                                <span class="badge bg-danger rounded-pill">3</span>
                            </button>
                        </div>
                    </div>
                @endif
            @endauth

            {{-- FLASH SUCCESS --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- PAGE CONTENT --}}
            @yield('content')

        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>