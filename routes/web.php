<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeavePdfController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\LeaveVerificationController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('leave-applications')->name('leave-applications.')->group(function () {
    Route::get('/', [LeaveApplicationController::class, 'index'])->name('index');
    Route::get('/create', [LeaveApplicationController::class, 'create'])->name('create');
    Route::post('/', [LeaveApplicationController::class, 'store'])->name('store');
    Route::get('/{leaveApplication}', [LeaveApplicationController::class, 'show'])->name('show');
    Route::delete('/{leaveApplication}', [LeaveApplicationController::class, 'destroy'])->name('destroy');
    Route::post('/{application}/cancel', [LeaveApplicationController::class, 'cancelLeave'])->name('cancel');
});

Route::middleware('auth')->prefix('leave-applications')->name('leave-applications.')->group(function () {
    Route::get('/{leaveApplication}/download-letter', [LeavePdfController::class, 'generateLeaveLetter'])->name('download-letter');
    Route::get('/{leaveApplication}/view-letter', [LeavePdfController::class, 'generateLeaveLetterView'])->name('view-letter');
    Route::get('/{leaveApplication}/check-letter-availability', [LeavePdfController::class, 'checkAvailability'])->name('check-letter-availability');
});

Route::middleware('auth')->prefix('leave-verifications')->name('leave-verifications.')->group(function () {
    Route::get('/', [LeaveVerificationController::class, 'index'])->name('index');
    Route::get('/{application}', [LeaveVerificationController::class, 'showVerificationDetail'])->name('show');
    Route::post('/{application}/approve', [LeaveVerificationController::class, 'approveLeave'])->name('approve');
    Route::post('/{application}/reject', [LeaveVerificationController::class, 'rejectLeave'])->name('reject');
    Route::post('/bulk-action', [LeaveVerificationController::class, 'bulkApproveReject'])->name('bulk-action');
});

Route::middleware(['auth', 'role:ketua_divisi'])->prefix('division')->name('division.')->group(function () {
    Route::get('/leaves', [LeaveApplicationController::class, 'divisionLeaves'])->name('leaves');
});

Route::middleware(['auth', 'role:hrd'])->prefix('hrd')->name('hrd.')->group(function () {
    Route::get('/all-leaves', [LeaveApplicationController::class, 'allLeaves'])->name('all-leaves');
    Route::get('/division-leaves', [LeaveApplicationController::class, 'divisionLeaves'])->name('division-leaves');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::resource('users', UserController::class);
    
    Route::resource('divisions', DivisionController::class);
    Route::get('divisions/{division}/members', [DivisionController::class, 'showMembers'])->name('divisions.members.show');
    Route::post('divisions/{division}/members', [DivisionController::class, 'addMember'])->name('divisions.members.add');
    Route::delete('divisions/{division}/members/{user}', [DivisionController::class, 'removeMember'])->name('divisions.members.remove');
    
    Route::resource('holidays', HolidayController::class);
    Route::get('holidays/import', [HolidayController::class, 'showImportForm'])->name('holidays.import-form');
    Route::post('holidays/import', [HolidayController::class, 'importFromGoogleCalendar'])->name('holidays.import');
});

require __DIR__.'/auth.php';