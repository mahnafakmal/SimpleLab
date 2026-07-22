<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\EquipmentReturnController;
use App\Http\Controllers\BorrowHistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPeminjamanController;
use App\Http\Controllers\LaporanKerusakanController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\View;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
// Separate login pages for dosen and mahasiswa
Route::get('/login/dosen', [AuthController::class, 'showLoginDosen'])->name('login.dosen');
Route::post('/login/dosen', [AuthController::class, 'loginDosen'])->name('login.dosen.store');
Route::get('/login/mahasiswa', [AuthController::class, 'showLoginMahasiswa'])->name('login.mahasiswa');
Route::post('/login/mahasiswa', [AuthController::class, 'loginMahasiswa'])->name('login.mahasiswa.store');
// Dosen specific registration
Route::get('/register/dosen', [AuthController::class, 'showRegisterDosen'])->name('register.dosen');
Route::post('/register/dosen', [AuthController::class, 'registerDosen'])->name('register.dosen.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// Public root: show the dashboard for authenticated users, otherwise show the simple static dashboard

Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Debug route: list all Barang as JSON (temporary)
Route::get('/debug/barangs', function () {
    return response()->json(App\Models\Barang::orderBy('id')->get());
});

// Protected Routes
Route::middleware('auth')->group(function () {

    // RFID endpoints
    Route::post('/barang/register', [RfidController::class, 'registerBarang']);
    Route::get('/barang/{id}/edit', [RfidController::class, 'editBarang']);
    Route::post('/barang/{id}/update', [RfidController::class, 'updateBarang']);
    // Support direct GET to the update URL by redirecting to the edit form
    Route::get('/barang/{id}/update', function ($id) {
        return redirect("/barang/{$id}/edit");
    });
    Route::post('/barang/{id}/delete', [RfidController::class, 'deleteBarang']);
    Route::post('/rfid/card/register', [RfidController::class, 'registerUserCard']);
    Route::post('/rfid/authenticate', [RfidController::class, 'authenticateUser']);
    Route::post('/rfid/track', [RfidController::class, 'trackAsset']);
    Route::post('/peminjaman/borrow', [RfidController::class, 'borrowAsset']);

    // Web Peminjaman & Booking endpoints
    Route::get('/equipment/borrow', [PeminjamanController::class, 'showBorrowForm'])->name('equipment.borrow');
    Route::get('/equipment/return', [EquipmentReturnController::class, 'showReturnForm'])->name('equipment.return');
    Route::get('/equipment/active-loans', [EquipmentReturnController::class, 'getActiveLoans'])->name('equipment.active-loans');
    Route::post('/equipment/return/scan', [EquipmentReturnController::class, 'processScan'])->name('equipment.return.scan');
    Route::post('/web/peminjaman/alat', [PeminjamanController::class, 'borrowAlat'])->name('web.peminjaman.alat');
    Route::post('/web/peminjaman/alat/dosen', [PeminjamanController::class, 'borrowAlatDosen'])->name('web.peminjaman.alat.dosen');
    Route::post('/web/pengembalian/alat/{id}', [PeminjamanController::class, 'returnAlat'])->name('web.pengembalian.alat');
    // Room booking routes removed (feature disabled)

    // Borrow history routes
    Route::get('/history', [BorrowHistoryController::class, 'index'])->name('history.index');
    Route::get('/history/overdue', [BorrowHistoryController::class, 'overdue'])->name('history.overdue');
    Route::get('/history/export-pdf', [BorrowHistoryController::class, 'exportPdf'])->name('history.export-pdf');
    Route::get('/history/{peminjaman}', [BorrowHistoryController::class, 'show'])->name('history.show');

    // Admin loan and room status routes
    Route::post('/admin/loan/status/{id}', [AdminPeminjamanController::class, 'updateLoanStatus'])->name('admin.loan.status');
    // Admin room status route removed (booking feature disabled)

    // Admin: create dosen account
    Route::post('/admin/users/dosen', [AuthController::class, 'createDosenByAdmin'])->name('admin.users.dosen.create');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    // Schedule Routes
    Route::get('/jadwal-lab', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/api/schedules', [ScheduleController::class, 'getSchedules'])->name('schedule.api');
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::put('/schedule/{id}', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::delete('/schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
    Route::get('/admin/kelola-jadwal', [ScheduleController::class, 'adminPage'])->name('admin.schedule.manage');
    Route::get('/jadwal-lab-legacy', [ScheduleController::class, 'index'])->name('jadwal.laboratorium');
    // List available items for users/admin
    Route::get('/barang/tersedia', [DashboardController::class, 'availableItems'])->name('barang.tersedia');
    Route::get('/barang/semua', [DashboardController::class, 'allItems'])->name('barang.semua');
    Route::get('/barang/dipinjam', [DashboardController::class, 'borrowedItems'])->name('barang.dipinjam');
    // Admin reports: history peminjaman
    Route::get('/admin/laporan/peminjaman', [DashboardController::class, 'reportPeminjaman'])->name('admin.laporan.peminjaman');
    Route::get('/admin/laporan/peminjaman/excel', [DashboardController::class, 'exportPeminjamanExcel'])->name('admin.laporan.peminjaman.excel');
    Route::get('/admin/laporan/registrasi', [DashboardController::class, 'reportRegistrasi'])->name('admin.laporan.registrasi');
    Route::get('/admin/laporan/registrasi/excel', [DashboardController::class, 'exportRegistrasiExcel'])->name('admin.laporan.registrasi.excel');

    // Laporan Kerusakan
    Route::get('/laporan-kerusakan', [LaporanKerusakanController::class, 'index'])->name('laporan.kerusakan.index');
    Route::post('/laporan-kerusakan', [LaporanKerusakanController::class, 'store'])->name('laporan.kerusakan.store');
    Route::post('/admin/laporan-kerusakan/{id}/status', [LaporanKerusakanController::class, 'updateStatus'])->name('admin.laporan.kerusakan.status');

    // RFID Management placeholder (fitur RFID dialihkan ke dashboard)
    Route::get('/admin/rfid', function () {
        return redirect()->route('dashboard')->with('info', 'Fitur Pengelolaan RFID belum tersedia.');
    })->name('rfid.index');

    Route::get('/admin/rfid/register-equipment', function () {
        return redirect()->route('dashboard');
    })->name('rfid.register-equipment');

    Route::get('/admin/rfid/register-user', function () {
        return redirect()->route('dashboard');
    })->name('rfid.register-user');

    Route::post('/admin/rfid/register-user', function () {
        return redirect()->route('dashboard');
    })->name('rfid.store-user');

    Route::post('/admin/rfid/register-equipment', function () {
        return redirect()->route('dashboard');
    })->name('rfid.store-equipment');

    // Mark unread notifications as read (AJAX)
    Route::post('/notifications/mark-read', function (\Illuminate\Http\Request $request) {
        $user = Auth::user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
        return response()->json(['ok' => true]);
    })->name('notifications.markRead');

    // API JSON endpoints (digunakan oleh dashboard sections)
    Route::get('/api/admin/users', function () {
        return response()->json(
            \App\Models\User::select('id', 'name', 'email', 'role')->orderBy('name')->get()
        );
    })->name('api.admin.users');

    Route::get('/api/admin/rfid-tags', function () {
        return response()->json([]);
    })->name('api.admin.rfid.tags');

    Route::post('/api/rfid/validate', function () {
        return response()->json(['valid' => false]);
    })->name('api.rfid.validate');
});

    // (demo routes removed)
