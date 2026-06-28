<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Tersedia - SimpleLab</title>
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
            <div class="title-row" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <h2>Barang Tersedia</h2>
                <a href="{{ route('dashboard') }}" class="back-btn" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.75rem; color: #1e293b; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);" onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#cbd5e1'; this.style.transform='translateX(-3px)'" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#e2e8f0'; this.style.transform='none'">
                    <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Kembali ke Home
                </a>
            </div>
            <p class="subtitle">Menampilkan semua barang yang saat ini berstatus tersedia di laboratorium.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <span class="label">Total Alat Lab</span>
                <span class="value">{{ $totalAssets ?? 0 }}</span>
            </div>
            <div class="stat-card">
                <span class="label">Jumlah Tersedia</span>
                <span class="value" style="color:#22c55e">{{ $availableItems->count() ?? 0 }}</span>
            </div>
        </div>

        <div class="list-card">
            <h3>Daftar Barang Tersedia</h3>
            @if($availableItems->isEmpty())
                <div class="empty-state"><i data-lucide="box" size="48"></i><p>Tidak ada barang tersedia.</p></div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($availableItems as $item)
                                <tr>
                                    <td class="thumb-col">
                                        @php
                                            $c1 = public_path('images/barangs/'.$item->image);
                                            $c2 = public_path('images/'.$item->image);
                                        @endphp
                                        @if($item->image && (file_exists($c1) || file_exists($c2)))
                                            <img src="{{ file_exists($c1) ? asset('images/barangs/'.$item->image) : asset('images/'.$item->image) }}" alt="{{ $item->name }}" class="thumb">
                                        @else
                                            <div class="thumb-placeholder"><i data-lucide="camera"></i></div>
                                        @endif
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->kategori ?? '-' }}</td>
                                    <td>{{ $item->kondisi ?? '-' }}</td>
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
