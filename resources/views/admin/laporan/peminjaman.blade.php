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
            <div class="title-row"><h2>Riwayat Peminjaman Alat</h2></div>
            <p class="subtitle">Daftar lengkap peminjaman alat (termasuk yang sudah dikembalikan).</p>
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
                                    <td>{{ $p->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $p->user->name ?? $p->user_id }}</td>
                                    <td>{{ $p->barang->name ?? '-' }}</td>
                                    <td>{{ $p->tagRfid->uid ?? '-' }}</td>
                                    <td>{{ ucfirst($p->status) }}</td>
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
