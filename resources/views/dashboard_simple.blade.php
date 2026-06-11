<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>SIMPLELAB - Tampilan</title>
  <style>
    :root{--blue:#1a56db;--blue-dark:#1e429f;--blue-light:#e8f0fe;--green:#057a55;--green-light:#def7ec;--red:#c81e1e;--red-light:#fde8e8;--orange:#e3771a;--bg:#f3f4f6}
    html,body{height:100%;margin:0;font-family:system-ui,Segoe UI,Roboto,Arial}
    .app{display:flex;min-height:100vh;background:var(--bg)}
    .sidebar{width:220px;background:var(--blue-dark);color:#fff;display:flex;flex-direction:column}
    .brand{padding:20px 16px;border-bottom:1px solid rgba(255,255,255,0.08);display:flex;align-items:center;gap:10px}
    .brand .logo{width:36px;height:36;border-radius:50%;background:#fff;color:var(--blue-dark);display:flex;align-items:center;justify-content:center}
    .nav{padding:8px 0;flex:1}
    .nav button{width:100%;background:transparent;border:none;color:#fff;padding:10px 20px;text-align:left;cursor:pointer}
    .main{flex:1;display:flex;flex-direction:column}
    .topbar{background:#fff;padding:12px 24px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center}
    .content{padding:24px;flex:1;overflow:auto}
    .cards{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px;display:flex;align-items:center;gap:14px}
    .card .icon{width:48px;height:48;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px 12px;text-align:left;color:#374151}
    thead th{color:#6b7280;background:#f9fafb}
    .badge{display:inline-block;padding:2px 10px;border-radius:20px;font-size:12px;font-weight:500}
  </style>
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand">
        <div class="logo">🔬</div>
        <strong>SIMPLELAB</strong>
      </div>
      <nav class="nav">
        <button>🏠 Dashboard</button>
        <button>📦 Inventaris Barang</button>
        <button>📋 Peminjaman</button>
        <button>↩️ Pengembalian</button>
        <button>📅 Jadwal</button>
      </nav>
    </aside>
    <main class="main">
      <header class="topbar">
        <div style="display:flex;align-items:center;gap:12px"><button style="background:none;border:none;font-size:20;cursor:pointer">☰</button><span style="font-weight:600;font-size:16">Dashboard</span></div>
        <div style="display:flex;align-items:center;gap:12px"><div style="position:relative"><span style="font-size:20;">🔔</span><span style="position:absolute;top:-6;right:-6;background:red;color:#fff;border-radius:50%;width:16px;height:16px;font-size:10px;display:flex;align-items:center;justify-content:center">3</span></div><div style="display:flex;align-items:center;gap:8px"><div style="width:32px;height:32;border-radius:50%;background:var(--blue-light);display:flex;align-items:center;justify-content:center">👤</div><span style="font-size:13;color:#374151">Admin Laboran</span></div></div>
      </header>
      <section class="content">
        <div class="cards">
          <div class="card"><div class="icon" style="background:var(--blue-light)">🖥️</div><div><div style="font-size:24;font-weight:700;color:var(--blue)">120</div><div style="font-size:12;color:#6b7280">Total Alat</div></div></div>
          <div class="card"><div class="icon" style="background:var(--orange-light)">📋</div><div><div style="font-size:24;font-weight:700;color:var(--orange)">15</div><div style="font-size:12;color:#6b7280">Dipinjam</div></div></div>
          <div class="card"><div class="icon" style="background:var(--green-light)">✅</div><div><div style="font-size:24;font-weight:700;color:var(--green)">98</div><div style="font-size:12;color:#6b7280">Tersedia</div></div></div>
          <div class="card"><div class="icon" style="background:var(--red-light)">⚠️</div><div><div style="font-size:24;font-weight:700;color:var(--red)">7</div><div style="font-size:12;color:#6b7280">Rusak</div></div></div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
          <div style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;padding:20px">
            <h3 style="margin:0 0 12px;font-size:14;color:#111827">Riwayat Aktivitas Terbaru</h3>
            <table>
              <thead><tr><th>Tanggal</th><th>Nama Barang</th><th>User</th><th>Status</th></tr></thead>
              <tbody>
                <tr><td>13/04/2026</td><td>Proyektor Epson</td><td>Aqil</td><td><span class="badge" style="background:var(--blue-light);color:var(--blue)">Dipinjam</span></td></tr>
                <tr><td>13/04/2026</td><td>Keyboard Logitech</td><td>Dosen</td><td><span class="badge" style="background:var(--green-light);color:var(--green)">Dikembalikan</span></td></tr>
                <tr><td>12/04/2026</td><td>CPU Dell</td><td>Rina</td><td><span class="badge" style="background:var(--green-light);color:var(--green)">Dikembalikan</span></td></tr>
              </tbody>
            </table>
          </div>
          <div style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;padding:20px">
            <h3 style="margin:0 0 12px;font-size:14;color:#111827">Grafik Peminjaman Bulanan</h3>
            <div style="height:120;display:flex;align-items:flex-end;gap:8;padding:0 8px">
              <div style="flex:1"><div style="height:40px;background:var(--blue);border-radius:4px 4px 0 0"></div><div style="text-align:center;font-size:10;color:#6b7280">Jan</div></div>
              <div style="flex:1"><div style="height:70px;background:var(--blue);border-radius:4px 4px 0 0"></div><div style="text-align:center;font-size:10;color:#6b7280">Apr</div></div>
              <div style="flex:1"><div style="height:30px;background:var(--blue);border-radius:4px 4px 0 0"></div><div style="text-align:center;font-size:10;color:#6b7280">Mei</div></div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
