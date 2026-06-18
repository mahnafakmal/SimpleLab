<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPeminjamanController;
use App\Http\Controllers\LaporanKerusakanController;
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
    Route::post('/web/peminjaman/alat', [PeminjamanController::class, 'borrowAlat'])->name('web.peminjaman.alat');
    Route::post('/web/peminjaman/alat/dosen', [PeminjamanController::class, 'borrowAlatDosen'])->name('web.peminjaman.alat.dosen');
    Route::post('/web/pengembalian/alat/{id}', [PeminjamanController::class, 'returnAlat'])->name('web.pengembalian.alat');
    // Room booking routes removed (feature disabled)

    // Admin loan and room status routes
    Route::post('/admin/loan/status/{id}', [AdminPeminjamanController::class, 'updateLoanStatus'])->name('admin.loan.status');
    // Admin room status route removed (booking feature disabled)

    // Admin: create dosen account
    Route::post('/admin/users/dosen', [AuthController::class, 'createDosenByAdmin'])->name('admin.users.dosen.create');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // List available items for users/admin
    Route::get('/barang/tersedia', [DashboardController::class, 'availableItems'])->name('barang.tersedia');
    Route::get('/barang/semua', [DashboardController::class, 'allItems'])->name('barang.semua');
    Route::get('/barang/dipinjam', [DashboardController::class, 'borrowedItems'])->name('barang.dipinjam');
    // Admin reports: history peminjaman
    Route::get('/admin/laporan/peminjaman', [DashboardController::class, 'reportPeminjaman'])->name('admin.laporan.peminjaman');
    Route::get('/admin/laporan/registrasi', [DashboardController::class, 'reportRegistrasi'])->name('admin.laporan.registrasi');
    // Admin room reports removed

    // Laporan Kerusakan
    Route::post('/laporan-kerusakan', [LaporanKerusakanController::class, 'store'])->name('laporan.kerusakan.store');
    Route::post('/admin/laporan-kerusakan/{id}/status', [LaporanKerusakanController::class, 'updateStatus'])->name('admin.laporan.kerusakan.status');
});

    // (demo routes removed)
