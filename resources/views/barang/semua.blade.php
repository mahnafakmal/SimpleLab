<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Alat - SimpleLab</title>
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
            <div class="title-row"><h2>Semua Alat Laboratorium</h2></div>
            <p class="subtitle">Total aset: {{ $totalAssets ?? 0 }} — daftar lengkap seluruh peralatan.</p>
        </div>

        <div class="list-card">
            <h3>Daftar Semua Alat</h3>
            @if($items->isEmpty())
                <div class="empty-state"><i data-lucide="package-open" size="48"></i><p>Belum ada data alat.</p></div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Kondisi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $it)
                                <tr>
                                    <td>{{ $it->name }}</td>
                                    <td>{{ $it->kategori ?? '-' }}</td>
                                    <td>{{ $it->kondisi ?? '-' }}</td>
                                    <td>{{ ucfirst($it->status ?? 'unknown') }}</td>
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
