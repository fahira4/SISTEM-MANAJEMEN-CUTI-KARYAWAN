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
            
            // 1. DATA PEMOHON
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // 2. DATA CUTI
            $table->enum('leave_type', ['tahunan', 'sakit']); // Jenis Cuti
            $table->date('start_date'); // Tanggal Mulai
            $table->date('end_date'); // Tanggal Selesai
            $table->integer('total_days'); // Total hari (dihitung otomatis)
            $table->text('reason'); // Alasan Cuti
            $table->string('attachment_path')->nullable(); // Lampiran Surat Dokter (Wajib jika sakit)
            $table->string('address_during_leave')->nullable(); // Alamat selama cuti
            $table->string('emergency_contact')->nullable(); // Nomor darurat

            // 3. ALUR PERSETUJUAN (STATUS MACHINE)
            $table->enum('status', [
                'pending', // Baru diajukan karyawan
                'approved_by_leader', // Disetujui Ketua Divisi
                'rejected_by_leader', // Ditolak Ketua Divisi
                'approved_by_hrd', // Disetujui HRD (Final)
                'rejected_by_hrd', // Ditolak HRD (Final)
                'cancelled' // Dibatalkan oleh Karyawan
            ])->default('pending');

            // 4. LOG PERSETUJUAN
            $table->foreignId('leader_approver_id')->nullable()->constrained('users');
            $table->timestamp('leader_approval_at')->nullable();
            $table->text('leader_rejection_notes')->nullable(); // Catatan jika ditolak leader

            $table->foreignId('hrd_approver_id')->nullable()->constrained('users');
            $table->timestamp('hrd_approval_at')->nullable();
            $table->text('hrd_rejection_notes')->nullable(); // Catatan jika ditolak HRD

            $table->text('cancellation_reason')->nullable(); // Alasan jika dibatalkan

            $table->timestamps(); // auto created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
