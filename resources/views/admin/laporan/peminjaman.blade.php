<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/css/dashboard.css')
    @else
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @endif
</head>
<body>
    <nav class="top-nav">
        <div class="logo-area">
            <div class="logo-icon"><i data-lucide="flask-conical"></i></div>
            <div class="logo-text"><h1>SimpleLab</h1><p>Admin - Laporan</p></div>
        </div>
        <div class="user-area">
            <span class="badge-admin">Admin</span>
            <span class="user-email">{{ auth()->user()->email }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">@csrf<button type="submit" class="logout-btn">Logout</button></form>
        </div>
    </nav>

    <main class="main-container">
        <div class="header-section">
            <div class="title-row">
                <h2>Riwayat Peminjaman Alat</h2>
                <span class="meta-count">{{ $peminjaman->count() }} entri</span>
            </div>
            <p class="subtitle">Daftar lengkap peminjaman alat (termasuk yang sudah dikembalikan).</p>
            <div style="margin-top:12px">
                <a href="{{ route('admin.laporan.peminjaman.export-excel') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:8px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export Excel
                </a>
            </div>
        </div>

        <div class="list-card">
            @if($peminjaman->isEmpty())
                <div class="empty-state"><i data-lucide="clipboard-list" size="48"></i><p>Belum ada riwayat peminjaman.</p></div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>User</th>
                                <th>Barang</th>
                                <th>UID Tag</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjaman as $p)
                                <tr>
                                    @php
                                        $displayPWhen = '-';
                                        if(!empty($p->created_at)) {
                                            try {
                                                if($p->created_at instanceof \Illuminate\Support\Carbon) {
                                                    $displayPWhen = $p->created_at->isoFormat('D MMM YYYY, HH:mm');
                                                } else {
                                                    $displayPWhen = \Illuminate\Support\Carbon::parse($p->created_at)->isoFormat('D MMM YYYY, HH:mm');
                                                }
                                            } catch (\Exception $e) {
                                                $displayPWhen = '-';
                                            }
                                        }
                                    @endphp
                                    <td>{{ $displayPWhen }} WIB</td>
                                    <td>{{ $p->user->name ?? 'User#'.$p->user_id }}</td>
                                    <td>{{ $p->barang->name ?? 'Barang Terhapus' }}</td>
                                    <td>{{ $p->tagRfid->uid ?? '-' }}</td>
                                    <td>
                                        @if($p->status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($p->status === 'borrowed' || $p->status === 'dipinjam')
                                            <span class="badge badge-info">Dipinjam</span>
                                        @else
                                            <span class="badge badge-success">{{ ucfirst($p->status) }}</span>
                                        @endif
                                    </td>
                                    <td style="max-width:320px">{{ $p->notes ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </main>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
