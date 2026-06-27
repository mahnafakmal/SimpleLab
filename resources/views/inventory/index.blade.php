@extends('layouts.app-enhanced')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="bi bi-box2-heart"></i> Manajemen Inventaris Barang
            </h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('inventory.create') }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle"></i> Tambah Barang
            </a>
            <a href="{{ route('inventory.export-csv') }}" class="btn btn-outline-secondary">
                <i class="bi bi-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-start border-primary border-5">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-primary">{{ $stats['total'] ?? 0 }}</h3>
                    <small class="text-muted">Total Barang</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-success border-5">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-success">{{ $stats['available'] ?? 0 }}</h3>
                    <small class="text-muted">Tersedia</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-warning border-5">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-warning">{{ $stats['borrowed'] ?? 0 }}</h3>
                    <small class="text-muted">Dipinjam</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-danger border-5">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-danger">{{ $stats['damaged'] ?? 0 }}</h3>
                    <small class="text-muted">Rusak</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Barang Laboratorium</h5>
            <div>
                <input type="text" id="search-input" class="form-control form-control-sm" style="width: 250px;" placeholder="Cari barang...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="inventory-table">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Kondisi</th>
                            <th>Lokasi</th>
                            <th>Peminjaman</th>
                            <th>Kerusakan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $barang)
                            <tr>
                                <td>
                                    <code>{{ $barang->kode_barang }}</code>
                                </td>
                                <td>
                                    <strong>{{ $barang->nama_barang }}</strong><br>
                                    <small class="text-muted">{{ substr($barang->spesifikasi ?? '-', 0, 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $barang->kategori }}</span>
                                </td>
                                <td>
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
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    <small>{{ $barang->lokasi ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $barang->peminjaman_count ?? 0 }} kali</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $barang->laporan_kerusakan_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('inventory.show', $barang->id) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('inventory.edit', $barang->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('inventory.destroy', $barang->id) }}" style="display:inline;" onsubmit="return confirm('Hapus barang ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox"></i> Belum ada barang terdaftar
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $barangs->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    // Simple search functionality
    document.getElementById('search-input').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const table = document.getElementById('inventory-table');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endsection
