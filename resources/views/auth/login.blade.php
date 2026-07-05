<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — SIMPLELAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/css/login.css')
    @else
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    @endif
</head>
<body>
    <div class="wrap">
        <div class="brand-panel">
            @php
                $logo1 = public_path('images/logo-unimus.png');
                $logo2 = public_path('images/barangs/logo-unimus.png');
            @endphp
            @if (file_exists($logo1) || file_exists($logo2))
                <img src="{{ file_exists($logo1) ? asset('images/logo-unimus.png') : asset('images/barangs/logo-unimus.png') }}" alt="Unimus" class="unimus-logo">
            @else
                <div class="logo-icon">
                    <i data-lucide="flask-conical" size="28"></i>
                </div>
            @endif
            <h1>SimpleLab</h1>
            <p>Platform manajemen laboratorium IoT — pinjam alat, kelola jadwal, pantau akses.</p>
        </div>

        <div class="card">
            <h2>Selamat Datang</h2>
            <p class="lead">Masuk ke akun Anda untuk mengelola peralatan dan peminjaman.</p>

            @if ($errors->any())
                <div style="background:#ffe7e7;border:1px solid #ffb3b3;padding:10px;border-radius:8px;color:#7a1a1a;margin-bottom:12px;">
                    {{ implode(' ', $errors->all()) }}
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" class="input" placeholder="name@contoh.com" required value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input id="password" type="password" name="password" class="input" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label>Masuk sebagai</label>
                    <div class="roles">
                        <label class="role"><input type="radio" name="role" value="admin">Admin</label>
                        <label class="role"><input type="radio" name="role" value="dosen">Dosen</label>
                        <label class="role"><input type="radio" name="role" value="user" checked>Mahasiswa</label>
                    </div>
                </div>

                <div style="margin-top:8px">
                    <button class="btn" type="submit">Masuk <i data-lucide="log-in" style="width:16px;height:16px;color:#062028"></i></button>
                </div>
            </form>

            <div class="muted">Belum punya akun? <a href="{{ route('register') }}" style="color:#bff7ff;text-decoration:underline;">Daftar sekarang</a></div>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
