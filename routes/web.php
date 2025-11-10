<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Mahasiswa\MahasiswaBimbinganController;
use App\Http\Controllers\Dosen\DosenBimbinganController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout (Authenticated Only)
Route::post('/logout', [AuthController::class, 'logout'])
     ->name('logout')
     ->middleware('auth');

// Dashboard (Authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Mahasiswa Management
    Route::get('/mahasiswa', [AdminController::class, 'indexMahasiswa'])->name('mahasiswa.index');
    Route::get('/mahasiswa/create', [AdminController::class, 'createMahasiswa'])->name('mahasiswa.create');
    Route::post('/mahasiswa', [AdminController::class, 'storeMahasiswa'])->name('mahasiswa.store');

    // Dosen Management
    Route::get('/dosen', [AdminController::class, 'indexDosen'])->name('dosen.index');
    Route::get('/dosen/create', [AdminController::class, 'createDosen'])->name('dosen.create');
    Route::post('/dosen', [AdminController::class, 'storeDosen'])->name('dosen.store');

    // Laporan
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
});

// Dosen Routes
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    // Mahasiswa Bimbingan
    Route::get('/mahasiswa', [DosenBimbinganController::class, 'index'])->name('mahasiswa.index');
    Route::get('/mahasiswa/{id}', [DosenBimbinganController::class, 'showMahasiswa'])->name('mahasiswa.show');

    // Review Bimbingan
    Route::get('/bimbingan/{id}/review', [DosenBimbinganController::class, 'reviewBimbingan'])->name('bimbingan.review');
    Route::post('/bimbingan/{id}/review', [DosenBimbinganController::class, 'submitReview'])->name('bimbingan.submit-review');

    // Approve Status
    Route::post('/mahasiswa/{id}/approve-sempro', [DosenBimbinganController::class, 'approveLayakSempro'])->name('approve.sempro');
    Route::post('/mahasiswa/{id}/approve-sidang', [DosenBimbinganController::class, 'approveLayakSidang'])->name('approve.sidang');
});

// Mahasiswa Routes
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Bimbingan
    Route::get('/bimbingan/create', [MahasiswaBimbinganController::class, 'create'])->name('bimbingan.create');
    Route::post('/bimbingan', [MahasiswaBimbinganController::class, 'store'])->name('bimbingan.store');
    Route::get('/bimbingan/{id}', [MahasiswaBimbinganController::class, 'show'])->name('bimbingan.show');
    Route::get('/bimbingan/{id}/download', [MahasiswaBimbinganController::class, 'download'])->name('bimbingan.download');

    // Export History
    Route::get('/riwayat/export', [MahasiswaBimbinganController::class, 'exportHistory'])->name('riwayat.export');
});
