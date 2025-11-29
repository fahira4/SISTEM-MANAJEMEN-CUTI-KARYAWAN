<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            
            // --- 1. DATA UTAMA ---
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->enum('leave_type', ['tahunan', 'sakit']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->text('reason'); // Alasan cuti (Wajib dari karyawan)
            
            // File bukti (opsional/nullable)
            $table->string('attachment_path')->nullable();
            
            // Kontak darurat (opsional/nullable)
            $table->string('address_during_leave')->nullable();
            $table->string('emergency_contact')->nullable();

            // --- 2. STATUS ---
            $table->enum('status', [
                'pending',
                'approved_by_leader', 
                'rejected_by_leader',
                'approved_by_hrd',
                'rejected_by_hrd',
                'cancelled'
            ])->default('pending');

            // --- 3. APPROVAL LEADER (Ketua Divisi) ---
            $table->foreignId('leader_approver_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            
            $table->timestamp('leader_approval_at')->nullable();
            
            // Approval Notes: Nullable (Opsional sesuai request Anda)
            $table->text('leader_approval_note')->nullable(); 
            
            // Rejection Notes: Nullable di DB (karena kosong saat awal), 
            // tapi WAJIB diisi min 10 char lewat Validasi Controller saat aksi Reject.
            $table->text('leader_rejection_notes')->nullable();

            // --- 4. APPROVAL HRD ---
            $table->foreignId('hrd_approver_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamp('hrd_approval_at')->nullable();
            
            // Approval Notes: Nullable (Opsional)
            $table->text('hrd_approval_note')->nullable();
            
            // Rejection Notes: Nullable di DB, Wajib di Controller
            $table->text('hrd_rejection_notes')->nullable();

            // --- 5. PEMBATALAN ---
            // Cancel Reason: Nullable (sesuai request Anda)
            $table->text('cancellation_reason')->nullable();
            
            $table->timestamps();

            // --- INDEXES ---
            $table->index(['user_id', 'status']);
            $table->index('leave_type');
            $table->index('start_date');
            $table->index('end_date');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};