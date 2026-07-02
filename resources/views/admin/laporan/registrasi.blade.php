<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Registrasi - Admin</title>
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
                <h2>Laporan Registrasi Pengguna</h2>
                <span class="meta-count">{{ $registrations->count() }} entri</span>
            </div>
            <p class="subtitle">Daftar akun yang baru terdaftar.</p>
        </div>

        <div class="list-card">
            @if(!auth()->check() || auth()->user()->role !== 'admin')
                <div class="empty-state"><p>Anda tidak memiliki akses ke laporan ini.</p></div>
            @else
                @if($registrations->isEmpty())
                    <div class="empty-state"><i data-lucide="alert-circle" size="48"></i><p>Tidak ada registrasi terbaru.</p></div>
                @else
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Event</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrations as $r)
                                    @php
                                        $displayRegWhen = '-';
                                        if(!empty($r->created_at)) {
                                            try {
                                                if($r->created_at instanceof \Illuminate\Support\Carbon) {
                                                    $displayRegWhen = $r->created_at->isoFormat('D MMM YYYY, HH:mm');
                                                } else {
                                                    $displayRegWhen = \Illuminate\Support\Carbon::parse($r->created_at)->isoFormat('D MMM YYYY, HH:mm');
                                                }
                                            } catch (\Exception $e) {
                                                $displayRegWhen = '-';
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $displayRegWhen }} WIB</td>
                                        <td>{{ $r->event }}</td>
                                        <td style="max-width:520px;word-wrap:break-word;white-space:normal;">{{ $r->detail }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        </div>
    </main>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
