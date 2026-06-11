import React, { useState } from "react";

const BLUE = "#1a56db";
const BLUE_DARK = "#1e429f";
const BLUE_LIGHT = "#e8f0fe";
const GREEN = "#057a55";
const GREEN_LIGHT = "#def7ec";
const RED = "#c81e1e";
const RED_LIGHT = "#fde8e8";
const ORANGE = "#e3771a";
const ORANGE_LIGHT = "#fef3c7";

const initialItems = [
  { id: "A001", name: "CPU Dell Optiplex", category: "CPU", condition: "Baik", status: "Tersedia", location: "Lab 1" },
  { id: "A002", name: "Monitor LG 22\"", category: "Monitor", condition: "Baik", status: "Dipinjam", location: "Lab 1" },
  { id: "A003", name: "Keyboard Logitech", category: "Keyboard", condition: "Baik", status: "Tersedia", location: "Lab 2" },
  { id: "A004", name: "Mouse Wireless", category: "Mouse", condition: "Baik", status: "Tersedia", location: "Lab 2" },
  { id: "A005", name: "Proyektor Epson", category: "Proyektor", condition: "Baik", status: "Dipinjam", location: "Lab 1" },
  { id: "A006", name: "Printer Canon", category: "Printer", condition: "Rusak", status: "Rusak", location: "Lab 3" },
  { id: "A007", name: "Speaker Aktif", category: "Speaker", condition: "Baik", status: "Tersedia", location: "Lab 1" },
];

const recentActivity = [
  { date: "13/04/2026", item: "Proyektor Epson", user: "Aqil", status: "Dipinjam" },
  { date: "13/04/2026", item: "Keyboard Logitech", user: "Dosen", status: "Dikembalikan" },
  { date: "13/04/2026", item: "Mouse Wireless", user: "Budi", status: "Dipinjam" },
  { date: "12/04/2026", item: "CPU Dell", user: "Rina", status: "Dikembalikan" },
  { date: "12/04/2026", item: "Monitor LG", user: "Dosen", status: "Dipinjam" },
];

const scheduleData = [
  { date: "15/04/2026", time: "08.00 - 10.00", lecturer: "Zainal Abidin", class: "TI A", lab: "Lab 1" },
  { date: "15/04/2026", time: "10.00 - 12.00", lecturer: "Budi Santoso", class: "TI B", lab: "Lab 2" },
  { date: "16/04/2026", time: "08.00 - 10.00", lecturer: "Rina Wati", class: "TI C", lab: "Lab 1" },
  { date: "16/04/2026", time: "13.00 - 15.00", lecturer: "Dedi Kurniawan", class: "TI A", lab: "Lab 2" },
  { date: "17/04/2026", time: "08.00 - 10.00", lecturer: "Zainal Abidin", class: "TI B", lab: "Lab 1" },
];

const borrowings = [
  { id: "P001", borrower: "M. Aqil Mihdadu Fatih", nim: "G2A022001", item: "Proyektor Epson", itemId: "A005", borrowDate: "13/04/2026", dueDate: "15/04/2026", status: "Dipinjam" },
  { id: "P002", borrower: "Budi Santoso", nim: "DOSEN", item: "Mouse Wireless", itemId: "A004", borrowDate: "13/04/2026", dueDate: "14/04/2026", status: "Dipinjam" },
  { id: "P003", borrower: "Rina Wati", nim: "DOSEN", item: "CPU Dell", itemId: "A001", borrowDate: "10/04/2026", dueDate: "12/04/2026", status: "Terlambat" },
];

const StatusBadge = ({ status }) => {
  const map = {
    Tersedia: 'status-tersedia',
    Dipinjam: 'status-dipinjam',
    Rusak: 'status-rusak',
    Dikembalikan: 'status-tersedia',
    Terlambat: 'status-terlambat',
  };
  const cls = map[status] || '';
  return (
    <span className={`status-badge ${cls}`}>{status}</span>
  );
};

const Sidebar = ({ active, setPage }) => {
  const items = [
    { key: "dashboard", icon: "🏠", label: "Dashboard" },
    { key: "inventaris", icon: "📦", label: "Inventaris Barang" },
    { key: "peminjaman", icon: "📋", label: "Peminjaman" },
    { key: "pengembalian", icon: "↩️", label: "Pengembalian" },
    { key: "jadwal", icon: "📅", label: "Jadwal Laboratorium" },
    { key: "laporan", icon: "📊", label: "Laporan" },
    { key: "manajemen", icon: "👥", label: "Manajemen User" },
    { key: "pengaturan", icon: "⚙️", label: "Pengaturan" },
  ];
  return (
    <div className="sidebar">
      <div className="brand">
        <div className="logo">🔬</div>
        <span style={{ fontWeight: 700, fontSize: 16 }}>SIMPLELAB</span>
      </div>
      <nav>
        {items.map(it => (
          <button key={it.key} onClick={() => setPage(it.key)} className={active === it.key ? 'active' : ''}>
            <span>{it.icon}</span>
            {it.label}
          </button>
        ))}
        <button className="logout">🚪 Logout</button>
      </nav>
    </div>
  );
};

const Topbar = ({ title }) => (
  <div className="topbar">
    <div className="left">
      <button style={{ background: 'none', border: 0, cursor: 'pointer', fontSize: 20 }}>☰</button>
      <span className="title">{title}</span>
    </div>
    <div className="actions">
      <div style={{ position: 'relative' }}>
        <span style={{ fontSize: 20, cursor: 'pointer' }}>🔔</span>
        <span style={{ position: 'absolute', top: -4, right: -4, background: 'red', color: 'white', borderRadius: '50%', width: 16, height: 16, fontSize: 10, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>3</span>
      </div>
      <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
        <div style={{ width: 32, height: 32, borderRadius: '50%', background: BLUE_LIGHT, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 14 }}>👤</div>
        <span style={{ fontSize: 13, color: '#374151' }}>Admin Laboran</span>
      </div>
    </div>
  </div>
);

const BarChart = ({ data }) => {
  const max = Math.max(...data.map(d => d.val));
  return (
    <div className="barchart">
      {data.map((d, i) => (
        <div key={i} className="col">
          <div className="bar" style={{ height: `${(d.val / max) * 100}px` }} />
          <span style={{ fontSize: 10, color: "#6b7280" }}>{d.label}</span>
        </div>
      ))}
    </div>
  );
};

const LineChart = ({ data }) => {
  const max = Math.max(...data.map(d => d.val));
  const min = Math.min(...data.map(d => d.val));
  const w = 340, h = 100;
  const points = data.map((d, i) => {
    const x = (i / (data.length - 1)) * (w - 40) + 20;
    const y = h - 10 - ((d.val - min) / (max - min + 1)) * (h - 20);
    return `${x},${y}`;
  });
  return (
    <svg viewBox={`0 0 ${w} ${h}`} style={{ width: "100%", height: 100 }}>
      <polyline points={points.join(" ")} fill="none" stroke={BLUE} strokeWidth="2" />
      {data.map((d, i) => {
        const [x, y] = points[i].split(",");
        return <circle key={i} cx={x} cy={y} r="4" fill={BLUE} />;
      })}
      {data.map((d, i) => {
        const [x] = points[i].split(",");
        return <text key={i} x={x} y={h - 2} textAnchor="middle" fontSize="9" fill="#6b7280">{d.label}</text>;
      })}
    </svg>
  );
};

const DashboardPage = () => {
  const stats = [
    { label: "Total Alat", value: 120, icon: "🖥️", color: BLUE, bg: BLUE_LIGHT },
    { label: "Dipinjam", value: 15, icon: "📋", color: ORANGE, bg: ORANGE_LIGHT },
    { label: "Tersedia", value: 98, icon: "✅", color: GREEN, bg: GREEN_LIGHT },
    { label: "Rusak", value: 7, icon: "⚠️", color: RED, bg: RED_LIGHT },
  ];
  const chartData = [
    { label: "Jan", val: 20 }, { label: "Feb", val: 32 }, { label: "Mar", val: 25 },
    { label: "Apr", val: 45 }, { label: "Mei", val: 30 }, { label: "Jun", val: 38 },
  ];
  return (
    <div style={{ padding: 24, display: "flex", flexDirection: "column", gap: 20 }}>
      <div style={{ display: "grid", gridTemplateColumns: "repeat(4,1fr)", gap: 16 }}>
        {stats.map((s, i) => (
          <div key={i} style={{ background: "white", borderRadius: 12, padding: 16, border: "1px solid #e5e7eb", display: "flex", alignItems: "center", gap: 14 }}>
            <div style={{ width: 48, height: 48, borderRadius: "50%", background: s.bg, display: "flex", alignItems: "center", justifyContent: "center", fontSize: 22 }}>{s.icon}</div>
            <div>
              <div style={{ fontSize: 24, fontWeight: 700, color: s.color }}>{s.value}</div>
              <div style={{ fontSize: 12, color: "#6b7280" }}>{s.label}</div>
            </div>
          </div>
        ))}
      </div>
      <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 20 }}>
        <div style={{ background: "white", borderRadius: 12, border: "1px solid #e5e7eb", padding: 20 }}>
          <h3 style={{ margin: "0 0 16px", fontSize: 14, color: "#111827" }}>Riwayat Aktivitas Terbaru</h3>
          <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 12 }}>
            <thead>
              <tr style={{ borderBottom: "1px solid #e5e7eb" }}>
                { ["Tanggal", "Nama Barang", "User", "Status"].map(h => (
                  <th key={h} style={{ padding: "6px 8px", textAlign: "left", color: "#6b7280", fontWeight: 500 }}>{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {recentActivity.map((r, i) => (
                <tr key={i} style={{ borderBottom: "1px solid #f3f4f6" }}>
                  <td style={{ padding: "8px 8px", color: "#374151" }}>{r.date}</td>
                  <td style={{ padding: "8px 8px", color: "#374151" }}>{r.item}</td>
                  <td style={{ padding: "8px 8px", color: "#374151" }}>{r.user}</td>
                  <td style={{ padding: "8px 8px" }}><StatusBadge status={r.status} /></td>
                </tr>
              ))}
            </tbody>
          </table>
          <button style={{ marginTop: 12, background: "none", border: "none", color: BLUE, cursor: "pointer", fontSize: 12 }}>Lihat Semua →</button>
        </div>
        <div style={{ background: "white", borderRadius: 12, border: "1px solid #e5e7eb", padding: 20 }}>
          <h3 style={{ margin: "0 0 16px", fontSize: 14, color: "#111827" }}>Grafik Peminjaman Bulanan</h3>
          <BarChart data={chartData} />
        </div>
      </div>
      <div style={{ background: "#fffbeb", border: "1px solid #fbbf24", borderRadius: 10, padding: "12px 16px", display: "flex", justifyContent: "space-between", alignItems: "center" }}>
        <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
          <span style={{ fontSize: 20 }}>⚠️</span>
          <div>
            <div style={{ fontWeight: 600, fontSize: 13, color: "#92400e" }}>Peminjaman Terlambat</div>
            <div style={{ fontSize: 12, color: "#92400e" }}>2 barang terlambat dikembalikan</div>
          </div>
        </div>
        <button style={{ background: "white", border: "1px solid #fbbf24", borderRadius: 8, padding: "6px 16px", cursor: "pointer", fontSize: 12, color: "#92400e" }}>Lihat Detail</button>
      </div>
    </div>
  );
};

const InventarisPage = ({ items, setItems }) => {
  const [search, setSearch] = useState("");
  const [showForm, setShowForm] = useState(false);
  const [form, setForm] = useState({ name: "", category: "", condition: "Baik", location: "", rfid: "" });

  const filtered = items.filter(it =>
    it.name.toLowerCase().includes(search.toLowerCase()) ||
    it.id.toLowerCase().includes(search.toLowerCase())
  );

  const handleAdd = () => {
    const newId = "A" + String(items.length + 1).padStart(3, "0");
    setItems([...items, { id: newId, name: form.name, category: form.category, condition: form.condition, status: "Tersedia", location: form.location }]);
    setForm({ name: "", category: "", condition: "Baik", location: "", rfid: "" });
    setShowForm(false);
  };

  const handleDelete = (id) => setItems(items.filter(it => it.id !== id));

  return (
    <div style={{ padding: 24 }}>
      <div style={{ display: "flex", justifyContent: "space-between", marginBottom: 20 }}>
        <h2 style={{ margin: 0, fontSize: 20, color: "#111827" }}>Inventaris Barang</h2>
        <div style={{ display: "flex", gap: 10 }}>
          <button style={{ background: "white", border: `1px solid ${BLUE}`, color: BLUE, borderRadius: 8, padding: "8px 16px", cursor: "pointer", fontSize: 13, display: "flex", alignItems: "center", gap: 6 }}>
            📡 Scan RFID
          </button>
          <button onClick={() => setShowForm(true)} style={{ background: BLUE, border: "none", color: "white", borderRadius: 8, padding: "8px 16px", cursor: "pointer", fontSize: 13 }}>
            + Tambah Barang
          </button>
        </div>
      </div>

      {showForm && (
        <div style={{ position: "fixed", inset: 0, background: "rgba(0,0,0,0.4)", display: "flex", alignItems: "center", justifyContent: "center", zIndex: 50 }}>
          <div style={{ background: "white", borderRadius: 12, padding: 24, width: 380, maxHeight: "80vh", overflowY: "auto" }}>
            <div style={{ display: "flex", justifyContent: "space-between", marginBottom: 16 }}>
              <h3 style={{ margin: 0, fontSize: 16 }}>Tambah Barang</h3>
              <button onClick={() => setShowForm(false)} style={{ background: "none", border: "none", cursor: "pointer", fontSize: 18 }}>✕</button>
            </div>
            {[
              { label: "Kode Barang", placeholder: "Otomatis", key: null },
              { label: "Nama Barang", placeholder: "Masukkan nama barang", key: "name" },
            ].map((f, i) => (
              <div key={i} style={{ marginBottom: 14 }}>
                <label style={{ fontSize: 13, color: "#374151", display: "block", marginBottom: 4 }}>{f.label}</label>
                <input value={f.key ? form[f.key] : ""} onChange={e => f.key && setForm({ ...form, [f.key]: e.target.value })}
                  placeholder={f.placeholder} disabled={!f.key}
                  style={{ width: "100%", border: "1px solid #d1d5db", borderRadius: 8, padding: "8px 12px", fontSize: 13, boxSizing: "border-box", background: f.key ? "white" : "#f9fafb" }} />
              </div>
            ))}
            {[["Kategori", "Kondisi"].map((label, i) => (
              <div key={i} style={{ marginBottom: 14 }}>
                <label style={{ fontSize: 13, color: "#374151", display: "block", marginBottom: 4 }}>{label}</label>
                <select onChange={e => setForm({ ...form, [label.toLowerCase()]: e.target.value })}
                  style={{ width: "100%", border: "1px solid #d1d5db", borderRadius: 8, padding: "8px 12px", fontSize: 13, boxSizing: "border-box" }}>
                  {label === "Kategori"
                    ? ["CPU", "Monitor", "Keyboard", "Mouse", "Proyektor", "Printer", "Speaker"].map(o => <option key={o}>{o}</option>)
                    : ["Baik", "Rusak Ringan", "Rusak Berat"].map(o => <option key={o}>{o}</option>)}
                </select>
              </div>
            ))]}
            <div style={{ marginBottom: 14 }}>
              <label style={{ fontSize: 13, color: "#374151", display: "block", marginBottom: 4 }}>Lokasi</label>
              <input value={form.location} onChange={e => setForm({ ...form, location: e.target.value })}
                placeholder="Masukkan lokasi" style={{ width: "100%", border: "1px solid #d1d5db", borderRadius: 8, padding: "8px 12px", fontSize: 13, boxSizing: "border-box" }} />
            </div>
            <div style={{ marginBottom: 20 }}>
              <label style={{ fontSize: 13, color: "#374151", display: "block", marginBottom: 4 }}>RFID Tag</label>
              <div style={{ display: "flex", gap: 8 }}>
                <input value={form.rfid} onChange={e => setForm({ ...form, rfid: e.target.value })}
                  placeholder="Scan RFID Tag" style={{ flex: 1, border: "1px solid #d1d5db", borderRadius: 8, padding: "8px 12px", fontSize: 13 }} />
                <button style={{ background: BLUE, color: "white", border: "none", borderRadius: 8, padding: "8px 16px", cursor: "pointer", fontSize: 13 }}>Scan</button>
              </div>
            </div>
            <div style={{ display: "flex", gap: 10, justifyContent: "flex-end" }}>
              <button onClick={() => setShowForm(false)} style={{ background: "white", border: "1px solid #d1d5db", borderRadius: 8, padding: "8px 20px", cursor: "pointer", fontSize: 13 }}>Batal</button>
              <button onClick={handleAdd} style={{ background: BLUE, color: "white", border: "none", borderRadius: 8, padding: "8px 20px", cursor: "pointer", fontSize: 13 }}>Simpan</button>
            </div>
          </div>
        </div>
      )}

      <div style={{ background: "white", borderRadius: 12, border: "1px solid #e5e7eb" }}>
        <div style={{ padding: "16px 20px", borderBottom: "1px solid #e5e7eb" }}>
          <div style={{ position: "relative", maxWidth: 320 }}>
            <span style={{ position: "absolute", left: 10, top: "50%", transform: "translateY(-50%)", color: "#9ca3af" }}>🔍</span>
            <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Cari nama / kode barang..."
              style={{ width: "100%", padding: "8px 12px 8px 34px", border: "1px solid #d1d5db", borderRadius: 8, fontSize: 13, boxSizing: "border-box" }} />
          </div>
        </div>
        <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 13 }}>
          <thead>
            <tr style={{ background: "#f9fafb" }}>
              { ["Kode", "Nama Barang", "Kategori", "Kondisi", "Status", "Aksi"].map(h => (
                <th key={h} style={{ padding: "10px 16px", textAlign: "left", color: "#6b7280", fontWeight: 500, borderBottom: "1px solid #e5e7eb" }}>{h}</th>
              ))}
            </tr>
          </thead>
          <tbody>
            {filtered.map((item, i) => (
              <tr key={item.id} style={{ borderBottom: "1px solid #f3f4f6" }}>
                <td style={{ padding: "12px 16px", color: "#374151", fontWeight: 500 }}>{item.id}</td>
                <td style={{ padding: "12px 16px", color: "#111827" }}>{item.name}</td>
                <td style={{ padding: "12px 16px", color: "#6b7280" }}>{item.category}</td>
                <td style={{ padding: "12px 16px", color: "#374151" }}>{item.condition}</td>
                <td style={{ padding: "12px 16px" }}><StatusBadge status={item.status} /></td>
                <td style={{ padding: "12px 16px", display: "flex", gap: 8 }}>
                  <button style={{ background: "none", border: "none", cursor: "pointer", color: BLUE, fontSize: 16 }}>✏️</button>
                  <button onClick={() => handleDelete(item.id)} style={{ background: "none", border: "none", cursor: "pointer", color: RED, fontSize: 16 }}>🗑️</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
        <div style={{ padding: "12px 20px", display: "flex", justifyContent: "flex-end", gap: 6 }}>
          {[1, 2, 3].map(p => (
            <button key={p} style={{ width: 32, height: 32, borderRadius: 6, border: p === 1 ? `1px solid ${BLUE}` : "1px solid #d1d5db", background: p === 1 ? BLUE : "white", color: p === 1 ? "white" : "#374151", cursor: "pointer", fontSize: 13 }}>{p}</button>
          ))}
        </div>
      </div>
    </div>
  );
};

const PeminjamanPage = () => {
  const [step, setStep] = useState(0);
  const [scannedUser, setScannedUser] = useState(false);
  const [scannedItem, setScannedItem] = useState(false);

  return (
    <div style={{ padding: 24, maxWidth: 560 }}>
      <h2 style={{ margin: "0 0 24px", fontSize: 20 }}>Peminjaman Barang</h2>
      <div style={{ background: "white", borderRadius: 12, border: "1px solid #e5e7eb", padding: 24 }}>
        <div style={{ marginBottom: 24 }}>
          <div style={{ color: BLUE, fontWeight: 600, fontSize: 14, marginBottom: 12 }}>1. Scan RFID Pengguna</div>
          <div style={{ display: "flex", gap: 14, alignItems: "center" }}>
            <div style={{ width: 72, height: 72, borderRadius: 10, border: "2px dashed #d1d5db", display: "flex", alignItems: "center", justifyContent: "center", fontSize: 28, flexShrink: 0 }}>📡</div>
            {scannedUser ? (
              <div style={{ background: GREEN_LIGHT, border: `1px solid ${GREEN}`, borderRadius: 10, padding: "12px 16px", flex: 1 }}>
                <div style={{ fontSize: 12, color: "#6b7280" }}>Kartu terbaca</div>
                <div style={{ fontWeight: 600, color: "#111827", fontSize: 14 }}>M. Aqil Mihdadu Fatih</div>
                <div style={{ fontSize: 12, color: "#6b7280" }}>NIM. G2A022001</div>
              </div>
            ) : (
              <div>
                <p style={{ margin: "0 0 8px", fontSize: 13, color: "#374151" }}>Tempelkan kartu RFID pengguna</p>
                <button onClick={() => setScannedUser(true)} style={{ background: BLUE, color: "white", border: "none", borderRadius: 8, padding: "8px 16px", cursor: "pointer", fontSize: 13 }}>Simulasi Scan</button>
              </div>
            )}
          </div>
        </div>
        <div style={{ marginBottom: 24 }}>
          <div style={{ color: BLUE, fontWeight: 600, fontSize: 14, marginBottom: 12 }}>2. Scan RFID Barang</div>
          <div style={{ display: "flex", gap: 14, alignItems: "center" }}>
            <div style={{ width: 72, height: 72, borderRadius: 10, border: "2px dashed #d1d5db", display: "flex", alignItems: "center", justifyContent: "center", fontSize: 28, flexShrink: 0 }}>🏷️</div>
            {scannedItem ? (
              <div style={{ background: GREEN_LIGHT, border: `1px solid ${GREEN}`, borderRadius: 10, padding: "12px 16px", flex: 1 }}>
                <div style={{ fontSize: 12, color: "#6b7280" }}>Barang terbaca</div>
                <div style={{ fontWeight: 600, color: "#111827", fontSize: 14 }}>Proyektor Epson</div>
                <div style={{ fontSize: 12, color: "#6b7280" }}>Kode: A005</div>
              </div>
            ) : (
              <div>
                <p style={{ margin: "0 0 8px", fontSize: 13, color: "#374151" }}>Tempelkan tag RFID barang untuk dipinjam</p>
                <button onClick={() => setScannedItem(true)} style={{ background: BLUE, color: "white", border: "none", borderRadius: 8, padding: "8px 16px", cursor: "pointer", fontSize: 13 }}>Simulasi Scan</button>
              </div>
            )}
          </div>
        </div>
        {scannedUser && scannedItem && (
          <div style={{ marginBottom: 20 }}>
            <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 8, fontSize: 13, marginBottom: 16 }}>
              {[ ["Status Barang", "Tersedia"], ["Lokasi", "Lab. 1"], ["Kondisi", "Baik"] ].map(([k, v]) => (
                <div key={k}><span style={{ color: "#6b7280" }}>{k}</span><span style={{ color: "#111827", marginLeft: 8 }}>: {v}</span></div>
              ))}
            </div>
            <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 12 }}>
              {[ ["Tanggal Pinjam", "13/04/2026", "📅"], ["Batas Kembali", "15/04/2026", "📅"] ].map(([label, val, icon]) => (
                <div key={label}>
                  <label style={{ fontSize: 12, color: "#6b7280", display: "block", marginBottom: 4 }}>{label}</label>
                  <div style={{ border: "1px solid #d1d5db", borderRadius: 8, padding: "8px 12px", display: "flex", justifyContent: "space-between", alignItems: "center", fontSize: 13 }}>
                    {val} <span>{icon}</span>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}
        <button disabled={!scannedUser || !scannedItem}
          style={{ width: "100%", background: scannedUser && scannedItem ? BLUE : "#9ca3af", color: "white", border: "none", borderRadius: 10, padding: 14, cursor: scannedUser && scannedItem ? "pointer" : "not-allowed", fontSize: 14, fontWeight: 600 }}>
          PROSES PEMINJAMAN
        </button>
      </div>
    </div>
  );
};

const PengembalianPage = () => {
  const [scanned, setScanned] = useState(false);
  const [condition, setCondition] = useState("Baik");
  const [done, setDone] = useState(false);

  if (done) return (
    <div style={{ padding: 24, display: "flex", alignItems: "center", justifyContent: "center", minHeight: 400 }}>
      <div style={{ textAlign: "center" }}>
        <div style={{ fontSize: 64, marginBottom: 16 }}>✅</div>
        <h3 style={{ color: GREEN, marginBottom: 8 }}>Pengembalian Berhasil!</h3>
        <p style={{ color: "#6b7280", marginBottom: 16 }}>Proyektor Epson telah dikembalikan</p>
        <button onClick={() => { setScanned(false); setDone(false); setCondition("Baik"); }} style={{ background: BLUE, color: "white", border: "none", borderRadius: 8, padding: "10px 24px", cursor: "pointer", fontSize: 14 }}>Kembali</button>
      </div>
    </div>
  );

  return (
    <div style={{ padding: 24, maxWidth: 520 }}>
      <h2 style={{ margin: "0 0 24px", fontSize: 20 }}>Pengembalian Barang</h2>
      <div style={{ background: "white", borderRadius: 12, border: "1px solid #e5e7eb", padding: 24 }}>
        <div style={{ color: BLUE, fontWeight: 600, fontSize: 14, marginBottom: 12 }}>Scan RFID Barang</div>
        <div style={{ display: "flex", gap: 14, alignItems: "center", marginBottom: 20 }}>
          <div style={{ width: 72, height: 72, borderRadius: 10, border: "2px dashed #d1d5db", display: "flex", alignItems: "center", justifyContent: "center", fontSize: 28, flexShrink: 0 }}>🏷️</div>
          {scanned ? (
            <div style={{ background: GREEN_LIGHT, border: `1px solid ${GREEN}`, borderRadius: 10, padding: "12px 16px", flex: 1 }}>
              <div style={{ fontSize: 12, color: "#6b7280" }}>Barang terbaca</div>
              <div style={{ fontWeight: 600, color: "#111827", fontSize: 14 }}>Proyektor Epson</div>
              <div style={{ fontSize: 12, color: "#6b7280" }}>Kode: A005</div>
            </div>
          ) : (
            <div>
              <p style={{ margin: "0 0 8px", fontSize: 13, color: "#374151" }}>Tempelkan tag RFID barang untuk dikembalikan</p>
              <button onClick={() => setScanned(true)} style={{ background: BLUE, color: "white", border: "none", borderRadius: 8, padding: "8px 16px", cursor: "pointer", fontSize: 13 }}>Simulasi Scan</button>
            </div>
          )}
        </div>
        {scanned && (
          <>
            <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 8, fontSize: 13, marginBottom: 20, background: "#f9fafb", borderRadius: 8, padding: 14 }}>
              {[ ["Peminjam", "M. Aqil Mihdadu Fatih"], ["Tanggal Pinjam", "13/04/2026"], ["Batas Kembali", "15/04/2026"], ["Durasi Pinjam", "2 Hari"] ].map(([k, v]) => (
                <div key={k}>
                  <div style={{ color: "#6b7280", fontSize: 12 }}>{k}</div>
                  <div style={{ color: "#111827", fontWeight: 500 }}>: {v}</div>
                </div>
              ))}
            </div>
            <div style={{ marginBottom: 20 }}>
              <div style={{ fontWeight: 600, fontSize: 14, marginBottom: 10 }}>Kondisi Barang</div>
              {["Baik", "Rusak Ringan", "Rusak Berat"].map(c => (
                <label key={c} style={{ display: "flex", alignItems: "center", gap: 8, marginBottom: 8, cursor: "pointer", fontSize: 13 }}>
                  <input type="radio" name="condition" value={c} checked={condition === c} onChange={() => setCondition(c)} />
                  {c}
                </label>
              ))}
            </div>
          </>
        )}
        <button onClick={() => scanned && setDone(true)} disabled={!scanned}
          style={{ width: "100%", background: scanned ? GREEN : "#9ca3af", color: "white", border: "none", borderRadius: 10, padding: 14, cursor: scanned ? "pointer" : "not-allowed", fontSize: 14, fontWeight: 600 }}>
          REMBALIKAN
        </button>
      </div>
    </div>
  );
};

const JadwalPage = () => {
  const [showForm, setShowForm] = useState(false);
  return (
    <div style={{ padding: 24 }}>
      <div style={{ display: "flex", justifyContent: "space-between", marginBottom: 20, alignItems: "center" }}>
        <h2 style={{ margin: 0, fontSize: 20 }}>Jadwal Laboratorium</h2>
        <button onClick={() => setShowForm(!showForm)} style={{ background: BLUE, color: "white", border: "none", borderRadius: 8, padding: "8px 16px", cursor: "pointer", fontSize: 13 }}>
          + Tambah Jadwal
        </button>
      </div>
      {showForm && (
        <div style={{ background: "white", borderRadius: 12, border: "1px solid #e5e7eb", padding: 20, marginBottom: 20 }}>
          <h3 style={{ margin: "0 0 16px", fontSize: 15 }}>Tambah Jadwal Baru</h3>
          <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 14 }}>
            { ["Tanggal", "Waktu", "Dosen", "Kelas", "Lab"].map(f => (
              <div key={f}>
                <label style={{ fontSize: 13, color: "#374151", display: "block", marginBottom: 4 }}>{f}</label>
                <input placeholder={`Masukkan ${f.toLowerCase()}`} style={{ width: "100%", border: "1px solid #d1d5db", borderRadius: 8, padding: "8px 12px", fontSize: 13, boxSizing: "border-box" }} />
              </div>
            ))}
          </div>
          <div style={{ display: "flex", gap: 10, marginTop: 16, justifyContent: "flex-end" }}>
            <button onClick={() => setShowForm(false)} style={{ background: "white", border: "1px solid #d1d5db", borderRadius: 8, padding: "8px 20px", cursor: "pointer", fontSize: 13 }}>Batal</button>
            <button style={{ background: BLUE, color: "white", border: "none", borderRadius: 8, padding: "8px 20px", cursor: "pointer", fontSize: 13 }}>Simpan</button>
          </div>
        </div>
      )}
      <div style={{ background: "white", borderRadius: 12, border: "1px solid #e5e7eb" }}>
        <div style={{ padding: "14px 20px", borderBottom: "1px solid #e5e7eb", display: "flex", justifyContent: "space-between", alignItems: "center" }}>
          <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
            <button style={{ background: "none", border: "none", cursor: "pointer", fontSize: 18 }}>‹</button>
            <span style={{ fontWeight: 600, fontSize: 14 }}>April 2026</span>
            <button style={{ background: "none", border: "none", cursor: "pointer", fontSize: 18 }}>›</button>
          </div>
          <span style={{ fontSize: 20 }}>📅</span>
        </div>
        <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 13 }}>
          <thead>
            <tr style={{ background: "#f9fafb" }}>
              { ["Tanggal", "Waktu", "Dosen", "Kelas", "Lab"].map(h => (
                <th key={h} style={{ padding: "10px 16px", textAlign: "left", color: "#6b7280", fontWeight: 500, borderBottom: "1px solid #e5e7eb" }}>{h}</th>
              ))}
            </tr>
          </thead>
          <tbody>
            {scheduleData.map((s, i) => (
              <tr key={i} style={{ borderBottom: "1px solid #f3f4f6" }}>
                <td style={{ padding: "12px 16px", color: "#374151" }}>{s.date}</td>
                <td style={{ padding: "12px 16px", color: "#374151" }}>{s.time}</td>
                <td style={{ padding: "12px 16px", color: "#111827", fontWeight: 500 }}>{s.lecturer}</td>
                <td style={{ padding: "12px 16px", color: "#374151" }}>{s.class}</td>
                <td style={{ padding: "12px 16px", color: "#374151" }}>{s.lab}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

const LaporanPage = () => {
  const reportData = [
    { cat: "CPU", total: 25, pinjam: 8, kembali: 7, rusak: 1 },
    { cat: "Monitor", total: 30, pinjam: 6, kembali: 6, rusak: 0 },
    { cat: "Proyektor", total: 10, pinjam: 3, kembali: 3, rusak: 1 },
    { cat: "Keyboard", total: 20, pinjam: 5, kembali: 5, rusak: 0 },
    { cat: "Mouse", total: 20, pinjam: 6, kembali: 6, rusak: 0 },
  ];
  const lineData = [
    { label: "Jan", val: 20 }, { label: "Feb", val: 28 }, { label: "Mar", val: 18 },
    { label: "Apr", val: 35 }, { label: "Mei", val: 30 }, { label: "Jun", val: 25 },
  ];
  const totals = { total: 105, pinjam: 28, kembali: 27, rusak: 2 };
  return (
    <div style={{ padding: 24 }}>
      <h2 style={{ margin: "0 0 20px", fontSize: 20 }}>Laporan Inventaris &amp; Peminjaman</h2>
      <div style={{ background: "white", borderRadius: 12, border: "1px solid #e5e7eb", padding: 20 }}>
        <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 20, flexWrap: "wrap", gap: 12 }}>
          <div style={{ display: "flex", gap: 10, alignItems: "center" }}>
            <span style={{ fontSize: 13, color: "#374151" }}>Periode</span>
            <select style={{ border: "1px solid #d1d5db", borderRadius: 8, padding: "6px 12px", fontSize: 13 }}>
              { ["Januari", "Februari", "Maret", "April", "Mei", "Juni"].map(m => <option key={m}>{m}</option>) }
            </select>
            <select style={{ border: "1px solid #d1d5db", borderRadius: 8, padding: "6px 12px", fontSize: 13 }}>
              <option>2026</option><option>2025</option>
            </select>
            <button style={{ background: BLUE, color: "white", border: "none", borderRadius: 8, padding: "8px 16px", cursor: "pointer", fontSize: 13, display: "flex", alignItems: "center", gap: 6 }}>
              📊 Tampilkan
            </button>
          </div>
          <div style={{ display: "flex", gap: 10 }}>
            <button style={{ background: GREEN_LIGHT, color: GREEN, border: `1px solid ${GREEN}`, borderRadius: 8, padding: "8px 14px", cursor: "pointer", fontSize: 13 }}>📥 Export Excel</button>
            <button style={{ background: RED_LIGHT, color: RED, border: `1px solid ${RED}`, borderRadius: 8, padding: "8px 14px", cursor: "pointer", fontSize: 13 }}>📄 Export PDF</button>
          </div>
        </div>
        <h3 style={{ margin: "0 0 12px", fontSize: 14, color: BLUE }}>Ringkasan</h3>
        <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 13, marginBottom: 24 }}>
          <thead>
            <tr style={{ background: "#f9fafb" }}>
              { ["Kategori", "Total Alat", "Dipinjam", "Dikembalikan", "Rusak"].map(h => (
                <th key={h} style={{ padding: "8px 12px", textAlign: "left", color: "#6b7280", fontWeight: 500, borderBottom: "1px solid #e5e7eb" }}>{h}</th>
              ))}
            </tr>
          </thead>
          <tbody>
            {reportData.map((r, i) => (
              <tr key={i} style={{ borderBottom: "1px solid #f3f4f6" }}>
                <td style={{ padding: "10px 12px", color: "#374151" }}>{r.cat}</td>
                <td style={{ padding: "10px 12px", color: "#111827", fontWeight: 500 }}>{r.total}</td>
                <td style={{ padding: "10px 12px", color: BLUE }}>{r.pinjam}</td>
                <td style={{ padding: "10px 12px", color: GREEN }}>{r.kembali}</td>
                <td style={{ padding: "10px 12px", color: r.rusak > 0 ? RED : "#374151" }}>{r.rusak}</td>
              </tr>
            ))}
            <tr style={{ background: "#f9fafb", fontWeight: 600 }}>
              <td style={{ padding: "10px 12px" }}>Total</td>
              <td style={{ padding: "10px 12px" }}>{totals.total}</td>
              <td style={{ padding: "10px 12px", color: BLUE }}>{totals.pinjam}</td>
              <td style={{ padding: "10px 12px", color: GREEN }}>{totals.kembali}</td>
              <td style={{ padding: "10px 12px", color: RED }}>{totals.rusak}</td>
            </tr>
          </tbody>
        </table>
        <h3 style={{ margin: "0 0 12px", fontSize: 14, color: BLUE }}>Grafik Statistik Peminjaman</h3>
        <LineChart data={lineData} />
      </div>
    </div>
  );
};

const PlaceholderPage = ({ title }) => (
  <div style={{ padding: 24, display: "flex", alignItems: "center", justifyContent: "center", minHeight: 300 }}>
    <div style={{ textAlign: "center", color: "#9ca3af" }}>
      <div style={{ fontSize: 48, marginBottom: 12 }}>🔧</div>
      <div style={{ fontSize: 16 }}>{title}</div>
      <div style={{ fontSize: 13, marginTop: 4 }}>Halaman dalam pengembangan</div>
    </div>
  </div>
);

export default function App() {
  const [loggedIn, setLoggedIn] = useState(false);
  const [role, setRole] = useState("Admin / Laboran");
  const [page, setPage] = useState("dashboard");
  const [items, setItems] = useState(initialItems);
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");

  if (!loggedIn) {
    return (
      <div style={{ minHeight: "100vh", background: "linear-gradient(135deg, #1e429f 0%, #1a56db 60%, #3b82f6 100%)", display: "flex", alignItems: "center", justifyContent: "center" }}>
        <div style={{ background: "white", borderRadius: 16, padding: 40, width: 360, boxShadow: "0 20px 60px rgba(0,0,0,0.2)" }}>
          <div style={{ textAlign: "center", marginBottom: 28 }}>
            <div style={{ width: 64, height: 64, borderRadius: "50%", background: BLUE_LIGHT, margin: "0 auto 12px", display: "flex", alignItems: "center", justifyContent: "center", fontSize: 28 }}>🔬</div>
            <h1 style={{ margin: "0 0 4px", fontSize: 22, color: "#111827", fontWeight: 700 }}>SIMPLELAB</h1>
            <p style={{ margin: 0, fontSize: 12, color: "#6b7280" }}>Sistem Manajemen Peralatan Laboratorium</p>
            <p style={{ margin: 0, fontSize: 11, color: "#9ca3af" }}>Universitas Muhammadiyah Semarang</p>
          </div>
          <div style={{ marginBottom: 14 }}>
            <div style={{ position: "relative" }}>
              <span style={{ position: "absolute", left: 12, top: "50%", transform: "translateY(-50%)" }}>👤</span>
              <input value={username} onChange={e => setUsername(e.target.value)} placeholder="Username / NIM / NIDN"
                style={{ width: "100%", padding: "10px 12px 10px 36px", border: "1px solid #d1d5db", borderRadius: 8, fontSize: 13, boxSizing: "border-box" }} />
            </div>
          </div>
          <div style={{ marginBottom: 16 }}>
            <div style={{ position: "relative" }}>
              <span style={{ position: "absolute", left: 12, top: "50%", transform: "translateY(-50%)" }}>🔒</span>
              <input type="password" value={password} onChange={e => setPassword(e.target.value)} placeholder="Password"
                style={{ width: "100%", padding: "10px 12px 10px 36px", border: "1px solid #d1d5db", borderRadius: 8, fontSize: 13, boxSizing: "border-box" }} />
            </div>
          </div>
          <div style={{ marginBottom: 20 }}>
            <label style={{ fontSize: 13, color: "#374151", display: "block", marginBottom: 8, fontWeight: 500 }}>Pilih Role</label>
            <select value={role} onChange={e => setRole(e.target.value)}
              style={{ width: "100%", border: "1px solid #d1d5db", borderRadius: 8, padding: "9px 12px", fontSize: 13, marginBottom: 10, boxSizing: "border-box" }}>
              <option>Admin / Laboran</option><option>Dosen</option><option>Mahasiswa</option>
            </select>
            {["Admin / Laboran", "Dosen", "Mahasiswa"].map(r => (
              <label key={r} style={{ display: "flex", alignItems: "center", gap: 8, marginBottom: 4, cursor: "pointer", fontSize: 13 }}>
                <input type="radio" name="role" value={r} checked={role === r} onChange={() => setRole(r)} /> {r}
              </label>
            ))}
          </div>
          <button onClick={() => setLoggedIn(true)}
            style={{ width: "100%", background: BLUE, color: "white", border: "none", borderRadius: 10, padding: 12, cursor: "pointer", fontSize: 15, fontWeight: 600 }}>
            LOGIN
          </button>
          <p style={{ textAlign: "center", fontSize: 11, color: "#9ca3af", marginTop: 20, marginBottom: 0 }}>
            © 2026 Universitas Muhammadiyah Semarang<br />All rights reserved.
          </p>
        </div>
      </div>
    );
  }

  const pages = {
    dashboard: <DashboardPage />,
    inventaris: <InventarisPage items={items} setItems={setItems} />,
    peminjaman: <PeminjamanPage />,
    pengembalian: <PengembalianPage />,
    jadwal: <JadwalPage />,
    laporan: <LaporanPage />,
    manajemen: <PlaceholderPage title="Manajemen User" />,
    pengaturan: <PlaceholderPage title="Pengaturan" />,
  };

  const titles = { dashboard: "Dashboard", inventaris: "Inventaris Barang", peminjaman: "Peminjaman", pengembalian: "Pengembalian", jadwal: "Jadwal Laboratorium", laporan: "Laporan", manajemen: "Manajemen User", pengaturan: "Pengaturan" };

  return (
    <div style={{ display: "flex", minHeight: "100vh", background: "#f3f4f6", fontFamily: "system-ui, sans-serif" }}>
      <Sidebar active={page} setPage={setPage} />
      <div style={{ flex: 1, display: "flex", flexDirection: "column", overflow: "auto" }}>
        <Topbar title={titles[page]} />
        <div style={{ flex: 1, overflowY: "auto" }}>
          {pages[page]}
        </div>
      </div>
    </div>
  );
}
