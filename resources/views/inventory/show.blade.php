@extends('layouts.app-enhanced')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-0">{{ $barang->nama_barang }}</h2>
            <small class="text-muted">Kode: {{ $barang->kode_barang }}</small>
        </div>
        <div class="col text-end">
            <a href="{{ route('inventory.edit', $barang->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Info Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Barang</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Kategori:</strong>
                            <p>{{ $barang->kategori }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Status:</strong>
                            <p>
                                @switch($barang->status)
                                    @case('available')
                                        <span class="badge bg-success">Tersedia</span>
                                        @break
                                    @case('borrowed')
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                        @break
                                    @case('rusak')
                                        <span class="badge bg-danger">Rusak</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Kondisi:</strong>
                            <p>
                                @switch($barang->kondisi)
                                    @case('baik')
                                        <span class="badge bg-success">Baik</span>
                                        @break
                                    @case('cacat')
                                        <span class="badge bg-warning">Cacat</span>
                                        @break
                                    @case('rusak')
                                        <span class="badge bg-danger">Rusak</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Lokasi:</strong>
                            <p>{{ $barang->lokasi ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Spesifikasi:</strong>
                        <p>{{ $barang->spesifikasi ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Deskripsi:</strong>
                        <p>{{ $barang->deskripsi ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Riwayat Peminjaman -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Riwayat Peminjaman (10 Terakhir)</h5>
                </div>
                <div class="card-body">
                    @if($riwayatPeminjaman->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Peminjam</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayatPeminjaman as $loan)
                                        <tr>
                                            <td>{{ $loan->user->name ?? '-' }}</td>
                                            <td>{{ $loan->started_at->format('d/m/Y H:i') ?? '-' }}</td>
                                            <td>{{ $loan->returned_at ? $loan->returned_at->format('d/m/Y H:i') : 'Belum Dikembalikan' }}</td>
                                            <td>
                                                @if($loan->returned_at)
                                                    {{ $loan->started_at->diffInDays($loan->returned_at) }} hari
                                                @else
                                                    {{ $loan->started_at->diffInDays(now()) }} hari
                                                @endif
                                            </td>
                                            <td>
                                                @if($loan->status === 'active')
                                                    <span class="badge bg-warning">Dipinjam</span>
                                                @else
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> Belum ada riwayat peminjaman
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Foto -->
            @if($barang->image)
                <div class="card mb-4">
                    <img src="{{ asset('storage/' . $barang->image) }}" class="card-img-top" alt="{{ $barang->nama_barang }}">
                </div>
            @endif

            <!-- Stats -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Total Peminjaman</small>
                        <h4 class="mb-0">{{ $barang->peminjaman_count ?? 0 }}</h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Laporan Kerusakan</small>
                        <h4 class="mb-0 text-danger">{{ $barang->laporan_kerusakan_count ?? 0 }}</h4>
                    </div>
                    @if($barang->tagRfid)
                        <div>
                            <small class="text-muted">Tag RFID</small>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ $barang->tagRfid->tag_type }}</span>
                                <code>{{ substr($barang->tagRfid->uid, 0, 8) }}...</code>
                            </p>
                        </div>
                    @else
                        <div class="alert alert-warning alert-sm mb-0">
                            <small><i class="bi bi-exclamation-triangle"></i> Belum memiliki tag RFID</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
