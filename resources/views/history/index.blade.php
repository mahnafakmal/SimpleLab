@extends('layouts.app-enhanced')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="bi bi-hourglass-split"></i> Riwayat Peminjaman Saya
            </h2>
        </div>
        <div class="col-md-4 text-end">
            @if($overdueCount > 0)
                <a href="{{ route('history.overdue') }}" class="btn btn-danger me-2">
                    <i class="bi bi-exclamation-circle"></i> Terlambat ({{ $overdueCount }})
                </a>
            @endif
            <a href="{{ route('history.export-pdf') }}" class="btn btn-outline-secondary">
                <i class="bi bi-download"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Active Loans -->
    @if($activeLoans->count() > 0)
        <div class="mb-4">
            <h5 class="mb-3">
                <i class="bi bi-exclamation-triangle-fill text-warning"></i> Peminjaman Aktif ({{ $activeLoans->count() }})
            </h5>
            <div class="row">
                @foreach($activeLoans as $loan)
                    <div class="col-md-6 mb-3">
                        <div class="card border-start border-warning border-5">
                            <div class="card-body">
                                <h6 class="card-title">{{ $loan->barang->nama_barang }}</h6>
                                <small class="text-muted">Kode: {{ $loan->barang->kode_barang }}</small>
                                <hr>
                                <div class="row">
                                    <div class="col">
                                        <small class="text-muted">Tanggal Pinjam</small>
                                        <p class="mb-0"><strong>{{ $loan->started_at->format('d/m/Y H:i') }}</strong></p>
                                    </div>
                                    <div class="col">
                                        <small class="text-muted">Estimasi Kembali</small>
                                        <p class="mb-0">
                                            <strong>{{ $loan->due_date->format('d/m/Y') }}</strong>
                                            @if($loan->isOverdue())
                                                <span class="badge bg-danger">TERLAMBAT {{ $loan->getDaysOverdue() }} hari</span>
                                            @else
                                                <span class="badge bg-info">{{ $loan->due_date->diffInDays(now()) }} hari lagi</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Completed Loans -->
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Peminjaman Selesai</h5>
            <small class="text-muted">Total: {{ $completedLoans->total() }}</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Durasi</th>
                            <th>Kondisi Kembali</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($completedLoans as $loan)
                            <tr>
                                <td>
                                    <strong>{{ $loan->barang->nama_barang }}</strong><br>
                                    <small class="text-muted">{{ $loan->barang->kategori }}</small>
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
                                    @if($loan->barang->kondisi === 'baik')
                                        <span class="badge bg-success">Baik</span>
                                    @elseif($loan->barang->kondisi === 'cacat')
                                        <span class="badge bg-warning">Cacat</span>
                                    @else
                                        <span class="badge bg-danger">Rusak</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('history.show', $loan->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox"></i> Belum ada riwayat peminjaman selesai
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
