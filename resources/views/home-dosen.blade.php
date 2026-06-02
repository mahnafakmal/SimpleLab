<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Dosen - SimpleLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/css/home.css')
    @else
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @endif
</head>
<body>
    <!-- Top Navigation -->
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
            <span class="badge-user">Dosen</span>
            <span class="user-email">{{ auth()->user()->email }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i data-lucide="log-out" style="width: 16px;"></i>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="main-container">
        <div class="header-section">
            <div class="title-row">
                <h2>Selamat datang, {{ auth()->user()->name }} (Dosen)</h2>
            </div>
            <p class="subtitle">Lihat jadwal mengajar dan ringkasan aktivitas lab.</p>
        </div>

        @if(session('success'))
            <div class="alert success-alert">{{ session('success') }}</div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon-box"><i data-lucide="box"></i></div>
                <span class="label">Total Alat Lab</span>
                <span class="value">{{ $totalAlat ?? 0 }}</span>
            </div>
            <div class="stat-card">
                <div class="icon-box"><i data-lucide="check-circle"></i></div>
                <span class="label">Alat Tersedia</span>
                <span class="value">{{ $alatTersedia ?? 0 }}</span>
            </div>
            <div class="stat-card">
                <div class="icon-box"><i data-lucide="clipboard-list"></i></div>
                <span class="label">Permintaan Terbaru</span>
                <span class="value">{{ $recentLoans->count() ?? 0 }}</span>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="left-column">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-title">
                            <i data-lucide="calendar"></i>
                            <h3>Jadwal Mengajar Anda</h3>
                        </div>
                    </div>

                    <div class="schedule-list">
                        @forelse($jadwalLabs as $jadwal)
                            <div class="schedule-card">
                                <div class="schedule-day-badge">
                                    <span>{{ $jadwal->hari }}</span>
                                    <span class="time">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                </div>
                                <div class="schedule-details">
                                    <h4 class="schedule-title">{{ $jadwal->mata_kuliah }}</h4>
                                    <div class="schedule-meta">
                                        <div class="schedule-meta-item">
                                            <i data-lucide="users"></i>
                                            <span class="badge badge-info">{{ $jadwal->kelas }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-placeholder">
                                <i data-lucide="calendar-off"></i>
                                <p>Belum ada jadwal mengajar yang terdaftar untuk Anda.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="right-column">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-title">
                            <i data-lucide="clipboard-list"></i>
                            <h3>Permintaan Peminjaman Terakhir</h3>
                        </div>
                    </div>

                    <div class="list">
                        @forelse($recentLoans as $loan)
                            <div class="list-item">
                                <div style="font-weight:600;">{{ $loan->barang->name }}</div>
                                <div style="font-size:12px;color:#64748b;">Diminta oleh: {{ $loan->user->name }} — {{ $loan->created_at->diffForHumans() }}</div>
                            </div>
                        @empty
                            <div class="empty-placeholder">
                                <p>Tidak ada permintaan peminjaman terkini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>
