<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SimpleLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/css/dashboard.css')
    @else
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @endif
</head>
<body>
    <nav class="top-nav">
        <div class="logo-area">
            <div class="logo-icon">
                <i data-lucide="flask-conical"></i>
            </div>
            <div class="logo-text">
                <h1>SimpleLab</h1>
                <p>Lab IOT Computing</p>
            </div>
        </div>
        <div class="user-area">
            <span class="badge-admin">{{ auth()->user() ? 'User' : 'Admin' }}</span>
            <span class="user-email">{{ auth()->user()->email ?? 'admin@simplelab.com' }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i data-lucide="log-out" style="width: 18px;"></i>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="main-container">
        <div class="header-section">
            <div class="title-row">
                <h2>Dashboard Admin</h2>
                <div class="badge-admin-outline">
                    <i data-lucide="shield-check" style="width: 14px;"></i>
                    Admin
                </div>
            </div>
            <p class="subtitle">Kelola peralatan, peminjaman, dan pengguna Laboratorium IOT Computing</p>
        </div>

        <div class="tabs-nav">
            <div class="tab-item active" onclick="switchTab('ringkasan')">Ringkasan</div>
            <div class="tab-item" onclick="switchTab('peralatan')">Peralatan</div>
            <div class="tab-item" onclick="switchTab('peminjaman')">Peminjaman</div>
            <div class="tab-item" onclick="switchTab('pengguna')">Pengguna</div>
            <div class="tab-item" onclick="switchTab('laporan')">Laporan</div>
        </div>

        @if(session('success'))
            <div class="alert success-alert">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert error-alert">{{ session('error') }}</div>
        @endif

        @include('dashboard.sections.ringkasan')
        @include('dashboard.sections.peralatan')
        @include('dashboard.sections.peminjaman')
        @include('dashboard.sections.pengguna')
        @include('dashboard.sections.auto-admin')
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Tab switching logic
        function switchTab(tabId) {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-item').forEach(item => {
                item.classList.remove('active');
            });
            // Add active class to clicked tab
            event.target.classList.add('active');

            // Hide all content areas
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            // Show target content area
            document.getElementById(tabId).classList.add('active');
        }
    </script>
</body>
</html>
