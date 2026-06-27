# 🚀 Quick Start Guide - SimpleLab v2.0

## 5 Menit Setup Pertama

### 1️⃣ Login ke Aplikasi
```
URL: http://localhost:8000

Admin:
  Email: admin@simplelab.com
  Password: Admin12345

Dosen:
  Email: DosenBudi@gmail.com
  Password: DosenBudi123
```

### 2️⃣ Dashboard Pertama
Setelah login, Anda akan melihat:
- 📊 Statistik alat (Total, Tersedia, Dipinjam, Rusak)
- 📈 4 grafik interaktif
- 📋 Jadwal hari ini
- 🎁 Barang paling sering dipinjam

**Tips**: Klik pada grafik untuk melihat detail lebih lanjut

---

## 🛠️ Cara Menggunakan Setiap Fitur

### 📱 Pengembalian Alat (RFID Scan)

**Langkah-langkah:**
1. Menu → Pengembalian Alat
2. Fokus pada input (cursor sudah ada)
3. Arahkan pembaca RFID ke tag/kartu
4. Tunggu konfirmasi "Berhasil"
5. Scan alat berikutnya atau kembali

**Jika RFID tidak berfungsi:**
- Scroll bawah → "Pengembalian Manual"
- Pilih barang dari list
- Klik "Kembalikan"

---

### 📅 Jadwal Laboratorium

**Melihat Jadwal:**
1. Menu → Jadwal Lab
2. Lihat "Jadwal Hari Ini" di atas
3. Scroll bawah untuk "Jadwal Minggu Depan"

**Mencari Jadwal:**
- Ketik nama mata kuliah di search box
- Filter berdasarkan hari

**Admin - Tambah Jadwal:**
1. Klik tombol "Tambah Jadwal Baru"
2. Isi form (hari, mata kuliah, jam, dosen, kelas)
3. Klik "Simpan"

---

### 📊 Melihat Statistik & Grafik

**Otomatis di Dashboard:**
1. Status Alat - Lingkaran warna (Available/Borrowed/Damaged)
2. Frekuensi - Bar chart alat yang sering dipinjam
3. Tren - Grafik line peminjaman 30 hari
4. Top Items - 5 alat paling sering dipinjam

**Interpretasi:**
- 🟢 Green = Alat Tersedia
- 🟡 Yellow = Sedang Dipinjam
- 🔴 Red = Rusak/Perbaikan

---

### 📧 Email Notifikasi

**Anda akan menerima email untuk:**

1. ✅ **Pengembalian Berhasil**
   - Otomatis saat Anda mengembalikan alat
   - Berisi detail barang & waktu pengembalian

2. ⚠️ **Peringatan Keterlambatan**
   - Saat barang melewati tenggat waktu
   - Berulang setiap hari jika masih terlambat

**Pastikan email address Anda sudah benar di profile.**

---

### 🔓 RFID Card Management (Admin)

**Register RFID Baru:**
1. Menu → Admin Panel
2. RFID Management → Register Card/Tag
3. Input UID (hasil scan RFID)
4. Link ke user/barang
5. Simpan

**Tolak RFID Invalid:**
- Sistem otomatis menolak RFID yang tidak terdaftar
- Pesan: "RFID tidak terdaftar dalam sistem"

---

## 🎨 Interface Tips

### 🎯 Navigation
```
Header (sticky di atas)
├── Logo UNIMUS
├── SimpleLab title
└── User dropdown (profile, logout)

Sidebar (collapsible di mobile)
├── Dashboard
├── Alat Laboratorium  
├── Pengembalian Alat
├── Jadwal Lab
└── (Admin) Laporan
```

### 🌈 Color Meanings
- 🔵 **Biru** = UNIMUS Primary (Main actions)
- 🟠 **Orange** = Secondary (Alerts, important)
- 🟢 **Hijau** = Available/Success
- 🟡 **Kuning** = Warning/In use
- 🔴 **Merah** = Error/Damaged

### ⚡ Quick Actions
- ⚡ "Pengembalian Alat" - Tombol prominent di sidebar
- ⚡ "Lihat Alat Tersedia" - List semua barang
- ⚡ "Jadwal Lab" - Quick view jadwal

---

## ❓ FAQ

**Q: RFID scan tidak detect?**
A: 
1. Pastikan RFID sudah terdaftar di admin
2. Cek koneksi pembaca RFID
3. Gunakan form manual di bawah

**Q: Tidak terima email notifikasi?**
A:
1. Cek folder Spam/Junk
2. Pastikan email di profile sudah benar
3. Hubungi admin

**Q: Bagaimana jika barang rusak?**
A:
1. Saat mengembalikan, pilih "Laporkan Kerusakan"
2. Isi form laporan kerusakan
3. Admin akan review dan approve

**Q: Lihat history peminjaman?**
A:
1. Menu → Alat Laboratorium
2. Filter "Alat yang Dipinjam"
3. Lihat daftar peminjaman Anda

**Q: Ubah password?**
A:
1. Klik user icon (top right)
2. Profile
3. Change Password

---

## 🚨 Common Issues & Solutions

| Issue | Solusi |
|-------|--------|
| Login gagal | Reset password atau hubungi admin |
| Dashboard grafik kosong | Refresh page (F5) atau tunggu beberapa detik |
| RFID tidak detect | Pastikan RFID registered & pembaca terhubung |
| Email tidak terkirim | Cek folder spam atau hubungi IT |
| Halaman lambat load | Clear browser cache (Ctrl+Shift+Delete) |

---

## 📞 Support

**Hubungi Admin untuk:**
- Register RFID baru
- Tambah jadwal lab
- Reset password
- Technical support
- Bug reports

**Contact:** lab@unimus.ac.id

---

## ✨ Pro Tips

1. **Auto-refresh**: Dashboard refresh data setiap 5 menit otomatis
2. **Mobile friendly**: Akses dari smartphone juga bisa
3. **Keyboard shortcut**: Tab untuk navigasi, Enter untuk submit
4. **Data export**: Admin bisa export laporan ke Excel (soon)
5. **Offline mode**: Beberapa fitur bisa offline (soon)

---

## 🎓 Untuk Admin

### Management Checklist:
- [ ] Setup email notifications
- [ ] Register all RFID cards
- [ ] Import existing lab schedules
- [ ] Train users on RFID scanner
- [ ] Setup backup database
- [ ] Monitor equipment usage

### Common Admin Tasks:
```bash
# Cek barang terlambat
php artisan equipment:check-overdue

# Clear cache if issues
php artisan cache:clear

# View logs
tail storage/logs/laravel.log

# Database backup
php artisan backup:run
```

---

## 🌟 Fitur yang Akan Datang

- 📱 Mobile app native
- 📥 Bulk import data
- 📊 Advanced reports & export
- 🔔 SMS notifications
- 💾 Automatic backup
- 🔐 Two-factor authentication
- 🌐 Multi-language support

---

**Happy Learning! 🎉**

For detailed documentation, see:
- `SYSTEM_DOCUMENTATION.md` - Full feature docs
- `SETUP_GUIDE.md` - Installation guide  
- `FEATURES_SUMMARY.md` - Feature breakdown

Last Updated: 25 Juni 2026
