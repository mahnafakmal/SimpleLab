@extends('layouts.app-enhanced')

@section('title', 'Beranda Mahasiswa - SimpleLab')

@section('css')
<style>
    .header-intro {
        margin-bottom: 2rem;
    }

    .header-intro h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--unimus-primary);
        margin-bottom: 0.5rem;
    }

    .header-intro p {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card-custom {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
        color: inherit;
        border-left: 4px solid var(--unimus-primary);
    }

    .stat-card-custom:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-card-custom.available {
        border-left-color: #28a745;
    }

    .stat-card-custom.borrowed {
        border-left-color: #ffc107;
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    .stat-value-custom {
        font-size: 2rem;
        font-weight: 700;
        color: var(--unimus-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label-custom {
        color: #666;
        font-size: 0.9rem;
    }

    .dashboard-grid-custom {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1024px) {
        .dashboard-grid-custom {
            grid-template-columns: 1fr;
        }
    }

    .alert-warning-custom {
        background-color: #FEF3C7;
        border: 2px solid #F59E0B;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .alert-warning-custom h3 {
        color: #D97706;
        font-size: 0.95rem;
        font-weight: 600;
        margin: 0 0 0.5rem;
    }

    .alert-warning-custom p {
        color: #92400E;
        font-size: 0.85rem;
        margin: 0 0 0.75rem;
    }

    .overdue-item {
        padding: 0.75rem;
        border-radius: 6px;
        border-left: 3px solid #DC2626;
        margin-bottom: 0.5rem;
    }

    .overdue-item.very-overdue {
        background: #FCA5A5;
    }

    .overdue-item.moderately-overdue {
        background: #FBCFE8;
    }

    .overdue-item strong {
        color: #7C2D12;
        font-size: 0.9rem;
    }

    .overdue-item p {
        margin: 2px 0 0;
        font-size: 0.8rem;
        color: #92400E;
    }

    .overdue-badge {
        background: #DC2626;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .panel-card-custom {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .panel-header-custom {
        border-bottom: 2px solid var(--unimus-primary);
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
    }

    .panel-title-custom {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--unimus-primary);
    }

    .panel-title-custom i {
        width: 20px;
        height: 20px;
    }

    .form-tabs-custom {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .form-tab-btn-custom {
        padding: 0.75rem 1rem;
        border: none;
        background: none;
        color: #666;
        font-weight: 600;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .form-tab-btn-custom.active {
        color: var(--unimus-primary);
        border-bottom-color: var(--unimus-primary);
    }

    .form-group-custom {
        margin-bottom: 1rem;
    }

    .form-group-custom label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
    }

    .form-control-custom {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: border-color 0.3s ease;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: var(--unimus-primary);
        box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.1);
    }

    .textarea-custom {
        min-height: 80px;
        resize: vertical;
    }

    .btn-submit-custom {
        width: 100%;
        padding: 0.75rem;
        background: var(--unimus-primary);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-submit-custom:hover {
        background: var(--unimus-accent);
    }

    .btn-submit-custom.danger {
        background: #dc3545;
    }

    .btn-submit-custom.danger:hover {
        background: #c82333;
    }

    .loan-item-custom {
        display: flex;
        gap: 1rem;
        align-items: center;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 0.75rem;
        border-left: 3px solid var(--unimus-primary);
    }

    .loan-item-thumb-custom {
        width: 64px;
        height: 64px;
        flex-shrink: 0;
        border-radius: 6px;
        overflow: hidden;
    }

    .loan-item-thumb-custom img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .loan-item-info-custom {
        flex: 1;
    }

    .loan-item-info-custom h4 {
        margin: 0 0 4px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
    }

    .loan-item-info-custom p {
        margin: 0;
        font-size: 0.85rem;
        color: #666;
    }

    .loan-item-action {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.35rem;
    }

    .badge-custom {
        padding: 0.35rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-success-custom {
        background: #d4edda;
        color: #155724;
    }

    .badge-warning-custom {
        background: #fff3cd;
        color: #856404;
    }

    .badge-danger-custom {
        background: #f8d7da;
        color: #721c24;
    }

    .btn-return-custom {
        background: none;
        border: none;
        color: var(--unimus-primary);
        cursor: pointer;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.35rem 0.75rem;
        text-decoration: underline;
        transition: color 0.3s ease;
    }

    .btn-return-custom:hover {
        color: var(--unimus-accent);
    }

    .tools-bar-custom {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .search-wrapper-custom {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .search-wrapper-custom i {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: #999;
    }

    .search-input-custom {
        width: 100%;
        padding: 0.75rem 0.75rem 0.75rem 2.5rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .filter-select-custom {
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        background: white;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .table-responsive-custom {
        overflow-x: auto;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom th {
        background: var(--unimus-primary);
        color: white;
        padding: 0.75rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .table-custom td {
        padding: 0.75rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .table-custom tbody tr:hover {
        background: rgba(0, 51, 102, 0.02);
    }

    .badge-info-custom {
        background: #e0f2fe;
        color: #0369a1;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        display: inline-block;
    }

    .empty-placeholder-custom {
        text-align: center;
        padding: 2rem 1rem;
        color: #999;
    }

    .empty-placeholder-custom p {
        margin: 0;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<!-- Header Section -->
<div class="header-intro">
    <h2>Halo, {{ auth()->user()->name }}! 👋</h2>
    @if(auth()->check() && auth()->user()->role === 'dosen')
        <p>Selamat datang di portal dosen SimpleLab. Kelola jadwal, pantau permintaan peminjaman, dan tinjau aktivitas laboratorium.</p>
    @else
        <p>Selamat datang di portal praktikan SimpleLab. Pantau status peralatan, ketersediaan alat, dan jadwal praktikum Anda.</p>
    @endif
</div>

<!-- Overdue Loans Warning -->
@if($overdueLoans->count() > 0)
    <div class="alert-warning-custom">
        <h3>⚠️ Peringatan: Barang Belum Dikembalikan!</h3>
        <p>Anda memiliki <strong>{{ $overdueLoans->count() }} alat</strong> yang belum dikembalikan melewati batas waktu:</p>
        @foreach($overdueLoans as $overdueItem)
            @php
                $daysOverdue = now()->diffInDays($overdueItem->due_date, false);
                $isVeryOverdue = $daysOverdue > 7;
            @endphp
            <div class="overdue-item {{ $isVeryOverdue ? 'very-overdue' : 'moderately-overdue' }}">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>{{ $overdueItem->barang->name }}</strong>
                        <p>Seharusnya dikembalikan: <strong>{{ $overdueItem->due_date->isoFormat('D MMM YYYY, HH:mm') }} WIB</strong></p>
                    </div>
                    <div style="text-align: right; white-space: nowrap;">
                        <span class="overdue-badge">{{ abs($daysOverdue) }} hari terlambat</span>
                    </div>
                </div>
            </div>
        @endforeach
        <p style="margin: 0.75rem 0 0; color: #92400E; font-size: 0.8rem;">
            <i class="bi bi-info-circle"></i>
            Segera kembalikan alat atau hubungi admin laboratorium untuk perpanjangan.
        </p>
    </div>
@endif

<!-- Quick Stats Grid -->
<div class="stats-grid">
    <div class="stat-card-custom">
        <div class="stat-value-custom">{{ $totalAlat }}</div>
        <div class="stat-label-custom">Total Alat Lab</div>
    </div>
    <a href="{{ route('barang.tersedia') }}" class="stat-card-custom available" style="text-decoration: none; color: inherit;">
        <div class="stat-value-custom" style="color: #28a745;">{{ $alatTersedia }}</div>
        <div class="stat-label-custom">Alat Tersedia</div>
    </a>
    <a href="{{ route('barang.dipinjam') }}" class="stat-card-custom borrowed" style="text-decoration: none; color: inherit;">
        <div class="stat-value-custom" style="color: #ffc107;">{{ $alatDipinjam }}</div>
        <div class="stat-label-custom">Alat Dipinjam</div>
    </a>
</div>

<!-- Main Dashboard Grid -->
<div class="dashboard-grid-custom">
    <!-- Left Column: Equipment Info & Status -->
    <div>
        <div class="panel-card-custom">
            <div class="panel-header-custom">
                <div class="panel-title-custom">
                    <i class="bi bi-database"></i>
                    Daftar & Status Alat Lab
                </div>
            </div>

            <!-- Search & Filter Controls -->
            <div class="tools-bar-custom">
                <div class="search-wrapper-custom">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" class="search-input-custom" placeholder="Cari nama alat atau kategori..." onkeyup="filterAlat()">
                </div>
                <select id="categoryFilter" class="filter-select-custom" onchange="filterAlat()">
                    <option value="">Semua Kategori</option>
                    @foreach($barangs->pluck('kategori')->unique()->filter() as $kategori)
                        <option value="{{ $kategori }}">{{ $kategori }}</option>
                    @endforeach
                </select>
                <select id="statusFilter" class="filter-select-custom" onchange="filterAlat()">
                    <option value="">Semua Status</option>
                    <option value="available">Tersedia</option>
                    <option value="borrowed">Dipinjam</option>
                </select>
            </div>

            <!-- Equipment Table -->
            <div class="table-responsive-custom">
                <table class="table-custom" id="alatTable">
                    <thead>
                        <tr>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Kondisi</th>
                            <th>Status</th>
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
                                <td style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 40px; height: 40px; flex-shrink: 0;">
                                        @if($img)
                                            <img src="{{ asset('images/barangs/' . $img) }}" alt="{{ $barang->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <div style="width: 40px; height: 40px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                                <i class="bi bi-box" style="font-size: 0.9rem; color: #999;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <span>{{ $barang->name }}</span>
                                </td>
                                <td>
                                    <span class="badge-info-custom">{{ $barang->kategori ?? 'Umum' }}</span>
                                </td>
                                <td>
                                    @if(strtolower($barang->kondisi) === 'baik')
                                        <span class="badge-status" style="background: #d4edda; color: #155724;">
                                            <i class="bi bi-shield-check"></i> Baik
                                        </span>
                                    @else
                                        <span class="badge-status" style="background: #f8d7da; color: #721c24;">
                                            <i class="bi bi-exclamation-triangle"></i> {{ $barang->kondisi }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($barang->status === 'available')
                                        <span class="badge-status badge-available">
                                            <i class="bi bi-check-circle"></i> Tersedia
                                        </span>
                                    @else
                                        <span class="badge-status badge-borrowed">
                                            <i class="bi bi-lock"></i> Dipinjam
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if(strtolower($barang->kondisi) === 'baik')
                                        <button type="button" onclick="reportDamage({{ $barang->id }})" class="btn-return-custom">
                                            <i class="bi bi-exclamation-triangle"></i> Laporkan Rusak
                                        </button>
                                    @else
                                        <span style="font-size: 0.85rem; color: #999;">{{ $barang->kondisi }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem;">
                                    <div class="empty-placeholder-custom">
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

    <!-- Right Column: Activities -->
    <div>
                
        <!-- Activities Section -->
        <div class="panel-card-custom">
            <div class="panel-header-custom">
                <div class="panel-title-custom">
                    <i class="bi bi-arrow-left-right"></i>
                    Aktivitas Lab
                </div>
            </div>

            <!-- Tabs to switch forms -->
            <div class="form-tabs-custom">
                <button type="button" id="tabBtnAlat" class="form-tab-btn-custom active" onclick="switchFormTab('alatForm', this)">Pinjam Alat</button>
                <button type="button" id="tabBtnKerusakan" class="form-tab-btn-custom" onclick="switchFormTab('kerusakanForm', this)">Laporkan Rusak</button>
            </div>

            <!-- Form 1: Borrow Equipment -->
            <form id="alatForm" class="booking-form active" action="{{ auth()->user()->role === 'dosen' ? route('web.peminjaman.alat.dosen') : route('web.peminjaman.alat') }}" method="POST" style="display: block;">
                @csrf
                <div class="form-group-custom">
                    <label for="barang_id">Pilih Alat Lab (Tersedia)</label>
                    <select name="barang_id" id="barang_id" class="form-control-custom" required>
                        <option value="">-- Pilih Alat --</option>
                        @foreach($barangs->where('status', 'available')->where('kondisi', 'Baik') as $b)
                            <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->kategori }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-custom">
                    <label for="waktu_mulai">Waktu Mulai</label>
                    <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" class="form-control-custom" required min="{{ now()->format('Y-m-d\\TH:i') }}">
                </div>
                <div class="form-group-custom">
                    <label for="waktu_selesai">Waktu Selesai</label>
                    <input type="datetime-local" name="waktu_selesai" id="waktu_selesai" class="form-control-custom" required>
                </div>
                <button type="submit" class="btn-submit-custom">
                    <i class="bi bi-box"></i>
                    Pinjam Alat Sekarang
                </button>
            </form>

            <!-- Form 2: Report Damage -->
            <form id="kerusakanForm" class="booking-form" action="{{ route('laporan.kerusakan.store') }}" method="POST" style="display: none;">
                @csrf
                <div class="form-group-custom">
                    <label for="kerusakan_barang_id">Pilih Alat Lab</label>
                    <select name="barang_id" id="kerusakan_barang_id" class="form-control-custom" required>
                        <option value="">-- Pilih Alat --</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->id }}">{{ $b->name }} (Kondisi: {{ $b->kondisi }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-custom">
                    <label for="deskripsi_kerusakan">Deskripsi Kerusakan</label>
                    <textarea name="deskripsi" id="deskripsi_kerusakan" class="form-control-custom textarea-custom" placeholder="Jelaskan kerusakan barang secara detail..." required></textarea>
                </div>
                <button type="submit" class="btn-submit-custom danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    Kirim Laporan Kerusakan
                </button>
            </form>
        </div>

        <!-- My Loans Section -->
        <div class="panel-card-custom">
            <div class="panel-header-custom">
                <div class="panel-title-custom">
                    <i class="bi bi-clipboard-list"></i>
                    Aktivitas Saya (Aktif)
                </div>
            </div>

            <!-- Active Equipment Loans -->
            <h5 style="font-size: 0.85rem; text-transform: uppercase; color: #666; margin-bottom: 1rem; border-left: 3px solid var(--unimus-primary); padding-left: 0.75rem;">Peminjaman Alat</h5>
            @forelse($peminjamanSaya as $pinjam)
                <div class="loan-item-custom">
                    <div class="loan-item-thumb-custom">
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
                            <img src="{{ asset('images/barangs/' . $img) }}" alt="{{ $pinjam->barang->name }}">
                        @else
                            <div style="width: 100%; height: 100%; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-box" style="font-size: 1.5rem; color: #999;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="loan-item-info-custom">
                        <h4>{{ $pinjam->barang->name }}</h4>
                        <p>Dipinjam: {{ \Carbon\Carbon::parse($pinjam->started_at)->isoFormat('D MMM YYYY, HH:mm') }} WIB</p>
                        @if($pinjam->due_date)
                            <p>Tenggat: {{ $pinjam->due_date->isoFormat('D MMM YYYY, HH:mm') }} WIB</p>
                            @if($pinjam->due_date < now())
                                <p style="color: #dc3545; font-weight: 600; margin-top: 4px;">
                                    ⚠️ {{ abs(now()->diffInDays($pinjam->due_date)) }} hari terlambat!
                                </p>
                            @endif
                        @endif
                    </div>
                    <div class="loan-item-action">
                        @if($pinjam->status === 'active')
                            @if($pinjam->due_date && $pinjam->due_date < now())
                                <span class="badge-custom badge-danger-custom">Overdue</span>
                            @else
                                <span class="badge-custom badge-warning-custom">Aktif</span>
                            @endif
                            <form action="{{ route('web.pengembalian.alat', $pinjam->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn-return-custom">Kembalikan</button>
                            </form>
                        @else
                            <span class="badge-custom badge-success-custom">Selesai</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-placeholder-custom">
                    <p>Tidak ada peminjaman alat lab aktif.</p>
                </div>
            @endforelse

            <!-- My Damage Reports -->
            <h5 style="font-size: 0.85rem; text-transform: uppercase; color: #666; margin: 1.5rem 0 1rem; border-left: 3px solid #dc3545; padding-left: 0.75rem;">Laporan Kerusakan</h5>
            @forelse($laporanKerusakanSaya as $laporan)
                <div style="padding: 0.75rem; background: #f8f9fa; border-radius: 6px; margin-bottom: 0.75rem; border-left: 3px solid #ff9800;">
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <div style="flex: 1;">
                            <h5 style="margin: 0 0 4px; font-size: 0.9rem; font-weight: 600;">{{ $laporan->barang->name }}</h5>
                            <p style="margin: 0; font-size: 0.85rem; color: #666; line-height: 1.4;">{{ $laporan->deskripsi }}</p>
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
                            <p style="margin: 4px 0 0; font-size: 0.75rem; color: #999;">{{ $displayLapWhen }}</p>
                        </div>
                        <div>
                            @if($laporan->status === 'pending')
                                <span class="badge-custom badge-warning-custom">Pending</span>
                            @elseif($laporan->status === 'proses')
                                <span class="badge-custom" style="background: #e0f2fe; color: #0369a1;">Proses</span>
                            @else
                                <span class="badge-custom badge-success-custom">Selesai</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-placeholder-custom">
                    <p>Belum ada laporan kerusakan.</p>
                </div>
            @endforelse
        </div>
    </div>

@endsection

@section('js')
<script>
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

    // Switch between Borrow Equipment and Report Damage forms
    function switchFormTab(formId, btnEl) {
        // Remove active classes
        document.querySelectorAll('.form-tab-btn-custom').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.booking-form').forEach(form => form.style.display = 'none');

        // Add active class to selected tab button
        btnEl.classList.add('active');

        // Show selected form
        document.getElementById(formId).style.display = 'block';
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
</script>
@endsection
