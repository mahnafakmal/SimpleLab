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
                <h1>SIMPLELAB</h1>
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

        <!-- Global RFID scanner (centralized to avoid scattered scan fields) -->
        <div id="global-rfid-scanner" style="position:fixed;bottom:24px;left:24px;z-index:10000;display:none;align-items:center;gap:8px;background:#111827;color:#fff;padding:10px;border-radius:8px;box-shadow:0 10px 30px rgba(0,0,0,0.2);">
            <label style="font-size:13px;margin-right:8px;color:#e5e7eb;">RFID Scanner:</label>
            <input id="global_rfid_input" type="text" autocomplete="off" style="padding:8px 10px;border-radius:6px;border:none;min-width:220px;background:#fff;color:#111827;">
            <button id="global_rfid_close" type="button" class="btn-scan" style="padding:6px 10px;">Close</button>
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
            // Accept either an Element or a selector/string that resolves to an Element
            var target = input;
            if (typeof input === 'string') {
                try {
                    target = document.querySelector(input);
                } catch (e) {
                    target = null;
                }
            }

            // Ensure there is a notice element (used across both behaviours)
            var notice = document.getElementById('scan-instruction-notice');
            if (!notice) {
                notice = document.createElement('div');
                notice.id = 'scan-instruction-notice';
                notice.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#1f2937;color:#fff;padding:12px 16px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.15);z-index:9999;max-width:320px;font-size:14px;';
                document.body.appendChild(notice);
            }

            // If the global scanner exists, open it and route input to the target
            var globalScanner = document.getElementById('global-rfid-scanner');
            var globalInput = document.getElementById('global_rfid_input');
            if (globalScanner && globalInput && target instanceof Element) {
                globalScanner._targetInput = target;
                globalScanner.style.display = 'flex';
                // clear any previous value and focus the shared scanner input
                globalInput.value = '';
                globalInput.focus();
                globalInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else if (target instanceof Element) {
                // fallback: focus the target input directly
                target.focus();
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // show brief instruction notice
            notice.textContent = message;
            clearTimeout(notice.hideTimer);
            notice.hideTimer = setTimeout(function () {
                notice.remove();
            }, 3500);
        }

        // Wire the global scanner input to populate the chosen target input and submit on Enter
        (function () {
            const scanner = document.getElementById('global-rfid-scanner');
            const ginput = document.getElementById('global_rfid_input');
            const closeBtn = document.getElementById('global_rfid_close');
            if (!scanner || !ginput) return;

            ginput.addEventListener('input', function () {
                const target = scanner._targetInput;
                if (target instanceof Element) {
                    target.value = this.value;
                    target.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });

            ginput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const target = scanner._targetInput;
                    if (target instanceof Element) {
                        const form = target.closest('form');
                        if (form) form.submit();
                    }
                    scanner.style.display = 'none';
                    scanner._targetInput = null;
                } else if (e.key === 'Escape') {
                    scanner.style.display = 'none';
                    scanner._targetInput = null;
                }
            });

            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    scanner.style.display = 'none';
                    scanner._targetInput = null;
                });
            }
        })();
    </script>
</body>
</html>

