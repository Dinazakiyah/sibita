<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Bimbingan TA - UNEJ')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Custom CSS dengan Tema UNEJ -->
    <style>
        /* Warna Tema Universitas Jember: Merah, Kuning, Hijau */
        :root {
            --unej-red: #DC143C;
            --unej-yellow: #FFD700;
            --unej-green: #228B22;
            --unej-dark: #1a1a1a;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Navbar dengan gradasi warna UNEJ */
        .navbar-unej {
            background: linear-gradient(135deg, var(--unej-red) 0%, var(--unej-yellow) 50%, var(--unej-green) 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-unej .navbar-brand,
        .navbar-unej .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .navbar-unej .nav-link:hover {
            color: var(--unej-dark) !important;
        }

        /* Sidebar */
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: white;
            border-right: 1px solid #dee2e6;
            padding: 20px 0;
        }

        .sidebar .nav-link {
            color: #333;
            padding: 12px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background-color: #f8f9fa;
            border-left-color: var(--unej-red);
            color: var(--unej-red);
        }

        .sidebar .nav-link.active {
            background-color: #fff3cd;
            border-left-color: var(--unej-green);
            color: var(--unej-green);
            font-weight: 600;
        }

        /* Card dengan aksen warna UNEJ */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--unej-red), var(--unej-yellow));
            color: white;
            font-weight: 600;
            border-radius: 10px 10px 0 0 !important;
        }

        /* Button dengan warna UNEJ */
        .btn-unej-primary {
            background: var(--unej-red);
            color: white;
            border: none;
        }

        .btn-unej-primary:hover {
            background: #b71c1c;
            color: white;
        }

        .btn-unej-success {
            background: var(--unej-green);
            color: white;
            border: none;
        }

        .btn-unej-success:hover {
            background: #1b5e20;
            color: white;
        }

        .btn-unej-warning {
            background: var(--unej-yellow);
            color: #333;
            border: none;
        }

        .btn-unej-warning:hover {
            background: #fbc02d;
        }

        /* Badge dengan warna UNEJ */
        .badge-unej {
            background: linear-gradient(135deg, var(--unej-red), var(--unej-green));
            color: white;
        }

        /* Alert */
        .alert {
            border-radius: 8px;
            border: none;
        }

        /* Table */
        .table thead {
            background: linear-gradient(135deg, var(--unej-red), var(--unej-yellow));
            color: white;
        }

        /* Footer */
        .footer {
            background-color: var(--unej-dark);
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-unej">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-mortarboard-fill"></i>
                Sistem Bimbingan TA - UNEJ
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                {{ auth()->user()->name }}
                                <span class="badge badge-unej">{{ strtoupper(auth()->user()->role) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="bi bi-person-circle"></i> Profil
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            @auth
                <!-- Sidebar -->
                @include('layouts.sidebar')
            @endauth

            <!-- Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Universitas Jember. Sistem Manajemen Bimbingan Tugas Akhir.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
