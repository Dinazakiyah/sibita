<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Mahasiswa\MahasiswaBimbinganController;
use App\Http\Controllers\Mahasiswa\MahasiswaController;
use App\Http\Controllers\Dosen\DosenBimbinganController;
use App\Http\Controllers\Dosen\DosenController;
use App\Http\Controllers\Admin\AdminController;



// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
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
    // Quick Menu
    Route::get('/menu', [\App\Http\Controllers\MenuController::class, 'index'])->name('menu');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password/edit', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Mahasiswa Management
    Route::get('/mahasiswa', [AdminController::class, 'indexMahasiswa'])->name('mahasiswa.index');
    Route::get('/mahasiswa/create', [AdminController::class, 'createMahasiswa'])->name('mahasiswa.create');
    Route::post('/mahasiswa', [AdminController::class, 'storeMahasiswa'])->name('mahasiswa.store');
    Route::get('/mahasiswa/{mahasiswa}', [AdminController::class, 'showMahasiswa'])->name('mahasiswa.show');

    // Dosen Management
    Route::get('/dosen', [AdminController::class, 'indexDosen'])->name('dosen.index');
    Route::get('/dosen/create', [AdminController::class, 'createDosen'])->name('dosen.create');
    Route::post('/dosen', [AdminController::class, 'storeDosen'])->name('dosen.store');
    Route::get('/dosen/{dosen}', [AdminController::class, 'showDosen'])->name('dosen.show');

    // Schedule Periods
    Route::get('/periods', [AdminController::class, 'periods'])->name('periods');
    Route::get('/periods/create', [AdminController::class, 'createPeriod'])->name('periods.create');
    Route::post('/periods', [AdminController::class, 'storePeriod'])->name('periods.store');

    // Laporan & Reports
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});

// Dosen Routes
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dashboard');

    // Mahasiswa Bimbingan
    Route::get('/mahasiswa', [DosenController::class, 'mahasiswa'])->name('mahasiswa.index');
    Route::get('/mahasiswa/{mahasiswa}', [DosenController::class, 'showMahasiswa'])->name('mahasiswa.show');

    // Bimbingan Detail
    Route::get('/bimbingan/{bimbingan}', [DosenController::class, 'showBimbingan'])->name('bimbingan.show');
    Route::put('/bimbingan/{bimbingan}/status', [DosenController::class, 'updateBimbinganStatus'])->name('bimbingan.update-status');

    // Submission Review
    Route::get('/submissions/{submission}/review', [DosenController::class, 'reviewSubmission'])->name('submissions.review');
    Route::post('/submissions/{submission}/comment', [DosenController::class, 'addComment'])->name('submissions.comment');
    Route::put('/submissions/{submission}/approve', [DosenController::class, 'approveSubmission'])->name('submissions.approve');
    Route::put('/submissions/{submission}/reject', [DosenController::class, 'rejectSubmission'])->name('submissions.reject');

    // History
    Route::get('/history', [DosenController::class, 'history'])->name('history');

    // Legacy routes for compatibility
    Route::get('/mahasiswa-compat/{id}', [DosenBimbinganController::class, 'showMahasiswa'])->name('mahasiswa.compat');
    Route::get('/bimbingan/{id}/review-compat', [DosenBimbinganController::class, 'reviewBimbingan'])->name('bimbingan.review');
    Route::post('/bimbingan/{id}/review-compat', [DosenBimbinganController::class, 'submitReview'])->name('bimbingan.submit-review');
    Route::post('/mahasiswa/{id}/approve-sempro-compat', [DosenBimbinganController::class, 'approveLayakSempro'])->name('approve.sempro');
    Route::post('/mahasiswa/{id}/approve-sidang-compat', [DosenBimbinganController::class, 'approveLayakSidang'])->name('approve.sidang');

    // New routes for bimbingan review and comments
    Route::get('/bimbingan/{id}/review', [DosenBimbinganController::class, 'reviewBimbingan'])->name('bimbingan.review-new');
    Route::post('/bimbingan/comment-submission/{submissionId}', [DosenBimbinganController::class, 'commentOnSubmission'])->name('dosen.bimbingan.comment-submission');

    // Appointment/Scheduling Routes
    Route::get('/appointments', [DosenBimbinganController::class, 'appointmentsIndex'])->name('appointments.index');
    Route::post('/appointments/{id}/approve', [DosenBimbinganController::class, 'approveAppointment'])->name('appointments.approve');
    Route::post('/appointments/{id}/reject', [DosenBimbinganController::class, 'rejectAppointment'])->name('appointments.reject');
    Route::get('/schedule', [DosenBimbinganController::class, 'mySchedule'])->name('schedule.my');
});

// Mahasiswa Routes
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');

    // Bimbingan
    Route::get('/bimbingan', [MahasiswaController::class, 'bimbingan'])->name('bimbingan.index');
    Route::get('/bimbingan/{bimbingan}', [MahasiswaController::class, 'showBimbingan'])->name('bimbingan.show');

    // File Upload
    Route::get('/bimbingan/{bimbingan}/upload', [MahasiswaController::class, 'uploadForm'])->name('uploads.create');
    Route::post('/bimbingan/{bimbingan}/upload', [MahasiswaController::class, 'storeUpload'])->name('uploads.store');

    // Submissions & Comments
    Route::get('/submissions/{submission}', [MahasiswaController::class, 'showSubmission'])->name('submissions.show');

    // Progress & Archive
    Route::get('/progress', [MahasiswaController::class, 'progress'])->name('progress');
    Route::get('/bimbingan/{bimbingan}/archive/download', [MahasiswaController::class, 'downloadArchive'])->name('archive.download');

    // Legacy routes for compatibility
    Route::get('/bimbingan-compat/create', [MahasiswaBimbinganController::class, 'create'])->name('bimbingan.create');
    Route::post('/bimbingan-compat', [MahasiswaBimbinganController::class, 'store'])->name('bimbingan.store');
    Route::get('/bimbingan-compat/{id}', [MahasiswaBimbinganController::class, 'show'])->name('bimbingan.compat');
    Route::get('/bimbingan-compat/{id}/download', [MahasiswaBimbinganController::class, 'download'])->name('bimbingan.download');
    Route::get('/riwayat-compat/export', [MahasiswaBimbinganController::class, 'exportHistory'])->name('riwayat.export');

    // Appointment/Scheduling Routes
    Route::get('/appointments', [MahasiswaBimbinganController::class, 'appointmentsIndex'])->name('appointments.index');
    Route::post('/appointments/book', [MahasiswaBimbinganController::class, 'bookAppointment'])->name('appointments.book');
    Route::get('/appointments/my', [MahasiswaBimbinganController::class, 'myAppointments'])->name('appointments.my');
    Route::post('/appointments/{id}/cancel', [MahasiswaBimbinganController::class, 'cancelAppointment'])->name('appointments.cancel');
});

// Schedule Periods
Route::get('/periods', [AdminController::class, 'periods'])->name('periods');
Route::get('/periods/create', [AdminController::class, 'createPeriod'])->name('periods.create');
Route::post('/periods', [AdminController::class, 'storePeriod'])->name('periods.store');

// Tambahan route untuk edit, update, delete
Route::get('/periods/{period}/edit', [AdminController::class, 'editPeriod'])->name('periods.edit');
Route::put('/periods/{period}', [AdminController::class, 'updatePeriod'])->name('periods.update');
Route::delete('/periods/{period}', [AdminController::class, 'deletePeriod'])->name('periods.delete');


Route::middleware(['auth', 'role:mahasiswa'])->group(function () {

    // Form upload bimbingan baru
    Route::get('/Mahasiswa/Bimbingan/upload',
        [MahasiswaBimbinganController::class, 'create'])
        ->name('mahasiswa.bimbingan.upload');

    // Store file
    Route::post('/Mahasiswa/Bimbingan/store',
        [MahasiswaBimbinganController::class, 'store'])
        ->name('Mahasiswa.Bimbingan.store');

});


Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/bimbingan/upload', [MahasiswaBimbinganController::class, 'create'])
        ->name('mahasiswa.bimbingan.create');

    Route::post('/mahasiswa/bimbingan/upload', [MahasiswaBimbinganController::class, 'store'])
        ->name('mahasiswa.bimbingan.store');
});



