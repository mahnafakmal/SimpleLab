@extends('layouts.app-enhanced')

@section('title', 'Riwayat Peminjaman Saya - SimpleLab')

@section('content')
<div class="container-fluid py-4">
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-8">
            <h3 class="fw-bold mb-1" style="color: var(--unimus-primary);">
                <i class="bi bi-clock-history me-1"></i> Riwayat Peminjaman Saya
            </h3>
            <p class="text-muted small mb-0">Daftar seluruh transaksi peminjaman alat laboratorium Anda baik yang aktif maupun selesai.</p>
        </div>
        <div class="col-md-4 text-md-end d-flex gap-2 justify-content-md-end">
            @if($overdueCount > 0)
                <a href="{{ route('history.overdue') }}" class="btn btn-danger btn-sm d-flex align-items-center gap-1" style="border-radius: 20px;">
                    <i class="bi bi-exclamation-triangle-fill"></i> Terlambat ({{ $overdueCount }})
                </a>
            @endif
            <a href="{{ route('history.export-pdf') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1" style="border-radius: 20px;">
                <i class="bi bi-file-pdf"></i> Ekspor PDF
            </a>
        </div>
    </div>

    <!-- Active Loans -->
    @if($activeLoans->count() > 0)
        <div class="mb-5">
            <h5 class="fw-bold mb-3" style="color: var(--unimus-primary);">
                <i class="bi bi-play-circle text-warning me-1"></i> Peminjaman Aktif ({{ $activeLoans->count() }})
            </h5>
            <div class="row g-3">
                @foreach($activeLoans as $loan)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-start border-warning border-4 shadow-sm position-relative" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3">Aktif</span>
                                <h6 class="fw-bold mb-1" style="color: var(--unimus-primary); max-width: 80%;">{{ $loan->barang->name }}</h6>
                                <small class="text-muted d-block mb-3">Kategori: {{ $loan->barang->kategori }}</small>
                                <div class="row g-2 pt-2 border-top">
                                    <div class="col-6">
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Tanggal Pinjam</small>
                                        <span class="fw-semibold text-dark" style="font-size: 0.85rem;">{{ $loan->started_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Batas Kembali</small>
                                        <span class="fw-semibold text-dark" style="font-size: 0.85rem;">
                                            {{ $loan->due_date ? $loan->due_date->format('d/m/Y') : 'Tidak ada' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    @if($loan->isOverdue())
                                        <div class="alert alert-danger py-1 px-2 mb-0 mt-2 small text-center" style="border-radius: 6px;">
                                            <i class="bi bi-exclamation-octagon"></i> Terlambat {{ $loan->getDaysOverdue() }} hari!
                                        </div>
                                    @else
                                        @php
                                            $daysLeft = now()->diffInDays($loan->due_date, false);
                                        @endphp
                                        <div class="alert alert-info py-1 px-2 mb-0 mt-2 small text-center" style="border-radius: 6px; background-color: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.1); color: var(--unimus-accent);">
                                            <i class="bi bi-clock"></i> {{ $daysLeft >= 0 ? $daysLeft . ' hari lagi' : 'Hari ini' }}
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <a href="{{ route('history.show', $loan->id) }}" class="btn btn-outline-primary btn-sm w-50">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('laporan.kerusakan.index') }}?barang_id={{ $loan->barang_id }}" class="btn btn-outline-danger btn-sm w-50">
                                        <i class="bi bi-exclamation-triangle"></i> Lapor Rusak
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Completed Loans -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0" style="color: var(--unimus-primary);">Peminjaman Selesai</h5>
            <span class="badge bg-light text-dark px-2.5 py-1.5" style="border-radius: 30px;">Total: {{ $completedLoans->total() }}</span>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive" style="border-radius: 12px; border: 1px solid #f1f5f9;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>Barang / Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Durasi Pinjam</th>
                            <th>Kondisi Kembali</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($completedLoans as $loan)
                            <tr>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $loan->barang->name ?? 'Barang Terhapus' }}</span>
                                    <div class="small text-muted">Kategori: {{ $loan->barang->kategori ?? '-' }}</div>
                                </td>
                                <td>{{ $loan->started_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $loan->returned_at ? $loan->returned_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    @if($loan->returned_at)
                                        {{ $loan->started_at->diffInDays($loan->returned_at) }} hari
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if(strtolower($loan->barang->kondisi ?? '') === 'baik')
                                        <span class="badge bg-success-subtle text-success px-2.5 py-1.5" style="border-radius: 30px;">Baik</span>
                                    @elseif(strtolower($loan->barang->kondisi ?? '') === 'diperbaiki' || strtolower($loan->barang->kondisi ?? '') === 'cacat')
                                        <span class="badge bg-warning-subtle text-warning px-2.5 py-1.5" style="border-radius: 30px;">Diperbaiki / Cacat</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5" style="border-radius: 30px;">Rusak</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('history.show', $loan->id) }}" class="btn btn-sm btn-outline-primary px-3" style="border-radius: 20px;">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                    Belum ada riwayat peminjaman yang selesai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $completedLoans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
