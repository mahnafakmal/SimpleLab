<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\EquipmentReturnController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\RfidManagementController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\BorrowHistoryController;
use App\Http\Controllers\ReportController;
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
    Route::get('/admin/laporan/peminjaman/export-excel', [DashboardController::class, 'exportPeminjamanExcel'])->name('admin.laporan.peminjaman.export-excel');
    Route::get('/admin/laporan/registrasi', [DashboardController::class, 'reportRegistrasi'])->name('admin.laporan.registrasi');
    Route::get('/admin/laporan/registrasi/export-excel', [DashboardController::class, 'exportRegistrasiExcel'])->name('admin.laporan.registrasi.export-excel');
    // Admin room reports removed

    // Laporan Kerusakan
    Route::post('/laporan-kerusakan', [LaporanKerusakanController::class, 'store'])->name('laporan.kerusakan.store');
    Route::post('/admin/laporan-kerusakan/{id}/status', [LaporanKerusakanController::class, 'updateStatus'])->name('admin.laporan.kerusakan.status');

    // Equipment Return Routes (RFID Scanner)
    Route::get('/equipment/return', [EquipmentReturnController::class, 'showReturnForm'])->name('equipment.return');
    Route::post('/equipment/return/scan', [EquipmentReturnController::class, 'processScan'])->name('equipment.return.scan');
    Route::get('/equipment/active-loans', [EquipmentReturnController::class, 'getActiveLoans'])->name('equipment.active-loans');
    Route::post('/equipment/{loan}/damage', [EquipmentReturnController::class, 'reportDamage'])->name('equipment.report-damage');

    // Laboratory Schedule Routes
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/api/schedule', [ScheduleController::class, 'getSchedules'])->name('schedule.api');
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::put('/schedule/{id}', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::delete('/schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');

    // Statistics & Charts Routes
    Route::get('/api/statistics/borrowing-frequency', [StatisticsController::class, 'getBorrowingFrequency'])->name('statistics.frequency');
    Route::get('/api/statistics/equipment-status', [StatisticsController::class, 'getEquipmentStatus'])->name('statistics.status');
    Route::get('/api/statistics/trends', [StatisticsController::class, 'getBorrowingTrends'])->name('statistics.trends');
    Route::get('/api/statistics/categories', [StatisticsController::class, 'getCategoryDistribution'])->name('statistics.categories');
    Route::get('/api/statistics/user', [StatisticsController::class, 'getUserStatistics'])->name('statistics.user');
    // Lightweight JSON endpoint for admin: list users (used by dashboard modal)
    Route::get('/api/admin/users', function () {
        return response()->json(
            App\Models\User::select('id','name','email','role')->orderBy('name')->get()
        );
    })->name('api.admin.users');

    // Lightweight JSON endpoint: list registered RFID tags for admin dashboard modal
    Route::get('/api/admin/rfid-tags', function () {
        $tags = App\Models\TagRfid::with('barang:id,name,status')->orderBy('id','desc')->get()->map(function($t){
            return [
                'id' => $t->id,
                'uid' => $t->uid,
                'barang' => $t->barang->name ?? null,
                'status' => $t->barang->status ?? 'unknown'
            ];
        });
        return response()->json($tags);
    })->name('api.admin.rfid.tags');
    Route::get('/api/statistics/top-items', [StatisticsController::class, 'getTopBorrowedItems'])->name('statistics.top-items');
    Route::get('/api/statistics/condition', [StatisticsController::class, 'getConditionReport'])->name('statistics.condition');
    Route::get('/api/statistics/dashboard', [StatisticsController::class, 'getDashboardStats'])->name('statistics.dashboard');

    // RFID Management Routes (Admin only)
    Route::prefix('admin/rfid')->middleware('adminonly')->group(function () {
        Route::get('/', [RfidManagementController::class, 'index'])->name('rfid.index');
        Route::get('/register-user', [RfidManagementController::class, 'showUserRegistration'])->name('rfid.register-user');
        Route::post('/register-user', [RfidManagementController::class, 'registerUserRfid'])->name('rfid.store-user');
        Route::get('/register-equipment', [RfidManagementController::class, 'showEquipmentRegistration'])->name('rfid.register-equipment');
        Route::post('/register-equipment', [RfidManagementController::class, 'registerEquipmentTag'])->name('rfid.store-equipment');
        Route::post('/{id}/activate', [RfidManagementController::class, 'activate'])->name('rfid.activate');
        Route::post('/{id}/deactivate', [RfidManagementController::class, 'deactivate'])->name('rfid.deactivate');
        Route::delete('/{id}', [RfidManagementController::class, 'destroy'])->name('rfid.destroy');
    });

    // RFID Validation API
    Route::post('/api/rfid/validate', [RfidManagementController::class, 'validateRfidUid'])->name('api.rfid.validate');

    // Inventory Management Routes (Admin only)
    Route::prefix('admin/inventory')->middleware('adminonly')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/create', [InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/', [InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/{barang}', [InventoryController::class, 'show'])->name('inventory.show');
        Route::get('/{barang}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
        Route::put('/{barang}', [InventoryController::class, 'update'])->name('inventory.update');
        Route::delete('/{barang}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::post('/bulk-status', [InventoryController::class, 'bulkUpdateStatus'])->name('inventory.bulk-status');
        Route::get('/export/csv', [InventoryController::class, 'exportCsv'])->name('inventory.export-csv');
        Route::get('/api/stats', [InventoryController::class, 'stats'])->name('inventory.stats');
    });

    // Borrow History Routes
    Route::prefix('history')->group(function () {
        Route::get('/', [BorrowHistoryController::class, 'index'])->name('history.index');
        Route::get('/{peminjaman}', [BorrowHistoryController::class, 'show'])->name('history.show');
        Route::post('/{peminjaman}/damage', [BorrowHistoryController::class, 'reportDamage'])->name('history.report-damage');
        Route::get('/export/pdf', [BorrowHistoryController::class, 'exportPdf'])->name('history.export-pdf');
        Route::get('/overdue', [BorrowHistoryController::class, 'overdue'])->name('history.overdue');
    });

    // Damage Reports (Admin only)
    Route::prefix('admin/damage-reports')->middleware('adminonly')->group(function () {
        Route::get('/', [BorrowHistoryController::class, 'damageReports'])->name('damage-reports.index');
        Route::put('/{laporan}', [BorrowHistoryController::class, 'updateDamageStatus'])->name('damage-reports.update');
        Route::get('/export/pdf', [BorrowHistoryController::class, 'exportDamageReportsPdf'])->name('damage-reports.export-pdf');
    });

    // Reports Routes (Admin only)
    Route::prefix('admin/reports')->middleware('adminonly')->group(function () {
        Route::get('/circulation', [ReportController::class, 'circulation'])->name('reports.circulation');
        Route::get('/circulation/export-pdf', [ReportController::class, 'exportCirculationPdf'])->name('reports.circulation-pdf');
        Route::get('/circulation/export-excel', [ReportController::class, 'exportCirculationExcel'])->name('reports.circulation-excel');
        Route::get('/equipment-frequency', [ReportController::class, 'equipmentFrequency'])->name('reports.frequency');
        Route::get('/damage', [ReportController::class, 'damageReport'])->name('reports.damage');
        Route::get('/damage/export-pdf', [ReportController::class, 'exportDamagePdf'])->name('reports.damage-pdf');
        Route::get('/dashboard-summary', [ReportController::class, 'dashboardSummary'])->name('reports.dashboard-summary');
    });
});

    // (demo routes removed)
