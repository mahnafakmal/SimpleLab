<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Dosen - SIMPLELAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>/* reuse simplified styles from login view */
        :root{--primary:#0ea5e9;--bg:#f8fafc;--text-main:#1e293b;--text-muted:#64748b;--border:#e2e8f0}
        *{margin:0;padding:0;box-sizing:border-box;font-family:'Plus Jakarta Sans',sans-serif}
        body{background:var(--bg);color:var(--text-main);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem}
        .card{width:100%;max-width:400px;background:#fff;padding:2rem;border-radius:1rem;border:1px solid var(--border)}
        .logo-area{text-align:center;margin-bottom:1rem}
        .form-group{margin-bottom:1rem}
        .form-input{width:100%;padding:.75rem 1rem;border:1px solid var(--border);border-radius:.75rem}
        .btn-submit{width:100%;background:var(--primary);color:#fff;border:none;padding:.85rem;border-radius:.75rem;font-weight:700}
        .error-box{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:.75rem;border-radius:.75rem;margin-bottom:1rem}
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-area">
            <div style="background:#0ea5e9;color:#fff;display:inline-block;padding:.6rem;border-radius:.8rem;margin-bottom:.5rem;"><i data-lucide="user-check" style="width:20px"></i></div>
            <h2>Login Dosen</h2>
            <p style="color:#64748b;margin-top:.25rem">Masuk sebagai dosen</p>
        </div>

        @if ($errors->has('email'))
            <div class="error-box">{{ $errors->first('email') }}</div>
        @endif

        <form action="{{ route('login.dosen.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-input" required value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label>Password</label>
                <div style="position: relative;">
                    <input id="password" type="password" name="password" class="form-input" required style="padding-right: 40px;">
                    <button type="button" onclick="togglePasswordVisibility('password', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 34px; height: 34px; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; line-height: 0; color: var(--text-muted);">
                        <i data-lucide="eye" style="width: 20px; height: 20px;"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-submit">Masuk sebagai Dosen</button>
        </form>
        <div style="text-align:center;margin-top:1rem;color:#64748b">Belum punya akun? <a href="{{ route('register') }}" style="color:#1e40af;font-weight:600">Daftar</a></div>
    </div>
    <script>
        lucide.createIcons();
        function togglePasswordVisibility(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>
