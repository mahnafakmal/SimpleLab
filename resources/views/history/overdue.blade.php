@extends('layouts.app-enhanced')

@section('title', 'Peminjaman Terlambat - SimpleLab')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('history.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 20px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-danger text-white border-0 pt-4 px-4 pb-3" style="border-radius: 16px 16px 0 0;">
            <h5 class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-1"></i> Daftar Barang Terlambat Dikembalikan</h5>
            <p class="mb-0 small text-white-50">Mohon segera kembalikan barang-barang berikut ke laboratorium untuk menghindari sanksi.</p>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive" style="border-radius: 12px; border: 1px solid #f1f5f9;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>Barang / Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tenggat Waktu</th>
                            <th>Jumlah Hari Keterlambatan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overdueLoans as $loan)
                            <tr>
                                <td>
                                    <span class="fw-bold text-danger">{{ $loan->barang->name ?? 'Barang Terhapus' }}</span>
                                    <div class="small text-muted">Kategori: {{ $loan->barang->kategori ?? '-' }}</div>
                                </td>
                                <td>{{ $loan->started_at->format('d/m/Y H:i') }}</td>
                                <td><strong class="text-danger">{{ $loan->due_date ? $loan->due_date->format('d/m/Y') : '-' }}</strong></td>
                                <td>
                                    <span class="badge bg-danger px-2.5 py-1.5" style="border-radius: 30px;">Terlambat {{ $loan->getDaysOverdue() }} hari</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('equipment.return') }}" class="btn btn-sm btn-danger px-3 fw-semibold" style="border-radius: 20px;">
                                        <i class="bi bi-arrow-counterclockwise"></i> Kembalikan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-check-circle fs-2 d-block mb-2 text-success"></i>
                                    Hebat! Tidak ada keterlambatan pengembalian barang saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
