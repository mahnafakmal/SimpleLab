<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMPLELAB - Laboratory Equipment Management')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css" rel="stylesheet">
    <style>
        :root {
            --unimus-primary: #1e40af;
            --unimus-secondary: #ff6b35;
            --unimus-accent: #2563eb;
            --unimus-light: #f8fafc;
            --unimus-success: #10b981;
            --unimus-danger: #ef4444;
            --unimus-warning: #f59e0b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        /* Header with UNIMUS Logo */
        header {
            background: rgba(30, 64, 175, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            color: white;
            padding: 0.6rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 700;
            font-size: 1.3rem;
        }

        .navbar-brand img {
            height: 48px;
            width: auto;
        }

        .navbar-brand span {
            display: flex;
            flex-direction: column;
            font-size: 1rem;
            letter-spacing: 0.5px;
            color: #fff;
        }

        .navbar-brand span small {
            font-size: 0.75rem;
            opacity: 0.8;
            font-weight: 400;
        }

        /* Sidebar */
        .sidebar {
            background-color: #0f2347;
            color: #94a3b8;
            min-height: calc(100vh - 70px);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1.5rem 0;
        }

        .sidebar .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            margin: 0.2rem 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover {
            color: #f1f5f9;
            background-color: rgba(255, 255, 255, 0.05);
            padding-left: 1.5rem;
        }

        .sidebar .nav-link.active {
            background-color: var(--unimus-secondary);
            color: white;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.25);
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            padding: 2rem;
            background-color: #f8fafc;
            min-height: calc(100vh - 70px);
        }

        /* Dashboard Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.02);
            border-left: 4px solid var(--unimus-primary);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        }

        .stat-card.available {
            border-left-color: var(--unimus-success);
        }

        .stat-card.borrowed {
            border-left-color: var(--unimus-warning);
        }

        .stat-card.damaged {
            border-left-color: var(--unimus-danger);
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--unimus-primary);
        }

        .stat-label {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
            margin-top: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
                    <img src="{{ asset('images/barangs/logo-unimus.png') }}" alt="UNIMUS Logo" onerror="this.onerror=null;this.src='{{ asset('images/barangs/logo-unimus.png') }}';">
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
                        <!-- Notification Dropdown -->
                        @php
                            // Guard: if the notifications table doesn't exist yet, avoid querying it
                            if (\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                                $navbarNotifications = Auth::user()->unreadNotifications()->take(10)->get();
                                $navbarNotifCount = Auth::user()->unreadNotifications()->count();
                            } else {
                                $navbarNotifications = collect();
                                $navbarNotifCount = 0;
                            }

                            // Also fetch recent LogAkses (Riwayat Aktivitas Akun & RFID) for the current user
                            if (\Illuminate\Support\Facades\Schema::hasTable('log_akses')) {
                                $navbarLogAkses = \App\Models\LogAkses::where('user_id', Auth::id())->orderBy('created_at', 'desc')->take(5)->get();
                            } else {
                                $navbarLogAkses = collect();
                            }
                        @endphp
                        <div class="dropdown me-2">
                            <button class="btn btn-outline-light btn-sm position-relative dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px; padding: 0.4rem 1rem;">
                                <i class="bi bi-bell"></i>
                                <span class="d-none d-md-inline">Notifikasi</span>
                                @if($navbarNotifCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem; padding: 0.25em 0.6em;">
                                        {{ $navbarNotifCount }}
                                    </span>
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 py-0" style="min-width: 320px; max-height: 400px; overflow-y: auto; border-radius: 12px; margin-top: 10px;">
                                <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                                    <span class="fw-bold text-dark"><i class="bi bi-bell-fill text-warning me-1"></i> Notifikasi</span>
                                    @if($navbarNotifCount > 0)
                                        <span class="badge bg-danger-subtle text-danger">{{ $navbarNotifCount }} baru</span>
                                    @endif
                                </div>
                                @forelse($navbarNotifications as $n)
                                    <li class="border-bottom">
                                        <div class="dropdown-item py-3 px-3" style="white-space: normal;">
                                            @php $data = $n->data; @endphp
                                            @if(isset($data['type']) && $data['type'] === 'equipment_overdue')
                                                @php $img = $data['barang_image'] ?? asset('images/barangs/logo-unimus.png'); @endphp
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <img src="{{ $img }}" alt="thumb" style="width:48px;height:48px;object-fit:cover;border-radius:6px;" onerror="this.onerror=null;this.src='{{ asset('images/barangs/logo-unimus.png') }}'">
                                                        <h6 class="mb-0 fw-bold text-danger" style="font-size: 0.9rem;">{{ $data['barang_name'] ?? 'Barang' }}</h6>
                                                    </div>
                                                    <span class="badge bg-danger" style="font-size: 0.7rem;">Terlambat</span>
                                                </div>
                                                <p class="mb-1 text-muted small" style="line-height: 1.3;">Batas waktu pengembalian telah terlewati.</p>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span class="small text-muted">Tenggat: <strong>{{ isset($data['due_date']) ? (new \Carbon\Carbon($data['due_date']))->format('d/m/Y') : '-' }}</strong></span>
                                                    <span class="small fw-bold text-danger">({{ $data['days_overdue'] ?? '0' }} hari)</span>
                                                </div>
                                                <a href="{{ route('equipment.return') }}" class="btn btn-danger btn-sm w-100 mt-2 text-white fw-semibold" style="font-size: 0.8rem; border-radius: 6px;">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Kembalikan Sekarang
                                                </a>
                                            @elseif(isset($data['type']) && $data['type'] === 'equipment_activity')
                                                @php $img = $data['barang_image'] ?? asset('images/barangs/logo-unimus.png'); @endphp
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <img src="{{ $img }}" alt="thumb" style="width:48px;height:48px;object-fit:cover;border-radius:6px;" onerror="this.onerror=null;this.src='{{ asset('images/barangs/logo-unimus.png') }}'">
                                                        <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">{{ $data['barang_name'] ?? 'Barang' }}</h6>
                                                    </div>
                                                    @if($data['action'] === 'borrowed' || $data['action'] === 'requested')
                                                        <span class="badge bg-primary" style="font-size: 0.7rem;">Dipinjam</span>
                                                    @else
                                                        <span class="badge bg-success" style="font-size: 0.7rem;">Dikembalikan</span>
                                                    @endif
                                                </div>
                                                <p class="mb-1 text-muted small" style="line-height: 1.3;">{{ $data['message'] ?? '' }}</p>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <a href="{{ route('equipment.return') }}" class="btn btn-outline-secondary btn-sm" style="font-size:0.8rem;">Lihat</a>
                                                    <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                                                </div>
                                            @else
                                                <p class="mb-0 small">{{ $n->data['message'] ?? 'Notifikasi baru' }}</p>
                                            @endif
                                        </div>
                                    </li>
                                @empty
                                    {{-- no unread notifications; will show log akses items below if any --}}
                                @endforelse

                                @if($navbarLogAkses->isNotEmpty())
                                    <div class="p-2 border-top bg-white small text-muted">Riwayat Aktivitas Akun &amp; RFID</div>
                                    @foreach($navbarLogAkses as $log)
                                        <li class="border-bottom">
                                            <div class="dropdown-item py-3 px-3" style="white-space: normal;">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="bi bi-card-text fs-4 text-secondary"></i>
                                                        <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">{{ $log->action }}</h6>
                                                    </div>
                                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-0 text-muted small">{{ $log->notes ?? '-' }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <!-- User Menu Dropdown -->
                        <div class="dropdown">
                            <div class="dropdown" style="position: relative;">
                                <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: 20px; padding: 0.4rem 1rem;">
                                    <i class="bi bi-person-circle"></i>
                                    {{ Auth::user()->name }}
                                </button>
                                @if(isset($navbarNotifCount) && $navbarNotifCount > 0)
                                    <span style="position: absolute; top: 0; right: 0; transform: translate(35%, -35%);">
                                        <span style="background:#7c3aed; color:#fff; border-radius:50%; display:inline-block; min-width:20px; height:20px; padding:0 6px; font-size:0.7rem; line-height:20px; text-align:center; font-weight:600; box-shadow:0 2px 4px rgba(0,0,0,0.15); border:2px solid rgba(255,255,255,0.9);">{{ $navbarNotifCount > 99 ? '99+' : $navbarNotifCount }}</span>
                                    </span>
                                @endif
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 8px;">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person"></i> Profil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right text-danger"></i> Keluar</button>
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
            <nav class="col-md-2 sidebar d-none d-md-block">
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
                    @if(Auth::user()->role !== 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                            <i class="bi bi-person-circle"></i> Profil Saya
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('history.*') ? 'active' : '' }}" href="{{ route('history.index') }}">
                            <i class="bi bi-clock-history"></i> Riwayat Peminjaman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('laporan.kerusakan.*') ? 'active' : '' }}" href="{{ route('laporan.kerusakan.index') }}">
                            <i class="bi bi-exclamation-triangle"></i> Laporan Kerusakan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('schedule.*') ? 'active' : '' }}" href="{{ route('schedule.index') }}">
                            <i class="bi bi-calendar-week"></i> Jadwal Lab
                        </a>
                    </li>
                    @endif
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
    <script>
        // When notifications dropdown is opened, mark all as read via AJAX then reload
        (function(){
            document.addEventListener('DOMContentLoaded', function(){
                var notifBtn = document.querySelector('.dropdown.me-2 button[data-bs-toggle="dropdown"]');
                if (!notifBtn) return;
                notifBtn.addEventListener('shown.bs.dropdown', function(){
                    fetch("{{ route('notifications.markRead') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({})
                    }).then(function(){
                        // reload to update unread badge and list
                        location.reload();
                    }).catch(function(){
                        // ignore errors
                    });
                });
            });
        })();
    </script>
</body>
</html>
