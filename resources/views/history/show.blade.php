@extends('layouts.app-enhanced')

@section('title', 'Detail Peminjaman - SIMPLELAB')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('history.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 20px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="row g-4">
        <!-- Left: Detail Info Card -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <span class="badge bg-primary mb-2">ID Peminjaman: #{{ $peminjaman->id }}</span>
                    <h4 class="fw-bold mb-1" style="color: var(--unimus-primary);">Detail Transaksi Peminjaman</h4>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive border-0">
                        <table class="table align-middle">
                            <tbody>
                                <tr>
                                    <td class="text-secondary small fw-semibold border-0 py-3" style="width: 35%;">Nama Barang / Alat</td>
                                    <td class="fw-bold text-dark border-0 py-3">{{ $peminjaman->barang->name ?? 'Barang Terhapus' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary small fw-semibold py-3">Kategori</td>
                                    <td class="text-dark py-3">{{ $peminjaman->barang->kategori ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary small fw-semibold py-3">Peminjam</td>
                                    <td class="text-dark py-3">
                                        <span class="fw-semibold">{{ $peminjaman->user->name ?? 'User Terhapus' }}</span>
                                        <div class="small text-muted">{{ $peminjaman->user->email ?? '' }} · NIM: {{ $peminjaman->user->nim ?? '-' }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary small fw-semibold py-3">Waktu Mulai Pinjam</td>
                                    <td class="text-dark py-3">{{ $peminjaman->started_at ? $peminjaman->started_at->format('d M Y H:i') : '-' }} WIB</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary small fw-semibold py-3">Batas Waktu Pengembalian</td>
                                    <td class="text-dark py-3">{{ $peminjaman->due_date ? $peminjaman->due_date->format('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary small fw-semibold py-3">Waktu Dikembalikan</td>
                                    <td class="text-dark py-3">
                                        @if($peminjaman->returned_at)
                                            <span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> {{ $peminjaman->returned_at->format('d M Y H:i') }} WIB</span>
                                        @else
                                            <span class="text-warning fw-semibold"><i class="bi bi-clock-history me-1"></i> Belum Dikembalikan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary small fw-semibold py-3">Status Transaksi</td>
                                    <td class="py-3">
                                        @if($peminjaman->status === 'active')
                                            <span class="badge bg-warning text-dark px-3 py-1.5" style="border-radius: 30px;">Aktif / Sedang Dipinjam</span>
                                        @elseif($peminjaman->status === 'completed' || $peminjaman->status === 'returned')
                                            <span class="badge bg-success text-white px-3 py-1.5" style="border-radius: 30px;">Selesai / Dikembalikan</span>
                                        @else
                                            <span class="badge bg-secondary text-white px-3 py-1.5" style="border-radius: 30px;">{{ ucfirst($peminjaman->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Actions & Damage Report Section -->
        <div class="col-lg-5">
            <!-- Damage Report Panel -->
            @if($peminjaman->status === 'active')
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold mb-1 text-danger">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Laporkan Alat Bermasalah
                        </h5>
                        <p class="text-muted small mb-0">Apakah ada kerusakan pada alat yang Anda pinjam ini? Sampaikan kendala Anda di sini.</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('laporan.kerusakan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="barang_id" value="{{ $peminjaman->barang_id }}">
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label fw-semibold text-secondary small">Jelaskan Kerusakan / Masalah</label>
                                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" placeholder="Contoh: Lampu indikator tidak menyala saat dihubungkan ke daya..." required style="border-radius: 8px;"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 fw-bold" style="border-radius: 8px;">
                                <i class="bi bi-send-fill me-1"></i> Kirim Laporan Kerusakan
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Damage Report Status if already reported -->
            @if($peminjaman->laporanKerusakan)
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold mb-1 text-dark">
                            <i class="bi bi-shield-exclamation text-danger me-1"></i> Status Laporan Kerusakan
                        </h5>
                        <p class="text-muted small mb-0">Rincian laporan kerusakan yang dikaitkan dengan barang ini.</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="p-3 bg-light rounded mb-3" style="border-radius: 8px;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark" style="font-size: 0.95rem;">Laporan Kerusakan #{{ $peminjaman->laporanKerusakan->id }}</span>
                                @if($peminjaman->laporanKerusakan->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($peminjaman->laporanKerusakan->status === 'proses')
                                    <span class="badge bg-info text-white">Proses</span>
                                @else
                                    <span class="badge bg-success text-white">Selesai</span>
                                @endif
                            </div>
                            <p class="mb-0 text-muted small">{{ $peminjaman->laporanKerusakan->deskripsi }}</p>
                        </div>
                        <small class="text-secondary d-block"><i class="bi bi-clock"></i> Dilaporkan: {{ $peminjaman->laporanKerusakan->created_at ? $peminjaman->laporanKerusakan->created_at->format('d M Y H:i') : '-' }}</small>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
