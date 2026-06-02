<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPeminjamanController;

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

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // RFID endpoints
    Route::post('/barang/register', [RfidController::class, 'registerBarang']);
    Route::post('/rfid/card/register', [RfidController::class, 'registerUserCard']);
    Route::post('/rfid/authenticate', [RfidController::class, 'authenticateUser']);
    Route::post('/rfid/track', [RfidController::class, 'trackAsset']);
    Route::post('/peminjaman/borrow', [RfidController::class, 'borrowAsset']);

    // Web Peminjaman & Booking endpoints
    Route::post('/web/peminjaman/alat', [PeminjamanController::class, 'borrowAlat'])->name('web.peminjaman.alat');
    Route::post('/web/pengembalian/alat/{id}', [PeminjamanController::class, 'returnAlat'])->name('web.pengembalian.alat');
    Route::post('/web/peminjaman/ruangan', [PeminjamanController::class, 'borrowRuangan'])->name('web.peminjaman.ruangan');
    Route::delete('/web/peminjaman/ruangan/{id}', [PeminjamanController::class, 'cancelRuangan'])->name('web.cancel.ruangan');

    // Admin loan and room status routes
    Route::post('/admin/loan/status/{id}', [AdminPeminjamanController::class, 'updateLoanStatus'])->name('admin.loan.status');
    Route::post('/admin/room/status/{id}', [AdminPeminjamanController::class, 'updateRoomStatus'])->name('admin.room.status');

    // Admin: create dosen account
    Route::post('/admin/users/dosen', [AuthController::class, 'createDosenByAdmin'])->name('admin.users.dosen.create');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // List available items for users/admin
    Route::get('/barang/tersedia', [DashboardController::class, 'availableItems'])->name('barang.tersedia');
    Route::get('/barang/semua', [DashboardController::class, 'allItems'])->name('barang.semua');
    Route::get('/barang/dipinjam', [DashboardController::class, 'borrowedItems'])->name('barang.dipinjam');
    // Admin reports: history peminjaman
    Route::get('/admin/laporan/peminjaman', [DashboardController::class, 'reportPeminjaman'])->name('admin.laporan.peminjaman');
    Route::get('/admin/laporan/ruangan', [DashboardController::class, 'reportRuangan'])->name('admin.laporan.ruangan');
});
