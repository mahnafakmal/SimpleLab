# 🚀 Panduan Instalasi & Setup SimpleLab v2.0

## Prasyarat
- PHP 8.1+
- Laravel 10+
- MySQL/SQLite
- Composer
- Node.js (opsional, untuk asset compilation)

---

## 📥 Instalasi

### 1. Update Dependencies
```bash
cd c:\laragon\www\SimpleLab
composer update
npm install
```

### 2. Environment Configuration
Pastikan file `.env` sudah dikonfigurasi:
```env
# Database
DB_CONNECTION=sqlite
DB_DATABASE=sampledb

# Mail (untuk notifikasi email)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@simplelab.unimus.ac.id"
MAIL_FROM_NAME="SimpleLab"

# Queue (untuk email async)
QUEUE_CONNECTION=database
```

### 3. Database Migration
```bash
php artisan migrate
```

Jika ada error, jalankan dengan force:
```bash
php artisan migrate --force
```

### 4. Seed Database (Opsional)
```bash
php artisan db:seed --class=DatabaseSeeder
php artisan db:seed --class=CreateDosenBudiSeeder
```

### 5. Clear Cache
```bash
php artisan config:cache
php artisan view:cache
php artisan cache:clear
```

### 6. Start Development Server
```bash
php artisan serve
```

Aplikasi akan accessible di: `http://localhost:8000`

---

## 🔐 Login Credentials

### Admin
- **Email:** admin@simplelab.com
- **Password:** Admin12345

### Dosen
- **Email:** DosenBudi@gmail.com
- **Password:** DosenBudi123

---

## ⚙️ Konfigurasi Penting

### 1. Setup Cron untuk Check Overdue (Production)
Tambahkan ke crontab:
```bash
* * * * * cd /path/to/SimpleLab && php artisan schedule:run >> /dev/null 2>&1
```

Atau setup di Windows Task Scheduler:
```batch
"C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" -d display_errors=0 "c:\laragon\www\SimpleLab\artisan" schedule:run
```

### 2. Setup Email Queue (untuk performance)
```bash
# Terminal 1: Start queue worker
php artisan queue:work --daemon

# Terminal 2: Monitor queue
php artisan queue:failed
```

### 3. Setup Storage Directory
```bash
php artisan storage:link
```

---

## 🧪 Testing Features

### 1. Test Dashboard
1. Login dengan admin account
2. Akses `/` (route dashboard)
3. Pastikan semua chart loading (bisa lihat di browser console)
4. Refresh setelah 30 detik untuk update data

### 2. Test RFID Scanner
1. Login dengan user account
2. Klik "Pengembalian Alat" di menu
3. Jika tidak punya RFID, gunakan manual form
4. Test success notification

### 3. Test Schedule Management
1. Login dengan admin
2. Klik "Jadwal Lab"
3. Coba tambah jadwal baru
4. Verify jadwal muncul di list

### 4. Test Email Notifications
1. Setup mail driver (bisa pakai Mailtrap untuk testing)
2. Trigger overdue check:
   ```bash
   php artisan equipment:check-overdue
   ```
3. Cek inbox untuk email notification

---

## 📊 API Endpoints Reference

### Statistics Endpoints
```
GET /api/statistics/borrowing-frequency     - Top 10 alat paling dipinjam
GET /api/statistics/equipment-status        - Status alat (count)
GET /api/statistics/trends                  - Tren peminjaman 30 hari
GET /api/statistics/categories              - Distribusi kategori
GET /api/statistics/user                    - User stats
GET /api/statistics/top-items               - Top items
GET /api/statistics/condition               - Laporan kondisi
GET /api/statistics/dashboard               - Comprehensive stats
```

### Equipment Return Endpoints
```
POST /equipment/return/scan                 - Process RFID scan
GET /equipment/active-loans                 - Get user's active loans
POST /equipment/{loan}/damage               - Report damaged equipment
```

### Schedule Endpoints
```
GET /api/schedule                           - Get all schedules (JSON)
POST /schedule                              - Create schedule (admin)
PUT /schedule/{id}                          - Update schedule (admin)
DELETE /schedule/{id}                       - Delete schedule (admin)
```

---

## 🐛 Troubleshooting

### Database Errors
```bash
# Rollback semua migrations
php artisan migrate:reset

# Rerun migrations
php artisan migrate

# Seed data
php artisan db:seed
```

### Cache Issues
```bash
# Clear all cache
php artisan cache:clear
php artisan route:cache --force
php artisan config:cache
php artisan view:cache

# Clear compiled classes
php artisan optimize:clear
```

### View Compilation Errors
```bash
# Rebuild view cache
php artisan view:cache
php artisan view:clear
```

### Email Not Sending
1. Test mail configuration:
   ```bash
   php artisan tinker
   >>> Mail::raw('Test', fn($m) => $m->to('test@example.com'));
   ```

2. Check mail logs:
   ```
   storage/logs/laravel.log
   ```

3. Verify `.env` mail settings

---

## 📝 Developer Notes

### Key Files Modified:
```
✅ app/Models/Peminjaman.php
✅ app/Models/Barang.php  
✅ app/Models/User.php
✅ app/Models/TagRfid.php
✅ app/Models/JadwalLab.php
✅ app/Http/Controllers/DashboardController.php
✅ routes/web.php
```

### New Files Created:
```
✅ app/Http/Controllers/EquipmentReturnController.php
✅ app/Http/Controllers/ScheduleController.php
✅ app/Http/Controllers/StatisticsController.php
✅ app/Notifications/EquipmentReturnedNotification.php
✅ app/Notifications/EquipmentOverdueNotification.php
✅ app/Console/Commands/CheckOverdueEquipment.php
✅ resources/views/layouts/app-enhanced.blade.php
✅ resources/views/dashboard-enhanced.blade.php
✅ resources/views/equipment/return-form.blade.php
✅ resources/views/schedule/index.blade.php
✅ database/migrations/2026_06_25_000000_add_columns_to_peminjamans.php
✅ database/migrations/2026_06_25_000001_add_columns_to_jadwal_labs.php
```

---

## 🎨 Customization Tips

### Mengubah Theme Color
Edit `resources/views/layouts/app-enhanced.blade.php`:
```css
:root {
    --unimus-primary: #003366;      /* Biru UNIMUS */
    --unimus-secondary: #ff6b35;    /* Orange */
    --unimus-accent: #004d99;       /* Biru terang */
}
```

### Mengubah Logo
Edit navbar di layout:
```html
<img src="your-logo-url" alt="Logo">
```

### Mengubah Timezone
Edit `config/app.php`:
```php
'timezone' => 'Asia/Jakarta',
```

---

## 🔍 Performance Optimization

### Caching
```php
// Dashboard data caching
Cache::remember('equipment-status', 300, function() {
    return [
        'total' => Barang::count(),
        'available' => Barang::where('status', 'available')->count(),
    ];
});
```

### Database Query Optimization
```php
// Gunakan eager loading
$loans = Peminjaman::with(['barang', 'user'])->get();

// Gunakan select untuk field tertentu saja
$items = Barang::select('id', 'name', 'status')->get();
```

### Frontend Optimization
```html
<!-- Lazy load charts -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadChartsWhenVisible();
    });
</script>
```

---

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Chart.js Documentation](https://www.chartjs.org)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs)

---

## ✅ Setup Checklist

- [ ] Update composer dependencies
- [ ] Configure `.env` file
- [ ] Run migrations
- [ ] Seed database
- [ ] Clear cache
- [ ] Test login
- [ ] Test dashboard charts
- [ ] Test RFID scanner
- [ ] Test schedule management
- [ ] Setup email (if needed)
- [ ] Setup cron job (if production)
- [ ] Document any customizations

---

## 🆘 Support

Jika ada issue, lakukan:

1. Check log files:
   ```
   storage/logs/laravel.log
   ```

2. Enable debug mode in `.env`:
   ```env
   APP_DEBUG=true
   ```

3. Check database:
   ```bash
   php artisan tinker
   >>> DB::table('peminjamans')->count()
   ```

4. Check queue (if using async):
   ```bash
   php artisan queue:failed
   ```

---

**Selamat! SimpleLab v2.0 siap digunakan.** 🎉

Last Updated: 25 Juni 2026
