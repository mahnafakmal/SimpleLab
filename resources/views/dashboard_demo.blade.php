@extends('layouts.simplelab')

@section('content')
<div class="dashboard-grid">
    <div>
        <div class="cards">
            <div class="card stat">
                <div class="label">Total Alat</div>
                <div class="num">{{ $total ?? 0 }}</div>
                <i class="fa-solid fa-box icon"></i>
            </div>
            <div class="card stat">
                <div class="label">Dipinjam</div>
                <div class="num">{{ $borrowed ?? 0 }}</div>
                <i class="fa-solid fa-hand-holding icon"></i>
            </div>
            <div class="card stat">
                <div class="label">Tersedia</div>
                <div class="num">{{ $available ?? 0 }}</div>
                <i class="fa-solid fa-check icon"></i>
            </div>
            <div class="card stat">
                <div class="label">Rusak</div>
                <div class="num">{{ $broken ?? 0 }}</div>
                <i class="fa-solid fa-wrench icon"></i>
            </div>
        </div>

        <div class="activity" style="margin-top:16px">
            <h3>Riwayat Aktivitas Terbaru</h3>
            <table class="table">
                <thead><tr><th>Tanggal</th><th>Nama Barang</th><th>User</th><th>Status</th></tr></thead>
                <tbody>
                    @if(!empty($recent) && $recent->count())
                        @foreach($recent as $r)
                            <tr>
                                <td>{{ $r->created_at->format('d/m/Y') }}</td>
                                <td>{{ optional($r->barang)->name ?? '—' }}</td>
                                <td>{{ optional($r->user)->name ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $r->status == 'dipinjam' ? 'borrowed' : 'available' }}">{{ ucfirst($r->status ?? 'dipinjam') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="4">Belum ada aktivitas</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <div class="chart-box">
            <h3>Grafik Peminjaman Bulanan</h3>
            <canvas id="peminjamanChart" height="220"></canvas>
            <script>
                window.DEMO_CHART_DATA = {
                    labels: <?php echo json_encode($chartLabels ?? []); ?>,
                    data: <?php echo json_encode($chartData ?? []); ?>
                };
            </script>
        </div>

        <div class="activity" style="margin-top:16px">
            <h3>Peminjaman Terlambat</h3>
            <div>2 barang terlambat dikembalikan</div>
        </div>
    </div>
</div>
@endsection
