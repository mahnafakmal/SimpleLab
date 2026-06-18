<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SimpleLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/css/dashboard.css')
    @else
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @endif
</head>
<body>
    <nav class="top-nav">
        <div class="logo-area">
            <div class="logo-icon">
                <i data-lucide="flask-conical"></i>
            </div>
            <div class="logo-text">
                <h1>SimpleLab</h1>
                <p>Lab IOT Computing</p>
            </div>
        </div>
        <div class="user-area">
            <span class="badge-admin">{{ ucfirst(optional(auth()->user())->role ?? 'Guest') }}</span>
            <span class="user-email">{{ optional(auth()->user())->email ?? 'Guest' }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i data-lucide="log-out" style="width: 18px;"></i>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="main-container">
        <div class="header-section">
            <div class="title-row">
                <h2>Dashboard {{ auth()->check() && auth()->user()->role === 'admin' ? 'Admin' : '' }}</h2>
                <div class="badge-admin-outline">
                    <i data-lucide="shield-check" style="width: 14px;"></i>
                    {{ auth()->check() ? ucfirst(auth()->user()->role) : 'Guest' }}
                </div>
            </div>
            <p class="subtitle">Kelola peralatan, peminjaman, dan pengguna Laboratorium IOT Computing</p>
        </div>

        <div class="tabs-nav">
            @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="tab-item" onclick="switchTab('admin_peminjaman', this)">Kelola Peminjaman</div>
            @endif
            <div class="tab-item active" onclick="switchTab('ringkasan', this)">Ringkasan</div>
            <div class="tab-item" onclick="switchTab('peralatan', this)">Peralatan</div>
            <div class="tab-item" onclick="switchTab('peminjaman', this)">Peminjaman</div>
            <div class="tab-item" onclick="switchTab('scan', this)">Scan RFID</div>
            <div class="tab-item" onclick="switchTab('aktifitas', this)">Aktifitas</div>
            <div class="tab-item" onclick="switchTab('laporan', this)">Laporan</div>
        </div>

        @if(session('success'))
            <div class="alert success-alert">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert error-alert">{{ session('error') }}</div>
        @endif

        @include('dashboard.sections.ringkasan')
        @include('dashboard.sections.peralatan')
        @include('dashboard.sections.peminjaman')
        @include('dashboard.sections.scan')
        @include('dashboard.sections.pengguna')
        <div id="admin_peminjaman" class="tab-content">
            @include('dashboard.sections.admin_peminjaman')
            <div style="margin-top: 2rem;">
                @include('dashboard.sections.laporan_kerusakan')
            </div>
        </div>
        <div id="laporan" class="tab-content">
            @include('dashboard.sections.laporan')
        </div>
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Tab switching logic
        function switchTab(tabId, tabEl) {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-item').forEach(item => {
                item.classList.remove('active');
            });
            // Add active class to clicked tab
            if (tabEl) {
                tabEl.classList.add('active');
            } else {
                const tabItem = Array.from(document.querySelectorAll('.tab-item')).find(item => {
                    const onclickStr = item.getAttribute('onclick') || '';
                    return onclickStr.includes(`'${tabId}'`) || onclickStr.includes(`"${tabId}"`);
                });
                if (tabItem) {
                    tabItem.classList.add('active');
                }
            }

            // Hide all content areas
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            // Show target content area
            const target = document.getElementById(tabId);
            if (target) {
                target.classList.add('active');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.rfid-scan-input').forEach(function (input) {
                input.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        var form = event.target.closest('form');
                        if (form) {
                            form.submit();
                        }
                    }
                });
            });
        });

        function focusAndNotify(input, message) {
            if (!input) {
                return;
            }
            input.focus();
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });

            var notice = document.getElementById('scan-instruction-notice');
            if (!notice) {
                notice = document.createElement('div');
                notice.id = 'scan-instruction-notice';
                notice.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#1f2937;color:#fff;padding:12px 16px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.15);z-index:9999;max-width:320px;font-size:14px;';
                document.body.appendChild(notice);
            }
            notice.textContent = message;
            clearTimeout(notice.hideTimer);
            notice.hideTimer = setTimeout(function () {
                notice.remove();
            }, 3500);
        }
    </script>
</body>
</html>

