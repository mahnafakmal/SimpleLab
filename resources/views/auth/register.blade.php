<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SimpleLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #22c55e;
            --danger: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .register-container {
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .logo-icon {
            background: var(--primary);
            color: white;
            padding: 0.75rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }

        .logo-text {
            text-align: center;
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.025em;
        }

        .logo-text p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .register-card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .card-header p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            width: 18px;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s;
            background: #f8fafc;
        }

        .form-input:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background: var(--primary-dark);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider-text {
            padding: 0 0.75rem;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .auth-link {
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .auth-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .auth-link a:hover {
            color: var(--primary-dark);
        }

        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .success-box {
            background: #ecfdf5;
            border: 1px solid #d1fae5;
            color: #166534;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo-area">
            <div class="logo-icon">
                <i data-lucide="user-plus" style="width: 24px; height: 24px;"></i>
            </div>
            <div class="logo-text">
                <h1>SimpleLab</h1>
                <p>Pendaftaran Pengguna</p>
            </div>
        </div>

        <div class="register-card">
            <div class="card-header">
                <h2>Buat Akun Baru</h2>
                <p>Daftar untuk mengakses sistem RFID lab</p>
            </div>

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="error-box">{{ $error }}</div>
                @endforeach
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <i data-lucide="user"></i>
                        <input type="text" name="name" class="form-input" placeholder="Nama Anda" required value="{{ old('name') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i data-lucide="mail"></i>
                        <input type="email" name="email" class="form-input" placeholder="email@contoh.com" required value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i data-lucide="lock"></i>
                        <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <i data-lucide="lock"></i>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" class="btn-login">Daftar</button>
            </form>

            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">atau</span>
                <div class="divider-line"></div>
            </div>

            <div class="auth-link">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
