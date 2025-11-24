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

// Route untuk pengajuan cuti
Route::resource('leave-applications', LeaveApplicationController::class)
    ->middleware(['auth']);

// Route untuk verifikasi cuti
Route::get('/leave-verifications', [LeaveApplicationController::class, 'showVerificationList'])
    ->middleware(['auth'])
    ->name('leave-verifications.index');

// Route untuk admin management users
Route::resource('admin/users', UserController::class)
    ->middleware(['auth', 'admin']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== LEAVE APPLICATION ROUTES ====================
Route::middleware('auth')->prefix('leave-applications')->name('leave-applications.')->group(function () {
    Route::get('/', [LeaveApplicationController::class, 'index'])->name('index');
    Route::get('/create', [LeaveApplicationController::class, 'create'])->name('create');
    Route::post('/', [LeaveApplicationController::class, 'store'])->name('store');
    Route::get('/{leaveApplication}', [LeaveApplicationController::class, 'show'])->name('show');
    Route::post('/{application}/cancel', [LeaveApplicationController::class, 'cancelLeave'])->name('cancel');
    
    // ==================== PDF ROUTES ====================
    Route::get('/{leaveApplication}/download-letter', 
        [LeavePdfController::class, 'generateLeaveLetter'])
        ->name('download-letter');

    Route::get('/{leaveApplication}/view-letter', 
        [LeavePdfController::class, 'generateLeaveLetterView'])
        ->name('view-letter');

    Route::get('/{leaveApplication}/check-letter-availability', 
        [LeavePdfController::class, 'checkAvailability'])
        ->name('check-letter-availability');
});

// ==================== LEAVE VERIFICATION ROUTES ====================
Route::middleware('auth')->prefix('leave-verifications')->name('leave-verifications.')->group(function () {
    Route::get('/', [LeaveApplicationController::class, 'showVerificationList'])->name('index');
    Route::get('/{application}', [LeaveApplicationController::class, 'showVerificationDetail'])->name('show');
    
    // SINGLE APPROVAL/REJECTION
    Route::post('/{application}/approve', [LeaveApplicationController::class, 'approveLeave'])->name('approve');
    Route::post('/{application}/reject', [LeaveApplicationController::class, 'rejectLeave'])->name('reject');
    
    // ✅ BULK/MULTIPLE APPROVAL/REJECTION (NEW)
    Route::post('/bulk-action', [LeaveApplicationController::class, 'bulkApproveReject'])
        ->name('bulk-action');
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