<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - SimpleLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/css/home.css')
    @else
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @endif
    <style>
        .profile-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        @media (max-width: 768px) {
            .profile-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="top-nav">
        <div class="logo-area">
            <div class="logo-icon"><i data-lucide="flask-conical"></i></div>
            <div class="logo-text"><h1>SimpleLab</h1><p>Lab IOT Computing</p></div>
        </div>
        <div class="user-area">
            <a href="{{ route('dashboard') }}" style="text-decoration:none;color:var(--text-main);font-weight:600;font-size:0.875rem;display:flex;align-items:center;gap:0.5rem;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-main)'">
                <i data-lucide="layout-dashboard" style="width:16px;"></i> Dashboard
            </a>
            <span class="badge-user">{{ auth()->user()->role === 'dosen' ? 'Dosen' : 'Mahasiswa' }}</span>
            <span class="user-email">{{ auth()->user()->email }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">@csrf<button type="submit" class="logout-btn">Logout</button></form>
        </div>
    </nav>

    <main class="main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="title-row" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <h2>Profil Pengguna</h2>
                <a href="{{ route('dashboard') }}" class="back-btn" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.75rem; color: #1e293b; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);" onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#cbd5e1'; this.style.transform='translateX(-3px)'" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#e2e8f0'; this.style.transform='none'">
                    <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Kembali ke Home
                </a>
            </div>
            <p class="subtitle">Kelola informasi akun Anda dan pantau riwayat aktivitas laboratorium.</p>
        </div>

        <!-- Profile Details Card (Photo, Name, Role, Instansi) -->
        <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:2rem;background:white;padding:2rem;border-radius:1rem;border:1px solid #e2e8f0;box-shadow:0 4px 12px rgba(0,0,0,0.03);flex-wrap:wrap;">
            <div style="position:relative;width:100px;height:100px;border-radius:50%;overflow:hidden;background:#e0f2fe;display:flex;align-items:center;justify-content:center;border:4px solid #fff;box-shadow:0 4px 10px rgba(0,0,0,0.1);flex-shrink:0;">
                <span style="font-size:2.5rem;font-weight:700;color:#0284c7;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width: 250px;">
                <h2 style="font-size:1.75rem;font-weight:700;color:#0f172a;margin-bottom:0.25rem;">{{ $user->name }}</h2>
                <p style="color:#64748b;font-size:0.95rem;margin-bottom:0.5rem;">{{ $user->email }}</p>
                <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                    <span class="badge-user" style="margin:0;">{{ $user->role === 'dosen' ? 'Dosen' : 'Mahasiswa' }}</span>
                    <span style="font-size:0.85rem;color:#64748b;background:#f1f5f9;padding:0.25rem 0.75rem;border-radius:9999px;font-weight:500;">
                        {{ $user->role === 'dosen' ? 'NIDN/NISN: ' . ($user->nisn ?? '-') : 'NIM: ' . ($user->nim ?? '-') }}
                    </span>
                    @if($user->role === 'user')
                    <span style="font-size:0.85rem;color:#64748b;background:#f1f5f9;padding:0.25rem 0.75rem;border-radius:9999px;font-weight:500;">
                        Prodi: {{ $user->prodi ?? '-' }}
                    </span>
                    <span style="font-size:0.85rem;color:#64748b;background:#f1f5f9;padding:0.25rem 0.75rem;border-radius:9999px;font-weight:500;">
                        Semester: {{ $user->semester ?? '-' }}
                    </span>
                    @endif
                </div>
            </div>
            <div style="min-width:200px; padding-left: 1rem; border-left: 2px solid #f1f5f9;">
                <span style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:0.25rem;">Instansi</span>
                <span style="font-weight:700;font-size:1.05rem;color:#0f172a;display:flex;align-items:center;gap:0.4rem;">
                    <i data-lucide="school" style="width:18px;height:18px;color:#0ea5e9;"></i> Universitas Muhammadiyah Semarang
                </span>
            </div>
        </div>

        <!-- History Sections -->
        <div class="profile-container">
            <!-- Left Side: Riwayat Peminjaman Alat -->
            <div class="list-card" style="margin-bottom:0;">
                <h3 style="display:flex;align-items:center;gap:0.5rem;"><i data-lucide="history" style="color:var(--primary);"></i> Riwayat Peminjaman Alat</h3>
                @if($peminjaman->isEmpty())
                    <div class="empty-placeholder" style="padding:4rem 2rem;">
                        <i data-lucide="package-open" style="width:48px;height:48px;"></i>
                        <p>Belum ada riwayat peminjaman alat.</p>
                    </div>
                @else
                    <div class="table-responsive" style="margin-top:1rem;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr>
                                    <th style="padding:0.75rem 1rem;border-bottom:2px solid #e2e8f0;text-align:left;">Nama Barang</th>
                                    <th style="padding:0.75rem 1rem;border-bottom:2px solid #e2e8f0;text-align:left;">Tgl Pinjam</th>
                                    <th style="padding:0.75rem 1rem;border-bottom:2px solid #e2e8f0;text-align:left;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjaman as $row)
                                    <tr>
                                        <td style="padding:0.85rem 1rem;border-bottom:1px solid #e2e8f0;">{{ $row->barang->name ?? 'Barang Terhapus' }}</td>
                                        <td style="padding:0.85rem 1rem;border-bottom:1px solid #e2e8f0;">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') }}</td>
                                        <td style="padding:0.85rem 1rem;border-bottom:1px solid #e2e8f0;">
                                            @if($row->status === 'active')
                                                <span class="badge badge-warning">Dipinjam</span>
                                            @elseif($row->status === 'returned')
                                                <span class="badge badge-success">Dikembalikan</span>
                                            @else
                                                <span class="badge badge-gray">{{ ucfirst($row->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Right Side: Riwayat Aktivitas Akun -->
            <div class="list-card" style="margin-bottom:0;">
                <h3 style="display:flex;align-items:center;gap:0.5rem;"><i data-lucide="fingerprint" style="color:var(--primary);"></i> Riwayat Aktivitas Akun & RFID</h3>
                @if($logAkses->isEmpty())
                    <div class="empty-placeholder" style="padding:4rem 2rem;">
                        <i data-lucide="shield-alert" style="width:48px;height:48px;"></i>
                        <p>Belum ada riwayat aktivitas akun.</p>
                    </div>
                @else
                    <div style="margin-top:1rem; display:flex; flex-direction:column; gap:0.75rem; max-height: 400px; overflow-y: auto; padding-right: 0.5rem;">
                        @foreach($logAkses as $log)
                            <div style="padding:0.85rem; border:1px solid #e2e8f0; border-radius:0.75rem; background:#f8fafc;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.25rem;">
                                    <strong style="font-size:0.85rem; color:#1e293b;">{{ $log->action }}</strong>
                                    <small style="font-size:0.75rem; color:#64748b;">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</small>
                                </div>
                                <p style="margin:0; font-size:0.8rem; color:#64748b;">{{ $log->notes ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
