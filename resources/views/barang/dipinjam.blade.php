<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alat Dipinjam - SimpleLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/css/home.css')
    @else
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @endif
</head>
<body>
    <nav class="top-nav">
        <div class="logo-area">
            <div class="logo-icon"><i data-lucide="flask-conical"></i></div>
            <div class="logo-text"><h1>SimpleLab</h1><p>Lab IOT Computing</p></div>
        </div>
        <div class="user-area">
            <span class="badge-user">{{ auth()->user()->role === 'dosen' ? 'Dosen' : 'Mahasiswa' }}</span>
            <span class="user-email">{{ auth()->user()->email }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">@csrf<button type="submit" class="logout-btn">Logout</button></form>
        </div>
    </nav>

    <main class="main-container">
        <div class="header-section">
            <div class="title-row"><h2>Alat yang Sedang Dipinjam</h2></div>
            <p class="subtitle">Total aset: {{ $totalAssets ?? 0 }} — berikut daftar alat yang saat ini berstatus dipinjam.</p>
        </div>

        <div class="list-card">
            <h3>Daftar Alat Dipinjam</h3>
            @if($borrowedItems->isEmpty())
                <div class="empty-state"><i data-lucide="package" size="48"></i><p>Tidak ada alat yang sedang dipinjam.</p></div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowedItems as $it)
                                <tr>
                                    <td>{{ $it->name }}</td>
                                    <td>{{ $it->kategori ?? '-' }}</td>
                                    <td>{{ $it->kondisi ?? '-' }}</td>
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
