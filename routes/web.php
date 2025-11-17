<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LeaveApplicationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// RUTE KHUSUS ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('divisions', DivisionController::class);
        Route::get('divisions/{division}/members', [DivisionController::class, 'showMembers'])
                ->name('divisions.members.show');
        Route::post('divisions/{division}/members', [DivisionController::class, 'addMember'])
                ->name('divisions.members.add');
        Route::resource('users', UserController::class);

    });
});

// RUTE UNTUK KARYAWAN & KETUA DIVISI
// Menggunakan middleware 'auth' (harus login)
Route::middleware('auth')->group(function () {

    // Rute untuk menampilkan form pengajuan cuti
    Route::get('leave-applications/create', [LeaveApplicationController::class, 'create'])
         ->name('leave-applications.create');

    // Rute untuk menyimpan data form pengajuan cuti
    Route::post('leave-applications', [LeaveApplicationController::class, 'store'])
         ->name('leave-applications.store');
    Route::get('my-leave-applications', [LeaveApplicationController::class, 'index'])
         ->name('leave-applications.index');

    // Nanti rute riwayat cuti, dll. bisa ditambahkan di sini
});

// RUTE UNTUK ATASAN (KETUA DIVISI & HRD)
Route::middleware(['auth', 'role:ketua_divisi,hrd'])->group(function () {
    
    // Halaman utama verifikasi (daftar cuti pending)
    Route::get('leave-verifications', [LeaveApplicationController::class, 'showVerificationList'])
         ->name('leave-verifications.index');
    // Halaman Detail Verifikasi
    Route::get('leave-verifications/{application}', [LeaveApplicationController::class, 'showVerificationDetail'])
         ->name('leave-verifications.show');

    // Aksi Approve (Setujui)
    Route::post('leave-verifications/{application}/approve', [LeaveApplicationController::class, 'approveLeave'])
         ->name('leave-verifications.approve');

    // Aksi Reject (Tolak)
    Route::post('leave-verifications/{application}/reject', [LeaveApplicationController::class, 'rejectLeave'])
         ->name('leave-verifications.reject');

    // Nanti kita tambahkan rute approve/reject di sini
});


require __DIR__.'/auth.php';
