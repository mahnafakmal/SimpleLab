<div id="ringkasan" class="tab-content active">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon-box" style="background: #f1f5f9; color: #475569;"><i data-lucide="package"></i></div>
            <a href="{{ route('barang.semua') }}" style="text-decoration:none;color:inherit;display:block;">
            <span class="label">Total Unit Alat</span>
            <span class="value">{{ $totalAssets ?? 0 }}</span>
            </a>
        </div>
        <div class="stat-card">
            <div class="icon-box" style="background: #f0fdf4; color: #22c55e;"><i data-lucide="check-circle-2"></i></div>
            <span class="label">Unit Tersedia</span>
            <a href="{{ route('barang.tersedia') }}" style="text-decoration:none;color:inherit;display:block;">
            <span class="value" style="color: #22c55e;">{{ $available ?? 0 }}</span>
            </a>
        </div>
        <div class="stat-card">
            <div class="icon-box" style="background: #eff6ff; color: #3b82f6;"><i data-lucide="clipboard-list"></i></div>
            <span class="label">Sedang Dipinjam</span>
            <a href="{{ route('barang.dipinjam') }}" style="text-decoration:none;color:inherit;display:block;">
            <span class="value" style="color: #3b82f6;">{{ $borrowed ?? 0 }}</span>
            </a>
        </div>
        <div class="stat-card">
            <div class="icon-box" style="background: #fef2f2; color: #ef4444;"><i data-lucide="alert-triangle"></i></div>
            <span class="label">Rusak/Maintenance</span>
            <span class="value" style="color: #ef4444;">0</span>
        </div>
        <div class="stat-card">
            <div class="icon-box" style="background: #fffbeb; color: #f59e0b;"><i data-lucide="clock"></i></div>
            <span class="label">Permintaan Pending</span>
            <span class="value" style="color: #f59e0b;">0</span>
        </div>
        <div class="stat-card">
            <div class="icon-box" style="background: #f0f9ff; color: #0ea5e9;"><i data-lucide="users"></i></div>
            <span class="label">Total Mahasiswa</span>
            <span class="value" style="color: #0ea5e9;">{{ $totalMahasiswa ?? $users->count() ?? 0 }}</span>
        </div>
        <div class="stat-card">
            <div class="icon-box" style="background: #fff7ed; color: #fb923c;"><i data-lucide="user-check"></i></div>
            <span class="label">Total Dosen</span>
            <span class="value" style="color: #fb923c;">{{ $totalDosen ?? 0 }}</span>
        </div>
    </div>

    <h3 class="section-title">Peminjaman Terbaru</h3>
    <div class="empty-state">
        <i data-lucide="clipboard-list" size="48"></i>
        <p>Belum ada peminjaman</p>
    </div>
</div>
