@extends('layouts.app-enhanced')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-0">
                <i class="bi bi-file-earmark-text"></i> Laporan Sirkulasi Alat
            </h2>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('reports.circulation-pdf') }}?start_date={{ $startDate->format('Y-m-d') }}&end_date={{ $endDate->format('Y-m-d') }}" class="btn btn-outline-danger">
                        <i class="bi bi-file-pdf"></i> PDF
                    </a>
                    <a href="{{ route('reports.circulation-excel') }}?start_date={{ $startDate->format('Y-m-d') }}&end_date={{ $endDate->format('Y-m-d') }}" class="btn btn-outline-success">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-start border-info border-5">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-info">{{ $stats['total_loans'] ?? 0 }}</h3>
                    <small class="text-muted">Total Peminjaman</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-success border-5">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-success">{{ $stats['returned'] ?? 0 }}</h3>
                    <small class="text-muted">Dikembalikan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-warning border-5">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-warning">{{ $stats['active'] ?? 0 }}</h3>
                    <small class="text-muted">Masih Dipinjam</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-danger border-5">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-danger">{{ $stats['overdue'] ?? 0 }}</h3>
                    <small class="text-muted">Terlambat</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Detail Peminjaman</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No. Peminjaman</th>
                            <th>Peminjam</th>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali Estimasi</th>
                            <th>Tanggal Kembali Aktual</th>
                            <th>Status</th>
                            <th>Terlambat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            <tr>
                                <td><code>#{{ $loan->id }}</code></td>
                                <td>{{ $loan->user->name ?? '-' }}</td>
                                <td>{{ $loan->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $loan->started_at ? $loan->started_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>{{ $loan->due_date ? $loan->due_date->format('d/m/Y') : '-' }}</td>
                                <td>{{ $loan->returned_at ? $loan->returned_at->format('d/m/Y H:i') : 'Belum Dikembalikan' }}</td>
                                <td>
                                    @if($loan->status === 'active')
                                        <span class="badge bg-warning">Aktif</span>
                                    @else
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    @if($loan->isOverdue())
                                        <span class="badge bg-danger">{{ $loan->getDaysOverdue() }} hari</span>
                                    @else
                                        <span class="badge bg-success">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox"></i> Tidak ada data peminjaman
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
