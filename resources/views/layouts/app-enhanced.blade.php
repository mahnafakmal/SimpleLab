<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SimpleLab - Laboratory Equipment Management')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css" rel="stylesheet">
    <style>
        :root {
            --unimus-primary: #003366;
            --unimus-secondary: #ff6b35;
            --unimus-accent: #004d99;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Header with UNIMUS Logo */
        header {
            background: linear-gradient(135deg, var(--unimus-primary) 0%, var(--unimus-accent) 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 700;
            font-size: 1.3rem;
        }

        .navbar-brand img {
            height: 50px;
            width: auto;
        }

        .navbar-brand span {
            display: flex;
            flex-direction: column;
            font-size: 0.9rem;
        }

        .navbar-brand span small {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        /* Sidebar */
        .sidebar {
            background-color: #fff;
            min-height: calc(100vh - 80px);
            border-right: 1px solid #e0e0e0;
            padding: 2rem 0;
        }

        .sidebar .nav-link {
            color: var(--unimus-primary);
            padding: 0.75rem 1.5rem;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(0, 51, 102, 0.05);
            border-left-color: var(--unimus-secondary);
            color: var(--unimus-secondary);
        }

        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 20px;
        }

        /* Main Content */
        .main-content {
            padding: 2rem;
        }

        /* Dashboard Cards */
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--unimus-primary);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-card.available {
            border-left-color: #28a745;
        }

        .stat-card.borrowed {
            border-left-color: #ffc107;
        }

        .stat-card.damaged {
            border-left-color: #dc3545;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--unimus-primary);
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
        }

        /* Tables */
        .table-responsive {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background-color: var(--unimus-primary);
            color: white;
        }

        .table tbody tr {
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 51, 102, 0.02);
        }

        /* Badges */
        .badge-status {
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-available {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-borrowed {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-damaged {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-overdue {
            background-color: #f5c6cb;
            color: #721c24;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--unimus-primary);
            border-color: var(--unimus-primary);
        }

        .btn-primary:hover {
            background-color: var(--unimus-accent);
            border-color: var(--unimus-accent);
        }

        .btn-secondary {
            background-color: var(--unimus-secondary);
            border-color: var(--unimus-secondary);
        }

        .btn-secondary:hover {
            background-color: #ff5722;
            border-color: #ff5722;
        }

        /* Forms */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--unimus-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 102, 0.25);
        }

        /* Alerts */
        .alert-info {
            background-color: rgba(0, 51, 102, 0.1);
            border-left: 4px solid var(--unimus-primary);
            color: var(--unimus-primary);
        }

        .alert-warning {
            background-color: rgba(255, 107, 53, 0.1);
            border-left: 4px solid var(--unimus-secondary);
            color: var(--unimus-secondary);
        }

        /* Footer */
        footer {
            background-color: var(--unimus-primary);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -250px;
                width: 250px;
                height: 100vh;
                transition: left 0.3s ease;
                z-index: 90;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                padding: 1rem;
            }

            .navbar-brand span small {
                display: none;
            }
        }

        /* RFID Scanner */
        .scanner-input {
            font-size: 1.2rem;
            padding: 1rem;
            border: 2px solid var(--unimus-primary);
            border-radius: 8px;
        }

        .scanner-result {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 8px;
            background-color: #f8f9fa;
            border-left: 4px solid var(--unimus-primary);
        }

        .scanner-result.success {
            background-color: #d4edda;
            border-left-color: #28a745;
            color: #155724;
        }

        .scanner-result.error {
            background-color: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }

        /* Charts */
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 2rem;
        }

        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .card-header {
            background-color: var(--unimus-primary);
            color: white;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
        }

        /* Loaders */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--unimus-primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    @yield('css')
</head>
<body>
    <!-- Header with UNIMUS Logo -->
    <header>
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="navbar-brand">
                    <img src="https://unimus.ac.id/wp-content/uploads/2021/10/logo-unimus-new.png" alt="UNIMUS Logo">
                    <span>
                        SimpleLab
                        <small>Laboratory Equipment Management</small>
                    </span>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="ms-auto d-flex align-items-center gap-3">
                        @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @endauth
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Navigation -->
            @auth
            <nav class="col-md-2 sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.tersedia') }}">
                            <i class="bi bi-box"></i> Alat Laboratorium
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('equipment.borrow') ? 'active' : '' }}" href="{{ route('equipment.borrow') }}">
                            <i class="bi bi-hand-thumbs-up"></i> Peminjaman Barang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('equipment.return') ? 'active' : '' }}" href="{{ route('equipment.return') }}">
                            <i class="bi bi-arrow-counterclockwise"></i> Pengembalian Alat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('schedule.*') ? 'active' : '' }}" href="{{ route('schedule.index') }}">
                            <i class="bi bi-calendar-week"></i> Jadwal Lab
                        </a>
                    </li>
                    @if(Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.laporan.peminjaman') }}">
                            <i class="bi bi-file-earmark-chart"></i> Laporan
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 main-content">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="bi bi-exclamation-circle"></i> Terjadi Kesalahan!</strong>
                    @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="bi bi-check-circle"></i> Sukses!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')
            </main>
            @else
            <main class="col-12 main-content">
                @yield('content')
            </main>
            @endauth
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container-fluid">
            <p>&copy; 2026 SimpleLab - Universitas Muhammadiyah Semarang. All rights reserved.</p>
            <small>Sistem Manajemen Alat Laboratorium</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @yield('js')
</body>
</html>
