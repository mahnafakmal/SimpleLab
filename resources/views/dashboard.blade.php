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
                <img src="{{ asset('images/barangs/logo-unimus.png') }}" alt="UNIMUS" style="width:44px;height:44px;object-fit:contain;border-radius:8px;">
            </div>
            <div class="logo-text">
                <h1>SIMPLELAB</h1>
                <p>Laboratorium IOT Computing</p>
            </div>
        </div>
        <div class="nav-links" style="margin-right:16px;display:flex;align-items:center;gap:12px;">
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('rfid.index') }}" style="color:inherit;text-decoration:none;font-weight:600;">Pengelolaan RFID</a>
            @endif
        </div>
        <div class="user-area">
            <span class="badge-admin">{{ ucfirst(optional(auth()->user())->role ?? 'Guest') }}</span>
            <span class="user-email">{{ optional(auth()->user())->email ?? 'Guest' }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i data-lucide="log-out" style="width: 18px;"></i>
                    Keluar
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

        @if(isset($overdueLoans) && $overdueLoans->count() > 0)
            <div style="margin-bottom:1rem;padding:14px 16px;border:1px solid #f59e0b;background:#fff7ed;border-radius:12px;color:#92400e;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
                <div>
                    <strong><i data-lucide="alert-triangle" style="width:16px;height:16px;margin-right:6px;"></i> Ada {{ $overdueLoans->count() }} peminjaman terlambat</strong>
                    <div style="font-size:13px;margin-top:4px;">Barang yang belum dikembalikan melewati batas waktu.</div>
                </div>
                <div style="font-size:13px;">{{ $overdueLoans->take(3)->map(fn($loan) => $loan->barang->name ?? 'Barang')->join(', ') }}</div>
            </div>
        @endif

        <!-- Global RFID scanner (centralized to avoid scattered scan fields) -->
        <div id="global-rfid-scanner" style="position:fixed;bottom:24px;left:24px;z-index:10000;display:none;align-items:center;gap:8px;background:#111827;color:#fff;padding:10px;border-radius:8px;box-shadow:0 10px 30px rgba(0,0,0,0.2);">
            <label style="font-size:13px;margin-right:8px;color:#e5e7eb;">Pemindai RFID:</label>
            <input id="global_rfid_input" type="text" autocomplete="off" style="padding:8px 10px;border-radius:6px;border:none;min-width:220px;background:#fff;color:#111827;">
            <button id="global_rfid_close" type="button" class="btn-scan" style="padding:6px 10px;">Tutup</button>
        </div>
        <div class="tabs-nav">
            @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="tab-item" onclick="switchTab('admin_peminjaman', this)">Kelola Peminjaman</div>
            @endif
            <div class="tab-item active" onclick="switchTab('ringkasan', this)">Ringkasan</div>
            <div class="tab-item" onclick="switchTab('peralatan', this)">Peralatan</div>
            <div class="tab-item" onclick="switchTab('peminjaman', this)">Peminjaman</div>
            <div class="tab-item" onclick="switchTab('scan', this)">Scan RFID</div>
            <div class="tab-item" onclick="switchTab('aktifitas', this)">Aktivitas</div>
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

            // If no target was found, fallback to scan tab's registration input
            if (!(target instanceof Element)) {
                // try common fallback inputs inside current page
                target = document.getElementById('rfid_uid_scan') || document.querySelector('.rfid-scan-input[name="tag_uid"]') || document.querySelector('.rfid-scan-input[name="card_uid"]') || null;
                if (target) {
                    // ensure user sees the Scan tab
                    try { switchTab('scan'); } catch (e) {}
                }
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
            } else {
                // no useful target found — show a notice to the user
                notice.textContent = 'Tidak ditemukan field untuk dipindai. Buka tab Scan jika ingin mendaftarkan tag.';
                clearTimeout(notice.hideTimer);
                notice.hideTimer = setTimeout(function () { notice.remove(); }, 3500);
                return;
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

            // Ensure all existing .btn-scan buttons work even if they lack inline onclick
            document.querySelectorAll('.btn-scan').forEach(function(btn) {
                btn.addEventListener('click', function (e) {
                    // if button already has an onclick inline, let it run
                    if (btn.getAttribute('onclick')) return;

                    // data-target can be a selector for the input to focus
                    var dt = btn.getAttribute('data-target');
                    var message = btn.getAttribute('data-message') || 'Klik field UID lalu pindai tag RFID.';
                    var target = null;
                    if (dt) {
                        try { target = document.querySelector(dt); } catch (err) { target = null; }
                    }
                    if (!target) {
                        // try to find input in same form
                        var form = btn.closest('form');
                        if (form) {
                            target = form.querySelector('.rfid-scan-input[name="rfid_uid"]') || form.querySelector('.rfid-scan-input[name="tag_uid"]') || form.querySelector('.rfid-scan-input[name="card_uid"]') || null;
                        }
                    }
                    focusAndNotify(target, message);
                });
            });
        })();
    </script>
    <!-- Simple modal for stat details -->
    <div id="stat-modal" style="display:none;position:fixed;left:50%;top:50%;transform:translate(-50%,-50%);z-index:20000;background:#ffffff;padding:18px;border-radius:10px;box-shadow:0 20px 50px rgba(2,6,23,0.35);max-width:720px;width:90%;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <strong id="stat-modal-title">Detail</strong>
            <button id="stat-modal-close" style="background:transparent;border:none;font-size:18px;cursor:pointer;">✕</button>
        </div>
        <div id="stat-modal-body" style="max-height:58vh;overflow:auto;font-size:14px;color:#111827;"></div>
    </div>
    <div id="stat-modal-backdrop" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:19999;"></div>

    <script>
        (function(){
            function showModal(title, html) {
                document.getElementById('stat-modal-title').textContent = title || 'Detail';
                document.getElementById('stat-modal-body').innerHTML = html || '<p>Tidak ada data.</p>';
                document.getElementById('stat-modal').style.display = 'block';
                document.getElementById('stat-modal-backdrop').style.display = 'block';
            }
            function hideModal(){
                document.getElementById('stat-modal').style.display = 'none';
                document.getElementById('stat-modal-backdrop').style.display = 'none';
            }
            document.getElementById('stat-modal-close').addEventListener('click', hideModal);
            document.getElementById('stat-modal-backdrop').addEventListener('click', hideModal);

            // Make stat cards interactive: navigate or fetch details
            document.querySelectorAll('.stat-card').forEach(function(card){
                const href = card.getAttribute('data-href');
                const api = card.getAttribute('data-api');
                if (href) {
                    card.style.cursor = 'pointer';
                }
                card.addEventListener('click', function(e){
                    // avoid catching clicks on inner links or buttons
                    if (e.target.closest('a') || e.target.tagName === 'A' || e.target.closest('button')) return;
                    if (href) {
                        window.location.href = href;
                        return;
                    }
                    if (api) {
                        // support optional role filter on the card
                        const role = card.getAttribute('data-role');
                        const url = role ? (api + '?role=' + encodeURIComponent(role)) : api;
                        showModal('Memuat...', '<p>Memuat data...</p>');
                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(function(res){ return res.json(); })
                            .then(function(data){
                                if (!data) { showModal('Hasil', '<p>Tidak ada data.</p>'); return; }
                                // If data is an array (list of users), render a table
                                if (Array.isArray(data)) {
                                    if (data.length === 0) { showModal('Hasil', '<p>Tidak ada akun.</p>'); return; }
                                    var html = '<table style="width:100%;border-collapse:collapse;font-size:14px;">';
                                    html += '<thead><tr style="text-align:left;border-bottom:1px solid #e6e6e6;"><th style="padding:8px">Nama</th><th style="padding:8px">Email</th><th style="padding:8px">Role</th></tr></thead>';
                                    html += '<tbody>';
                                    data.forEach(function(u){
                                        html += '<tr style="border-bottom:1px solid #f3f4f6;">';
                                        html += '<td style="padding:8px">' + (u.name || '-') + '</td>';
                                        html += '<td style="padding:8px;color:#374151">' + (u.email || '-') + '</td>';
                                        html += '<td style="padding:8px">' + (u.role || '-') + '</td>';
                                        html += '</tr>';
                                    });
                                    html += '</tbody></table>';
                                    showModal('Daftar Akun', html);
                                    return;
                                }

                                // Fallback: object -> render key/value pairs
                                var html = '<dl style="display:grid;grid-template-columns:1fr 2fr;gap:8px;">';
                                for (var k in data) {
                                    if (!Object.prototype.hasOwnProperty.call(data,k)) continue;
                                    html += '<dt style="font-weight:600;color:#374151;">'+k+'</dt>';
                                    html += '<dd style="margin:0;color:#111827;">'+(Array.isArray(data[k]) ? JSON.stringify(data[k]) : data[k])+'</dd>';
                                }
                                html += '</dl>';
                                showModal('Hasil', html);
                            }).catch(function(err){
                                showModal('Gagal memuat', '<p>Terjadi kesalahan saat memuat data.</p>');
                                console.error('Failed fetching stat api', err);
                            });
                    }
                });
            });
        })();
    </script>
</body>
</html>

