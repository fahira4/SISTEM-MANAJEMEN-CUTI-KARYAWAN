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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Users Management
    Route::resource('users', UserController::class);
    
    // Divisions Management
    Route::resource('divisions', DivisionController::class);
    
    // Division Members Management
    Route::get('divisions/{division}/members', [DivisionController::class, 'showMembers'])
         ->name('divisions.members.show');
    Route::post('divisions/{division}/members', [DivisionController::class, 'addMember'])
         ->name('divisions.members.add');
    Route::delete('divisions/{division}/members/{user}', [DivisionController::class, 'removeMember'])
         ->name('divisions.members.remove');
});

// RUTE UNTUK KARYAWAN & KETUA DIVISI
Route::middleware('auth')->group(function () {
    // Leave Applications for all authenticated users
    Route::get('leave-applications/create', [LeaveApplicationController::class, 'create'])
         ->name('leave-applications.create');
    Route::post('leave-applications', [LeaveApplicationController::class, 'store'])
         ->name('leave-applications.store');
    Route::get('my-leave-applications', [LeaveApplicationController::class, 'index'])
         ->name('leave-applications.index');
    Route::post('leave-applications/{application}/cancel', [LeaveApplicationController::class, 'cancelLeave'])
         ->name('leave-applications.cancel');
});

// RUTE UNTUK ATASAN (KETUA DIVISI, HRD, DAN ADMIN)
Route::middleware(['auth'])->group(function () { // HAPUS 'checkrole' sementara
    
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
});

// Temporary debug route
Route::get('/debug-leave', function () {
    $user = Auth::user();
    
    echo "User: " . $user->name . " (" . $user->role . ")<br>";
    echo "Division ID: " . $user->division_id . "<br><br>";
    
    // Cek semua pengajuan pending
    $allPending = \App\Models\LeaveApplication::with('applicant')
        ->where('status', 'pending')
        ->get();
        
    echo "All Pending Applications:<br>";
    foreach ($allPending as $app) {
        echo "- ID: " . $app->id . ", Applicant: " . $app->applicant->name . 
             ", Role: " . $app->applicant->role . ", Division: " . ($app->applicant->division_id ?? 'NULL') . "<br>";
    }
    
    // Cek anggota divisi ketua
    if ($user->role == 'ketua_divisi' && $user->division_id) {
        $teamMembers = \App\Models\User::where('division_id', $user->division_id)
                                      ->where('id', '!=', $user->id)
                                      ->get();
        
        echo "<br>Team Members:<br>";
        foreach ($teamMembers as $member) {
            echo "- " . $member->name . " (ID: " . $member->id . ")<br>";
        }
        
        $teamMemberIds = $teamMembers->pluck('id');
        $divisiPending = \App\Models\LeaveApplication::whereIn('user_id', $teamMemberIds)
                                                   ->where('status', 'pending')
                                                   ->get();
        
        echo "<br>Divisi Pending Applications:<br>";
        foreach ($divisiPending as $app) {
            echo "- ID: " . $app->id . ", Applicant: " . $app->applicant->name . "<br>";
        }
    }
});

// PDF Routes
Route::middleware('auth')->group(function () {
    // Download PDF surat cuti
    Route::get('/leave-applications/{leaveApplication}/download-pdf', 
               [App\Http\Controllers\LeavePdfController::class, 'generateLeaveLetter'])
         ->name('leave-applications.download-pdf');
    
    // Preview PDF di browser
    Route::get('/leave-applications/{leaveApplication}/preview-pdf', 
               [App\Http\Controllers\LeavePdfController::class, 'generateLeaveLetterView'])
         ->name('leave-applications.preview-pdf');
    
    // Download draft PDF (untuk cuti yang belum approved)
    Route::get('/leave-applications/{leaveApplication}/download-draft', 
               [App\Http\Controllers\LeavePdfController::class, 'generateDraftLeaveLetter'])
         ->name('leave-applications.download-draft');
});

require __DIR__.'/auth.php';