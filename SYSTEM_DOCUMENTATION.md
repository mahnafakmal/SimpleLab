# 📚 SimpleLab - Sistem Manajemen Alat Laboratorium UNIMUS

## 🎯 Fitur Implementasi Lengkap

Berikut adalah dokumentasi lengkap dari semua fitur yang telah diimplementasikan dalam sistem SimpleLab:

---

## 1️⃣ Dashboard Status Alat & Jadwal Laboratorium

### File Terkait:
- **Controller**: `app/Http/Controllers/DashboardController.php`
- **View**: `resources/views/dashboard-enhanced.blade.php`
- **Route**: `/` (route dashboard)

### Fitur:
✅ **Dashboard Statistik Real-time**
- Menampilkan total alat, alat tersedia, alat dipinjam, dan alat rusak
- Grafik Status Alat (Doughnut Chart)
- Grafik Frekuensi Peminjaman (Top 10 Alat)
- Grafik Tren Peminjaman (30 Hari Terakhir)

✅ **Informasi Peminjaman Aktif**
- Daftar peminjaman aktif pengguna saat ini
- Indikator barang yang terlambat dikembalikan
- Perhitungan hari keterlambatan otomatis

✅ **Jadwal Hari Ini**
- Tampilan jadwal laboratorium hari ini
- Informasi dosen, waktu, dan ruangan

✅ **Alat Paling Sering Dipinjam**
- Top 5 alat berdasarkan frekuensi peminjaman

---

## 2️⃣ Sistem Pengembalian Alat via Scan RFID

### File Terkait:
- **Controller**: `app/Http/Controllers/EquipmentReturnController.php`
- **View**: `resources/views/equipment/return-form.blade.php`
- **Model Method**: `Peminjaman::markReturned()`, `Barang::getActiveLoan()`
- **Route**: `/equipment/return` (GET), `/equipment/return/scan` (POST)

### Fitur:
✅ **RFID Scanner Integration**
- Form input untuk scan RFID tag/kartu
- Real-time processing hasil scan
- Auto-focus pada input scanner

✅ **Daftar Peminjaman Aktif**
- Menampilkan semua barang yang sedang dipinjam
- Informasi tanggal peminjaman dan tenggat
- Status keterlambatan dengan badge visual

✅ **Pengembalian Manual**
- Alternatif jika RFID tidak berfungsi
- Pilih dari daftar barang yang dipinjam
- Konfirmasi sebelum mengembalikan

✅ **Respons Sistem:**
```json
{
  "success": true,
  "message": "Barang berhasil dikembalikan",
  "equipment": "Arduino Uno R3",
  "returnedAt": "25/06/2026 14:30"
}
```

---

## 3️⃣ Validasi Kesesuaian Barang Dikembalikan

### File Terkait:
- **Controller Method**: `EquipmentReturnController::validateEquipmentCondition()`
- **Model Method**: `Barang::isAvailable()`, `Peminjaman::validateReturn()`
- **TagRfid Method**: `TagRfid::isValidTag()`, `TagRfid::isRegisteredEquipment()`

### Fitur:
✅ **Validasi RFID Registered**
- Cek apakah RFID terdaftar dalam sistem
- Tolak RFID yang tidak terdaftar dengan pesan jelas

✅ **Validasi Kecocokan Barang**
- Verifikasi barang yang dipindai sesuai peminjaman pengguna
- Cek status peminjaman aktif
- Validasi kondisi barang (tidak rusak)

✅ **Laporan Kerusakan**
- Sistem mendeteksi laporan kerusakan selama periode peminjaman
- Mencegah pengembalian barang yang dilaporkan rusak

✅ **Pesan Error Terstruktur:**
```
- "RFID tidak terdaftar dalam sistem"
- "Barang tidak ditemukan untuk RFID ini"
- "Anda tidak memiliki peminjaman aktif untuk barang ini"
- "Barang tidak dapat dikembalikan - Barang memiliki laporan kerusakan"
```

---

## 4️⃣ Notifikasi Barang Terlambat Dikembalikan

### File Terkait:
- **Notification Classes**: 
  - `app/Notifications/EquipmentOverdueNotification.php`
  - `app/Notifications/EquipmentReturnedNotification.php`
- **Console Command**: `app/Console/Commands/CheckOverdueEquipment.php`
- **Model Method**: `Peminjaman::isOverdue()`, `Peminjaman::getDaysOverdue()`

### Fitur:
✅ **Email Notification Otomatis**
- Notifikasi ke email pengguna untuk barang terlambat
- Notifikasi pengembalian barang berhasil
- Subject berbahasa Indonesia dengan emoji

✅ **Konten Email:**
```
Subject: ⚠️ Peringatan: Barang Terlambat Dikembalikan - SimpleLab

Halo [Nama Pengguna],

Anda memiliki barang yang terlambat dikembalikan:

**Barang:** Arduino Uno R3
**Kategori:** Mikrokontroler
**Tenggat Waktu:** 24/06/2026
**Terlambat:** 1 hari

[Tombol] Kembalikan Sekarang
```

✅ **Cron Command**
```bash
# Jalankan untuk cek keterlambatan
php artisan equipment:check-overdue

# Tambahkan ke scheduler (app/Console/Kernel.php)
$schedule->command('equipment:check-overdue')->daily();
```

✅ **Database Notifications**
- Notifikasi disimpan di database untuk history
- Dapat diakses dari dashboard pengguna

---

## 5️⃣ Manajemen Jadwal Reservasi Laboratorium

### File Terkait:
- **Controller**: `app/Http/Controllers/ScheduleController.php`
- **Model**: `app/Models/JadwalLab.php` (enhanced)
- **View**: `resources/views/schedule/index.blade.php`
- **Routes**: 
  - `/schedule` (GET)
  - `/api/schedule` (GET JSON)
  - `/schedule` (POST) - Admin
  - `/schedule/{id}` (PUT/DELETE) - Admin

### Fitur:
✅ **Tampilan Jadwal**
- Jadwal hari ini dengan highlight
- Jadwal minggu depan terorganisir per hari
- Informasi lengkap: waktu, dosen, ruangan, kapasitas

✅ **Filter Pencarian**
- Cari berdasarkan nama mata kuliah
- Cari berdasarkan nama dosen
- Filter berdasarkan hari

✅ **Management Admin**
- Tambah jadwal baru
- Edit jadwal yang ada
- Hapus jadwal
- Form validasi lengkap

✅ **API Endpoints:**
```javascript
GET /api/schedule
Response: {
  "success": true,
  "schedules": [
    {
      "id": 1,
      "title": "Sistem Mikrokontroler (A1)",
      "day": "Senin",
      "time": "08:00 - 10:00",
      "instructor": "Dr. Budiman",
      "room": "Lab Elektronika",
      "capacity": 30
    }
  ]
}
```

---

## 6️⃣ Grafik Frekuensi Peminjaman Alat

### File Terkait:
- **Controller**: `app/Http/Controllers/StatisticsController.php`
- **View Components**: Dashboard charts (Chart.js)
- **Routes**: `/api/statistics/*`

### Fitur:
✅ **Berbagai Jenis Grafik:**

1. **Status Alat (Doughnut Chart)**
   - Tersedia, Dipinjam, Rusak
   - Persentase visual
   - Update real-time setiap 5 menit

2. **Frekuensi Peminjaman (Bar Chart Horizontal)**
   - Top 10 alat paling sering dipinjam
   - Urutkan dari terbanyak ke terendah

3. **Tren Peminjaman (Line Chart)**
   - Data 30 hari terakhir
   - Tampilkan trend naik/turun
   - Tooltip dengan tanggal

4. **Distribusi Kategori (Pie Chart)**
   - Breakdown per kategori alat
   - Persentase visual

5. **Laporan Kondisi Alat (Bar Chart)**
   - Baik, Rusak, Perbaikan
   - Count per kondisi

✅ **API Endpoints:**
```
GET /api/statistics/borrowing-frequency
GET /api/statistics/equipment-status
GET /api/statistics/trends
GET /api/statistics/categories
GET /api/statistics/user
GET /api/statistics/top-items
GET /api/statistics/condition
GET /api/statistics/dashboard (comprehensive)
```

---

## 7️⃣ Antarmuka Web Intuitif & Responsif

### File Terkait:
- **Layout Master**: `resources/views/layouts/app-enhanced.blade.php`
- **CSS**: Integrated Bootstrap 5 dengan custom styling UNIMUS

### Fitur:
✅ **User Interface Modern**
- Color scheme UNIMUS (Primary: #003366, Secondary: #ff6b35)
- Desain flat, clean, dan profesional
- Konsistensi visual di semua halaman

✅ **Responsive Design**
- Mobile-first approach
- Sidebar kolapsibel di mobile
- Grid layout yang adaptive
- Tested di mobile, tablet, desktop

✅ **Navigation Intuitif**
- Sidebar dengan menu utama
- Breadcrumb trail
- Dropdown menu user
- Quick action buttons

✅ **Visual Feedback**
- Hover effects pada tombol
- Loading indicators
- Success/Error messages
- Toast notifications
- Badge untuk status

✅ **Accessibility**
- Keyboard navigation support
- ARIA labels
- Color contrast compliance
- Screen reader friendly

---

## 8️⃣ Notifikasi Status Peminjaman ke Email

### Fitur:
✅ **Email Notifications:**

1. **Pengembalian Berhasil**
   - Notifikasi ketika barang berhasil dikembalikan
   - Detail waktu pengembalian
   - Durasi peminjaman

2. **Peringatan Keterlambatan**
   - Notifikasi otomatis saat barang lewat tenggat
   - Berulang setiap hari jika masih terlambat
   - Severity tinggi jika > 7 hari

3. **Email Configuration** (`config/mail.php`):
```php
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io  // atau mailgun
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@simplelab.unimus.ac.id"
MAIL_FROM_NAME="SimpleLab"
```

✅ **Template Email Multilingual**
- Bahasa Indonesia dengan terminologi lokal
- Format HTML yang responsif
- Logo UNIMUS di header
- Tombol CTA (Call-to-Action)

---

## 9️⃣ Penolakan RFID Tidak Terdaftar

### File Terkait:
- **Model Method**: `TagRfid::isValidTag()`, `TagRfid::isRegisteredEquipment()`
- **Controller Method**: `EquipmentReturnController::processScan()`

### Fitur:
✅ **Validasi Ketat RFID**
- Cek database sebelum processing
- Tolak RFID dengan pesan error jelas

✅ **Response untuk RFID Invalid:**
```json
{
  "success": false,
  "message": "RFID tidak terdaftar dalam sistem",
  "error": "unregistered_rfid"
}
```

✅ **Admin dapat:**
- Melihat daftar RFID terdaftar
- Menambah RFID baru
- Menghubungkan RFID dengan barang/user
- Menonaktifkan RFID

---

## 🔟 Logo UNIMUS di Setiap Halaman

### File Terkait:
- **Layout**: `resources/views/layouts/app-enhanced.blade.php`
- **Header Section**: Navbar dengan logo

### Fitur:
✅ **Logo UNIMUS:**
- Ditampilkan di header semua halaman
- Link ke www.unimus.ac.id
- Respon: 50x50 px (optimal size)
- URL: https://unimus.ac.id/wp-content/uploads/2021/10/logo-unimus-new.png

✅ **Branding Elements:**
```html
<div class="navbar-brand">
    <img src="https://unimus.ac.id/wp-content/uploads/2021/10/logo-unimus-new.png" 
         alt="UNIMUS Logo">
    <span>
        SimpleLab
        <small>Laboratory Equipment Management</small>
    </span>
</div>
```

✅ **Header Design:**
- Background gradient UNIMUS colors
- Sticky header (tetap di atas saat scroll)
- Responsive font sizing
- Dark theme untuk readability

---

## 🚀 Cara Menggunakan

### 1. Login
```
Admin: 
  Email: admin@simplelab.com
  Password: Admin12345

Dosen:
  Email: DosenBudi@gmail.com
  Password: DosenBudi123
```

### 2. Dashboard
- Akses otomatis setelah login
- Lihat status alat & jadwal
- Pantau peminjaman aktif

### 3. Pengembalian Alat
1. Klik "Pengembalian Alat" di menu
2. Siapkan pembaca RFID
3. Arahkan tag ke pembaca
4. Sistem akan memvalidasi & confirm

### 4. Jadwal Laboratorium
- Klik "Jadwal Lab" di menu
- Lihat jadwal hari ini & minggu depan
- (Admin) Tambah/edit jadwal baru

### 5. Laporan & Statistik
- Akses dari dashboard
- View berbagai chart & grafik
- Export data jika diperlukan

---

## 📊 Struktur Database

### Tabel Utama:
```sql
-- Peminjamans
ALTER TABLE peminjamans ADD:
  - returned_at (timestamp nullable)
  - due_date (timestamp nullable)

-- JadwalLabs  
ALTER TABLE jadwal_labs ADD:
  - ruangan (string nullable)
  - kapasitas (integer nullable)
```

---

## 🔧 Maintenance & Monitoring

### Console Commands:
```bash
# Cek barang terlambat (jalankan daily via cron)
php artisan equipment:check-overdue

# Clear cache jika ada issue
php artisan cache:clear
php artisan view:clear

# Rebuild cache
php artisan config:cache
php artisan view:cache
```

### Scheduler (app/Console/Kernel.php):
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('equipment:check-overdue')
             ->daily()
             ->at('08:00');
}
```

---

## 📱 Fitur Mobile

- ✅ Responsive design tested
- ✅ Touch-friendly buttons
- ✅ Optimized charts untuk mobile
- ✅ Minimized navigation
- ✅ Fast loading times

---

## 🔐 Security Features

- ✅ CSRF protection (Laravel built-in)
- ✅ Authentication required untuk semua fitur
- ✅ Authorization checks (Admin-only features)
- ✅ Input validation di semua forms
- ✅ SQL Injection prevention (PDO)
- ✅ XSS protection

---

## 🎨 Customization

### Mengubah Warna UNIMUS:
Edit `resources/views/layouts/app-enhanced.blade.php`:
```css
--unimus-primary: #003366;    /* Biru tua */
--unimus-secondary: #ff6b35;  /* Orange */
--unimus-accent: #004d99;     /* Biru lebih terang */
```

### Mengubah Logo UNIMUS:
Ganti URL di navbar:
```html
<img src="YOUR_LOGO_URL" alt="UNIMUS Logo">
```

---

## 📝 Dokumentasi File

### Controllers Created:
- `app/Http/Controllers/EquipmentReturnController.php` - RFID return & validation
- `app/Http/Controllers/ScheduleController.php` - Lab schedule management
- `app/Http/Controllers/StatisticsController.php` - Charts & statistics

### Models Enhanced:
- `app/Models/Peminjaman.php` - Loan tracking methods
- `app/Models/Barang.php` - Equipment management methods
- `app/Models/User.php` - User relationship methods
- `app/Models/TagRfid.php` - RFID validation methods
- `app/Models/JadwalLab.php` - Schedule helper methods

### Notifications Created:
- `app/Notifications/EquipmentReturnedNotification.php`
- `app/Notifications/EquipmentOverdueNotification.php`

### Views Created:
- `resources/views/layouts/app-enhanced.blade.php` - Master layout
- `resources/views/dashboard-enhanced.blade.php` - Main dashboard
- `resources/views/equipment/return-form.blade.php` - RFID scanner
- `resources/views/schedule/index.blade.php` - Schedule management

### Migrations Created:
- `2026_06_25_000000_add_columns_to_peminjamans.php`
- `2026_06_25_000001_add_columns_to_jadwal_labs.php`

### Console Commands:
- `app/Console/Commands/CheckOverdueEquipment.php`

---

## ✅ Checklist Implementasi

- ✅ Dashboard dengan status alat & jadwal
- ✅ RFID scan untuk pengembalian alat
- ✅ Validasi kesesuaian barang dikembalikan
- ✅ Notifikasi keterlambatan
- ✅ Manajemen jadwal laboratorium
- ✅ Grafik frekuensi peminjaman
- ✅ UI responsif & user-friendly
- ✅ Email notifications
- ✅ RFID validation (reject unregistered)
- ✅ Logo UNIMUS di semua halaman

---

## 📞 Support & Troubleshooting

### Issue: Email notifikasi tidak terkirim
**Solusi:**
1. Cek konfigurasi MAIL di `.env`
2. Test dengan `php artisan tinker`
3. Jalankan queue: `php artisan queue:work`

### Issue: RFID scanner tidak detect
**Solusi:**
1. Pastikan RFID sudah terdaftar di database
2. Cek koneksi pembaca RFID
3. Gunakan form pengembalian manual

### Issue: Chart tidak tampil
**Solusi:**
1. Buka developer console (F12)
2. Cek error messages
3. Pastikan endpoint `/api/statistics/*` accessible
4. Refresh halaman

---

## 🎓 Kesimpulan

SimpleLab sekarang dilengkapi dengan fitur-fitur modern untuk manajemen alat laboratorium yang efisien, user-friendly, dan terintegrasi penuh dengan sistem notifikasi email dan RFID scanning. Sistem ini siap digunakan untuk mendukung operasional laboratorium UNIMUS.

---

**Terakhir Diupdate:** 25 Juni 2026  
**Versi:** 2.0 Enhanced  
**Status:** Production Ready ✅
