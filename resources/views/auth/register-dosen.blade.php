<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Dosen - SimpleLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* reuse styles from register view (kept minimal) */
        * { box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background:#f8fafc; color:#1e293b; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1.5rem; }
        .card { width:100%; max-width:420px; background:#fff; padding:2rem; border-radius:1rem; border:1px solid #e2e8f0; }
        .logo-area { text-align:center; margin-bottom:1rem; }
        .form-group { margin-bottom:1rem; }
        .form-input { width:100%; padding:.75rem 1rem; border:1px solid #e2e8f0; border-radius:.75rem; }
        .btn-primary { width:100%; padding:.85rem; background:#0ea5e9; color:#fff; border:none; border-radius:.75rem; font-weight:600; }
        .error-box { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:.75rem; border-radius:.75rem; margin-bottom:1rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-area">
            <div style="background:#0ea5e9;color:#fff;display:inline-block;padding:.6rem;border-radius:.8rem;margin-bottom:.5rem;"><i data-lucide="user-check" style="width:20px"></i></div>
            <h2>Registrasi Dosen</h2>
            <p style="color:#64748b;margin-top:.25rem">Buat akun dosen untuk akses admin atau fitur dosen</p>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error-box">{{ $error }}</div>
            @endforeach
        @endif

        <form action="{{ route('register.dosen.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-input" required value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-input" required value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label>NISN / NIDN (Nomor Induk Dosen)</label>
                <input type="text" name="nisn" class="form-input" required value="{{ old('nisn') }}">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-input" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-input" required>
            </div>

            <button type="submit" class="btn-primary">Daftar sebagai Dosen</button>
        </form>

        <div style="text-align:center;margin-top:1rem;color:#64748b;font-size:.9rem">
            Sudah punya akun? <a href="{{ route('login') }}" style="color:#0ea5e9;font-weight:600">Masuk</a>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
