<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Mahasiswa - SimpleLab</title>
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
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangs as $barang)
                                    <tr class="barang-row" data-name="{{ strtolower($barang->name) }}" data-kategori="{{ strtolower($barang->kategori) }}" data-status="{{ $barang->status }}">
                                        <td style="font-weight: 600; color: #0f172a;">{{ $barang->name }}</td>
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 2rem;">
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
                            <h3>Peminjaman & Pemesanan</h3>
                        </div>
                    </div>

                    <!-- Tabs to switch forms -->
                    <div class="form-tabs">
                        <button type="button" id="tabBtnAlat" class="form-tab-btn active" onclick="switchFormTab('alatForm', this)">Pinjam Alat</button>
                        <button type="button" id="tabBtnRuang" class="form-tab-btn" onclick="switchFormTab('ruangForm', this)">Booking Ruang</button>
                    </div>

                    <!-- Form 1: Borrow Equipment -->
                    <form id="alatForm" class="booking-form active" action="{{ route('web.peminjaman.alat') }}" method="POST">
                        @csrf
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="barang_id">Pilih Alat Lab (Tersedia)</label>
                            <select name="barang_id" id="barang_id" class="form-control-custom" required>
                                <option value="">-- Pilih Alat --</option>
                                @foreach($barangs->where('status', 'available') as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->kategori }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-submit-booking">
                            <i data-lucide="box" style="width: 16px; height: 16px;"></i>
                            Pinjam Alat Sekarang
                        </button>
                    </form>

                    <!-- Form 2: Book Lab Room -->
                    <form id="ruangForm" class="booking-form" action="{{ route('web.peminjaman.ruangan') }}" method="POST">
                        @csrf
                        <div class="form-group" style="margin-bottom: 0.75rem;">
                            <label for="nama_ruangan">Pilih Ruangan Lab</label>
                            <select name="nama_ruangan" id="nama_ruangan" class="form-control-custom" required>
                                <option value="Ruang Utama Lab IoT" {{ old('nama_ruangan') === 'Ruang Utama Lab IoT' ? 'selected' : '' }}>Ruang Utama Lab IoT</option>
                                <option value="Ruang Embedded System" {{ old('nama_ruangan') === 'Ruang Embedded System' ? 'selected' : '' }}>Ruang Embedded System</option>
                                <option value="Ruang Server & Network" {{ old('nama_ruangan') === 'Ruang Server & Network' ? 'selected' : '' }}>Ruang Server & Network</option>
                                <option value="Ruang Riset Mandiri" {{ old('nama_ruangan') === 'Ruang Riset Mandiri' ? 'selected' : '' }}>Ruang Riset Mandiri</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0.75rem;">
                            <label for="tanggal">Tanggal Booking</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control-custom" min="{{ date('Y-m-d') }}" required value="{{ old('tanggal') ?? date('Y-m-d') }}">
                        </div>
                        <div class="form-row-2" style="margin-bottom: 0.75rem;">
                            <div class="form-group">
                                <label for="jam_mulai">Jam Mulai</label>
                                <select name="jam_mulai" id="jam_mulai" class="form-control-custom" required>
                                    @for($i = 8; $i <= 17; $i++)
                                        @php $time = sprintf('%02d:00', $i); @endphp
                                        <option value="{{ $time }}" {{ old('jam_mulai') === $time ? 'selected' : '' }}>{{ $time }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="jam_selesai">Jam Selesai</label>
                                <select name="jam_selesai" id="jam_selesai" class="form-control-custom" required>
                                    @for($i = 9; $i <= 18; $i++)
                                        @php $time = sprintf('%02d:00', $i); @endphp
                                        <option value="{{ $time }}" {{ old('jam_selesai') === $time ? 'selected' : '' }}>{{ $time }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="keperluan">Keperluan Kegiatan</label>
                            <textarea name="keperluan" id="keperluan" class="form-control-custom textarea-custom" placeholder="Contoh: Melakukan penelitian IoT praktikum mandiri..." required>{{ old('keperluan') }}</textarea>
                        </div>
                        <button type="submit" class="btn-submit-booking">
                            <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                            Booking Ruang Sekarang
                        </button>
                    </form>
                </div>

                <!-- Lab Schedule Section -->
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-title">
                            <i data-lucide="calendar"></i>
                            <h3>Jadwal Penggunaan Lab</h3>
                        </div>
                    </div>

                    <div class="schedule-list">
                        @php
                            $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                            // build map by day and hour (HH)
                            $map = [];
                            foreach($jadwalLabs as $j) {
                                $hour = str_pad(substr($j->jam_mulai,0,2),2,'0',STR_PAD_LEFT);
                                $map[$j->hari][$hour][] = $j;
                            }
                        @endphp

                        <div class="table-responsive">
                            <table class="schedule-grid">
                                <thead>
                                    <tr>
                                        <th>Jam \ Hari</th>
                                        @foreach($days as $d)
                                            <th>{{ $d }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($h = 7; $h <= 21; $h++)
                                        @php $hh = sprintf('%02d:00', $h); $hkey = sprintf('%02d', $h); @endphp
                                        <tr>
                                            <td style="font-weight:700;">{{ $hh }}</td>
                                            @foreach($days as $d)
                                                <td>
                                                    @if(isset($map[$d][$hkey]))
                                                        @foreach($map[$d][$hkey] as $entry)
                                                            <div style="padding:6px;border-radius:6px;background:#f8fafc;margin-bottom:6px;">
                                                                <div style="font-weight:600">{{ $entry->mata_kuliah }}</div>
                                                                <div style="font-size:12px;color:#64748b;">{{ $entry->jam_mulai }} - {{ $entry->jam_selesai }} • {{ $entry->kelas }}</div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span style="color:#94a3b8;font-size:13px;">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

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
                            <div class="loan-item">
                                <div class="loan-item-info">
                                    <h4>{{ $pinjam->barang->name }}</h4>
                                    <p>Dipinjam: {{ \Carbon\Carbon::parse($pinjam->started_at)->isoFormat('D MMM YYYY, HH:mm') }} WIB</p>
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

                    <!-- Active Room Bookings -->
                    <div class="loan-list">
                        <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); border-left: 3px solid #10b981; padding-left: 6px; margin-bottom: 0.5rem;">Booking Ruangan</h4>
                        @forelse($peminjamanRuanganSaya as $booking)
                            <div class="loan-item">
                                <div class="loan-item-info">
                                    <h4>{{ $booking->nama_ruangan }}</h4>
                                    <p>Jadwal: {{ \Carbon\Carbon::parse($booking->tanggal)->isoFormat('D MMM YYYY') }} ({{ $booking->jam_mulai }} - {{ $booking->jam_selesai }})</p>
                                    <p style="font-size: 0.7rem; color: #10b981; margin-top: 2px;">Keperluan: "{{ $booking->keperluan }}"</p>
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.35rem;">
                                    @if($booking->status === 'approved')
                                        <span class="badge badge-success" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">Disetujui</span>
                                        <form action="{{ route('web.cancel.ruangan', $booking->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="cancel-booking-btn" style="color: var(--danger);">Batalkan</button>
                                        </form>
                                    @else
                                        <span class="badge badge-gray">{{ $booking->status }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-placeholder" style="padding: 1rem;">
                                <p style="font-size: 0.8rem;">Tidak ada booking ruangan aktif.</p>
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

        // Restore active tab based on old/error states if needed
        document.addEventListener("DOMContentLoaded", function() {
            @if($errors->has('nama_ruangan') || $errors->has('tanggal') || $errors->has('jam_mulai') || $errors->has('jam_selesai') || $errors->has('keperluan') || session()->has('error') && str_contains(session('error'), 'ruangan'))
                document.getElementById('tabBtnRuang').click();
            @endif
        });
    </script>
</body>
</html>
