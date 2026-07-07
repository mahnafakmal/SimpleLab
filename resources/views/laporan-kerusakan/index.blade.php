@extends('layouts.app-enhanced')

@section('title', 'Laporan Kerusakan Barang - SIMPLELAB')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Left: Form to Report Damage -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1" style="color: var(--unimus-primary);">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-1"></i> Laporkan Kerusakan Alat
                    </h5>
                    <p class="text-muted small mb-0">Laporkan jika Anda menemukan barang laboratorium yang rusak atau bermasalah.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('laporan.kerusakan.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="barang_id" class="form-label fw-semibold text-secondary small">Pilih Alat / Barang Lab</label>
                            <select name="barang_id" id="barang_id" class="form-select" style="border-radius: 8px;" required>
                                <option value="">-- Pilih Alat --</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }} (Status: {{ ucfirst($b->status) }} | Kondisi: {{ $b->kondisi }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="deskripsi" class="form-label fw-semibold text-secondary small">Deskripsi Kerusakan</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="5" placeholder="Jelaskan secara rinci kerusakan alat tersebut (misal: port USB longgar, layar tidak menyala, dll.)..." style="border-radius: 8px;" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 fw-bold py-2" style="border-radius: 8px;">
                            <i class="bi bi-send-fill me-1"></i> Kirim Laporan Kerusakan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right: List of Previous Damage Reports -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1" style="color: var(--unimus-primary);">
                        <i class="bi bi-clipboard-data-fill text-primary me-1"></i> Riwayat Laporan Kerusakan Saya
                    </h5>
                    <p class="text-muted small mb-0">Daftar laporan kerusakan yang telah Anda kirim beserta status tindak lanjutnya.</p>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive" style="border-radius: 12px; border: 1px solid #f1f5f9;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th>Alat / Barang</th>
                                    <th>Deskripsi Kerusakan</th>
                                    <th>Status</th>
                                    <th>Tanggal Lapor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $report->barang->name ?? 'Barang Terhapus' }}</span>
                                            <div class="small text-muted">{{ $report->barang->kategori ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-muted small" style="max-width: 250px; white-space: normal;">{{ $report->deskripsi }}</p>
                                        </td>
                                        <td>
                                            @if($report->status === 'pending')
                                                <span class="badge bg-warning text-dark px-2.5 py-1" style="border-radius: 30px;">Pending</span>
                                            @elseif($report->status === 'proses')
                                                <span class="badge bg-info text-white px-2.5 py-1" style="border-radius: 30px;">Proses</span>
                                            @else
                                                <span class="badge bg-success text-white px-2.5 py-1" style="border-radius: 30px;">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="small text-muted">{{ $report->created_at ? $report->created_at->format('d/m/Y H:i') : '-' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                            Belum ada laporan kerusakan yang diajukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
