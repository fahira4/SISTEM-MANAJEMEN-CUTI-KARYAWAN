<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeavePdfController;

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

// ==================== LEAVE APPLICATION ROUTES ====================
Route::middleware('auth')->prefix('leave-applications')->name('leave-applications.')->group(function () {
    Route::get('/create', [LeaveApplicationController::class, 'create'])->name('create');
    Route::post('/', [LeaveApplicationController::class, 'store'])->name('store');
    Route::get('/', [LeaveApplicationController::class, 'index'])->name('index');
    Route::get('/{leaveApplication}', [LeaveApplicationController::class, 'show'])->name('show');
    Route::post('/{application}/cancel', [LeaveApplicationController::class, 'cancelLeave'])->name('cancel');
    
    // ==================== PDF ROUTES (Pindahkan ke dalam group ini) ====================
    // Surat izin resmi (hanya untuk cuti yang disetujui HRD)
    Route::get('/{leaveApplication}/download-letter', 
        [LeavePdfController::class, 'generateLeaveLetter'])
        ->name('download-letter');

    Route::get('/{leaveApplication}/view-letter', 
        [LeavePdfController::class, 'generateLeaveLetterView'])
        ->name('view-letter');

    // Cek ketersediaan surat
    Route::get('/{leaveApplication}/check-letter-availability', 
        [LeavePdfController::class, 'checkAvailability'])
        ->name('check-letter-availability');
});

// ==================== LEAVE VERIFICATION ROUTES ====================
Route::middleware('auth')->prefix('leave-verifications')->name('leave-verifications.')->group(function () {
    Route::get('/', [LeaveApplicationController::class, 'showVerificationList'])->name('index');
    Route::get('/{application}', [LeaveApplicationController::class, 'showVerificationDetail'])->name('show');
    Route::post('/{application}/approve', [LeaveApplicationController::class, 'approveLeave'])->name('approve');
    Route::post('/{application}/reject', [LeaveApplicationController::class, 'rejectLeave'])->name('reject');
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Users Management
    Route::resource('users', UserController::class);
    
    // Divisions Management
    Route::resource('divisions', DivisionController::class);
    
    // Division Members Management
    Route::get('divisions/{division}/members', [DivisionController::class, 'showMembers'])->name('divisions.members.show');
    Route::post('divisions/{division}/members', [DivisionController::class, 'addMember'])->name('divisions.members.add');
    Route::delete('divisions/{division}/members/{user}', [DivisionController::class, 'removeMember'])->name('divisions.members.remove');
});

require __DIR__.'/auth.php';