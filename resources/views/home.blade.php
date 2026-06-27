<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Mahasiswa - SIMPLELAB</title>
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
                <h1>SIMPLELAB</h1>
                <p>Lab IOT Computing</p>
            </div>
        </div>
        <div class="user-area">
            @if(auth()->check() && auth()->user()->role === 'dosen')
                <span class="badge-user">Dosen</span>
            @else
                <span class="badge-user">Mahasiswa / User</span>
            @endif
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

    <!-- Main Container -->
    <main class="main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="title-row">
                <h2>Halo, {{ auth()->user()->name }}! 👋</h2>
            </div>
            @if(auth()->check() && auth()->user()->role === 'dosen')
                <p class="subtitle">Selamat datang di portal dosen SimpleLab. Kelola jadwal, pantau permintaan peminjaman, dan tinjau aktivitas laboratorium.</p>
            @else
                <div class="student-info-meta" style="margin-top: 6px; margin-bottom: 12px; font-size: 0.9rem; color: #64748b; display: flex; flex-wrap: wrap; gap: 16px; align-items: center; background: #ffffff; padding: 10px 16px; border-radius: 8px; border: 1px solid #e2e8f0; width: fit-content;">
                    @if(auth()->user()->nim)
                        <span style="display: inline-flex; align-items: center; gap: 6px;"><i data-lucide="hash" style="width: 14px; height: 14px; color: #0ea5e9;"></i> <strong>NIM:</strong> {{ auth()->user()->nim }}</span>
                    @endif
                    @if(auth()->user()->prodi)
                        <span style="display: inline-flex; align-items: center; gap: 6px;"><i data-lucide="book-open" style="width: 14px; height: 14px; color: #0ea5e9;"></i> <strong>Prodi:</strong> {{ auth()->user()->prodi }}</span>
                    @endif
                    @if(auth()->user()->semester)
                        <span style="display: inline-flex; align-items: center; gap: 6px;"><i data-lucide="calendar" style="width: 14px; height: 14px; color: #0ea5e9;"></i> <strong>Semester:</strong> {{ auth()->user()->semester }}</span>
                    @endif
                </div>
                <p class="subtitle">Selamat datang di portal praktikan SimpleLab. Pantau status peralatan, ketersediaan alat, dan jadwal praktikum Anda.</p>
            @endif
        </div>

        <!-- Session Alerts -->
        @if(session('success'))
            <div class="alert success-alert">
                <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert error-alert">
                <i data-lucide="alert-triangle" style="width: 20px; height: 20px;"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Quick Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="icon-box">
                    <i data-lucide="box"></i>
                </div>
                <span class="label">Total Alat Lab</span>
                <span class="value">{{ $totalAlat }}</span>
            </div>
            <a href="{{ route('barang.tersedia') }}" style="text-decoration:none; color:inherit;">
            <div class="stat-card green">
                <div class="icon-box">
                    <i data-lucide="check-circle"></i>
                </div>
                <span class="label">Alat Tersedia</span>
                <span class="value">{{ $alatTersedia }}</span>
            </div>
            </a>
            <a href="{{ route('barang.dipinjam') }}" style="text-decoration:none; color:inherit;">
            <div class="stat-card red">
                <div class="icon-box">
                    <i data-lucide="info"></i>
                </div>
                <span class="label">Alat Dipinjam</span>
                <span class="value">{{ $alatDipinjam }}</span>
            </div>
            </a>
        </div>

        <!-- Main Dashboard Split Layout -->
        <div class="dashboard-grid">
            <!-- Left Column: Equipment Info & Status -->
            <div class="left-column">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-title">
                            <i data-lucide="database"></i>
                            <h3>Daftar & Status Alat Lab</h3>
                        </div>
                    </div>

                    <!-- Search & Filter Controls -->
                    <div class="tools-bar">
                        <div class="search-wrapper">
                            <i data-lucide="search"></i>
                            <input type="text" id="searchInput" class="search-input" placeholder="Cari nama alat atau kategori..." onkeyup="filterAlat()">
                        </div>
                        <select id="categoryFilter" class="filter-select" onchange="filterAlat()">
                            <option value="">Semua Kategori</option>
                            @foreach($barangs->pluck('kategori')->unique()->filter() as $kategori)
                                <option value="{{ $kategori }}">{{ $kategori }}</option>
                            @endforeach
                        </select>
                        <select id="statusFilter" class="filter-select" onchange="filterAlat()">
                            <option value="">Semua Status</option>
                            <option value="available">Tersedia</option>
                            <option value="borrowed">Dipinjam</option>
                        </select>
                    </div>

                    <!-- Equipment Table -->
                    <div class="table-responsive">
                        <table class="custom-table" id="alatTable">
                            <thead>
                                <tr>
                                    <th>Nama Alat</th>
                                    <th>Kategori</th>
                                    <th>Kondisi</th>
                                    <th>Status Ketersediaan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangs as $barang)
                                    @php
                                        $img = null;
                                        $basePath = public_path('images/barangs/');
                                        $candidates = [];
                                        if(!empty($barang->image)) $candidates[] = $barang->image;
                                        $candidates[] = $barang->id . '.jpg';
                                        $candidates[] = $barang->id . '.png';
                                        $candidates[] = \Illuminate\Support\Str::slug($barang->name) . '.jpg';
                                        $candidates[] = \Illuminate\Support\Str::slug($barang->name) . '.png';
                                        foreach($candidates as $c) {
                                            if(!empty($c) && file_exists($basePath . $c)) { $img = $c; break; }
                                        }
                                    @endphp
                                    <tr class="barang-row" data-name="{{ strtolower($barang->name) }}" data-kategori="{{ strtolower($barang->kategori) }}" data-status="{{ $barang->status }}">
                                        <td style="font-weight: 600; color: #0f172a; display:flex; align-items:center; gap:10px;">
                                            <div style="width:48px;height:48px;flex-shrink:0;">
                                                @if($img)
                                                    <img src="{{ asset('images/barangs/' . $img) }}" alt="{{ $barang->name }}" style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #e6edf3;">
                                                @else
                                                    <div style="width:48px;height:48px;border-radius:6px;background:#f8fafc;display:flex;align-items:center;justify-content:center;border:1px solid #e6edf3;">
                                                        <i data-lucide="box" style="width:18px;height:18px;color:#64748b"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>{{ $barang->name }}</div>
                                        </td>
                                        <td>
                                            <span class="badge-info badge">{{ $barang->kategori ?? 'Umum' }}</span>
                                        </td>
                                        <td>
                                            @if(strtolower($barang->kondisi) === 'baik')
                                                <span class="badge-success badge" style="background:#e8f5e9; color:#2e7d32;">
                                                    <i data-lucide="shield" style="width:12px; height:12px; margin-right:2px;"></i> Baik
                                                </span>
                                            @else
                                                <span class="badge-danger badge" style="background:#ffebee; color:#c62828;">
                                                    <i data-lucide="alert-triangle" style="width:12px; height:12px; margin-right:2px;"></i> {{ $barang->kondisi }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($barang->status === 'available')
                                                <span class="badge badge-success">
                                                    <i data-lucide="check" style="width: 12px; height: 12px;"></i> Tersedia
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i data-lucide="lock" style="width: 12px; height: 12px;"></i> Dipinjam
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(strtolower($barang->kondisi) === 'baik')
                                                <button type="button" onclick="reportDamage({{ $barang->id }})" class="cancel-booking-btn" style="color: var(--danger); text-decoration: none; display: flex; align-items: center; gap: 4px; padding: 0; background: none; border: none; font-size: 0.8rem; font-weight: 600; cursor: pointer;">
                                                    <i data-lucide="alert-triangle" style="width: 12px; height: 12px;"></i> Laporkan Rusak
                                                </button>
                                            @else
                                                <span style="font-size: 11px; color: var(--text-muted);">{{ $barang->kondisi }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 2rem;">
                                            <div class="empty-placeholder">
                                                <i data-lucide="package-open"></i>
                                                <p>Belum ada alat laboratorium yang terdaftar.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Transactions, Lab Schedule & My Loans -->
            <div class="right-column">
                
                <!-- Peminjaman & Pemesanan Form Card -->
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-title">
                            <i data-lucide="arrow-right-left"></i>
                            <h3>Aktivitas Lab</h3>
                        </div>
                    </div>

                    <!-- Tabs to switch forms -->
                    <div class="form-tabs">
                        <button type="button" id="tabBtnAlat" class="form-tab-btn active" onclick="switchFormTab('alatForm', this)">Pinjam Alat</button>
                        <button type="button" id="tabBtnKerusakan" class="form-tab-btn" onclick="switchFormTab('kerusakanForm', this)">Laporkan Rusak</button>
                    </div>

                    <!-- Form 1: Borrow Equipment -->
                    <form id="alatForm" class="booking-form active" action="{{ auth()->user()->role === 'dosen' ? route('web.peminjaman.alat.dosen') : route('web.peminjaman.alat') }}" method="POST">
                        @csrf
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="barang_id">Pilih Alat Lab (Tersedia)</label>
                            <select name="barang_id" id="barang_id" class="form-control-custom" required>
                                <option value="">-- Pilih Alat --</option>
                                @foreach($barangs->where('status', 'available')->where('kondisi', 'Baik') as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->kategori }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-submit-booking">
                            <i data-lucide="box" style="width: 16px; height: 16px;"></i>
                            Pinjam Alat Sekarang
                        </button>
                    </form>

                    <!-- Form 2: Report Damage -->
                    <form id="kerusakanForm" class="booking-form" action="{{ route('laporan.kerusakan.store') }}" method="POST">
                        @csrf
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="kerusakan_barang_id">Pilih Alat Lab</label>
                            <select name="barang_id" id="kerusakan_barang_id" class="form-control-custom" required>
                                <option value="">-- Pilih Alat --</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }} (Kondisi: {{ $b->kondisi }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="deskripsi_kerusakan">Deskripsi Kerusakan</label>
                            <textarea name="deskripsi" id="deskripsi_kerusakan" class="form-control-custom textarea-custom" placeholder="Jelaskan kerusakan barang secara detail..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit-booking" style="background: var(--danger);">
                            <i data-lucide="alert-triangle" style="width: 16px; height: 16px;"></i>
                            Kirim Laporan Kerusakan
                        </button>
                    </form>
                </div>

                <!-- Jadwal removed -->

                <!-- My Loans Section -->
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-title">
                            <i data-lucide="clipboard-list"></i>
                            <h3>Aktivitas Saya (Aktif)</h3>
                        </div>
                    </div>

                    <!-- Active Equipment Loans -->
                    <div class="loan-list" style="margin-bottom: 1.5rem;">
                        <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); border-left: 3px solid var(--primary); padding-left: 6px; margin-bottom: 0.5rem;">Peminjaman Alat</h4>
                        @forelse($peminjamanSaya as $pinjam)
                            <div class="loan-item" style="display:flex;gap:12px;align-items:center;">
                                <div class="loan-item-thumb" style="width:64px;height:64px;flex-shrink:0;">
                                    @php
                                        $img = null;
                                        $basePath = public_path('images/barangs/');
                                        $candidates = [];
                                        if(!empty($pinjam->barang->image)) $candidates[] = $pinjam->barang->image;
                                        $candidates[] = $pinjam->barang->id . '.jpg';
                                        $candidates[] = $pinjam->barang->id . '.png';
                                        $candidates[] = \Illuminate\Support\Str::slug($pinjam->barang->name) . '.jpg';
                                        $candidates[] = \Illuminate\Support\Str::slug($pinjam->barang->name) . '.png';
                                        foreach($candidates as $c) {
                                            if(!empty($c) && file_exists($basePath . $c)) { $img = $c; break; }
                                        }
                                    @endphp
                                    @if($img)
                                        <img src="{{ asset('images/barangs/' . $img) }}" alt="{{ $pinjam->barang->name }}" style="width:64px;height:64px;object-fit:cover;border-radius:8px;border:1px solid #e6edf3;">
                                    @else
                                        <div style="width:64px;height:64px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;border:1px solid #e6edf3;">
                                            <i data-lucide="box" style="width:24px;height:24px;color:#64748b"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="loan-item-info" style="flex:1;">
                                    <h4 style="margin:0 0 4px;">{{ $pinjam->barang->name }}</h4>
                                    <p style="margin:0;font-size:0.85rem;color:#64748b;">Dipinjam: {{ \Carbon\Carbon::parse($pinjam->started_at)->isoFormat('D MMM YYYY, HH:mm') }} WIB</p>
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.35rem;">
                                    @if($pinjam->status === 'active')
                                        <span class="badge badge-warning">Aktif</span>
                                        <form action="{{ route('web.pengembalian.alat', $pinjam->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="cancel-booking-btn">Kembalikan</button>
                                        </form>
                                    @else
                                        <span class="badge badge-success">Selesai</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-placeholder" style="padding: 1rem;">
                                <p style="font-size: 0.8rem;">Tidak ada peminjaman alat lab aktif.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- My Damage Reports -->
                    <div class="report-list">
                        <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); border-left: 3px solid var(--danger); padding-left: 6px; margin-bottom: 0.5rem;">Laporan Kerusakan</h4>
                        @forelse($laporanKerusakanSaya as $laporan)
                            <div class="loan-item" style="display:flex;gap:12px;align-items:center;margin-bottom:8px;padding:0.5rem 0.75rem;">
                                <div style="flex:1;">
                                    <h4 style="margin:0 0 2px;font-size:0.85rem;">{{ $laporan->barang->name }}</h4>
                                    <p style="margin:0;font-size:0.75rem;color:#64748b;line-height:1.2;">{{ $laporan->deskripsi }}</p>
                                    @php
                                        $displayLapWhen = '-';
                                        if(!empty($laporan->created_at)) {
                                            try {
                                                if($laporan->created_at instanceof \Illuminate\Support\Carbon) {
                                                    $displayLapWhen = $laporan->created_at->diffForHumans();
                                                } else {
                                                    $displayLapWhen = \Illuminate\Support\Carbon::parse($laporan->created_at)->diffForHumans();
                                                }
                                            } catch (\Exception $e) {
                                                $displayLapWhen = '-';
                                            }
                                        }
                                    @endphp
                                    <p style="margin:4px 0 0 0;font-size:0.7rem;color:#94a3b8;">{{ $displayLapWhen }}</p>
                                </div>
                                <div>
                                    @if($laporan->status === 'pending')
                                        <span class="badge badge-warning" style="font-size: 0.65rem;">Pending</span>
                                    @elseif($laporan->status === 'proses')
                                        <span class="badge badge-info" style="font-size: 0.65rem; background:#e0f2fe; color:#0369a1;">Proses</span>
                                    @else
                                        <span class="badge badge-success" style="font-size: 0.65rem;">Selesai</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-placeholder" style="padding: 1rem;">
                                <p style="font-size: 0.8rem;">Belum ada laporan kerusakan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Client side filtering for equipment list
        function filterAlat() {
            var searchInput = document.getElementById("searchInput").value.toLowerCase();
            var categoryFilter = document.getElementById("categoryFilter").value.toLowerCase();
            var statusFilter = document.getElementById("statusFilter").value;
            
            var rows = document.getElementsByClassName("barang-row");
            
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                var name = row.getAttribute("data-name");
                var kategori = row.getAttribute("data-kategori");
                var status = row.getAttribute("data-status");
                
                var matchesSearch = name.includes(searchInput) || kategori.includes(searchInput);
                var matchesCategory = categoryFilter === "" || kategori === categoryFilter;
                var matchesStatus = statusFilter === "" || status === statusFilter;
                
                if (matchesSearch && matchesCategory && matchesStatus) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        }

        // Switch between Borrow Equipment and Book Room forms
        function switchFormTab(formId, btnEl) {
            // Remove active classes
            document.querySelectorAll('.form-tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.booking-form').forEach(form => form.classList.remove('active'));

            // Add active class to selected tab button
            btnEl.classList.add('active');

            // Show selected form
            document.getElementById(formId).classList.add('active');
        }

        function reportDamage(barangId) {
            // Switch to the damage tab
            const tabBtn = document.getElementById('tabBtnKerusakan');
            if (tabBtn) {
                switchFormTab('kerusakanForm', tabBtn);
            }
            // Select the barang in the dropdown
            const select = document.getElementById('kerusakan_barang_id');
            if (select) {
                select.value = barangId;
            }
            // Focus the textarea
            const textarea = document.getElementById('deskripsi_kerusakan');
            if (textarea) {
                textarea.focus();
            }
        }

        // Restore active tab based on old/error states if needed
        document.addEventListener("DOMContentLoaded", function() {
            // No room-booking tab anymore; nothing to restore here.
        });
    </script>
</body>
</html>
