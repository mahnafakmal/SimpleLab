<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RfidController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/barang/register', [RfidController::class, 'registerBarang']);
    Route::post('/rfid/card/register', [RfidController::class, 'registerUserCard']);
    Route::post('/rfid/authenticate', [RfidController::class, 'authenticateUser']);
    Route::post('/rfid/track', [RfidController::class, 'trackAsset']);
    Route::post('/peminjaman/borrow', [RfidController::class, 'borrowAsset']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
