<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Ruangan - Admin</title>
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
            <div class="title-row"><h2>Riwayat Peminjaman Ruangan</h2></div>
            <p class="subtitle">Daftar peminjaman ruangan yang pernah diajukan.</p>
        </div>

        <div class="list-card">
            @if($ruangan->isEmpty())
                <div class="empty-state"><i data-lucide="calendar" size="48"></i><p>Belum ada riwayat peminjaman ruangan.</p></div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>User</th>
                                <th>Ruangan</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                                <th>Keperluan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ruangan as $r)
                                <tr>
                                    <td>{{ $r->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $r->user->name ?? $r->user_id }}</td>
                                    <td>{{ $r->nama_ruangan }}</td>
                                    <td>{{ $r->tanggal }} ({{ $r->jam_mulai }} - {{ $r->jam_selesai }})</td>
                                    <td>{{ ucfirst($r->status) }}</td>
                                    <td>{{ $r->keperluan ?? '-' }}</td>
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
