<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // 1. Identitas Dasar
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            
            // 2. Peran & Divisi
            $table->enum('role', ['admin', 'karyawan', 'ketua_divisi', 'hrd'])->default('karyawan');
            
            // Kolom division_id (Foreign Key diaktifkan nanti di file terpisah)
            $table->unsignedBigInteger('division_id')->nullable(); 
            
            // 3. Data Kepegawaian & Cuti
            $table->integer('annual_leave_quota')->default(12);
            $table->date('join_date')->nullable();
            $table->boolean('active_status')->default(true);
            $table->enum('status', ['pending', 'active', 'rejected'])->default('active');
            
            // 4. Profil Tambahan
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_photo_path')->nullable();

            // 5. Auth Laravel Standar
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // --- INDEXES ---
            // Letakkan Index di paling bawah sebelum tutup kurung
            $table->index(['role', 'division_id']); 
            $table->index('active_status');
            $table->index('join_date');
        });

        // Tabel standar Laravel lainnya
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};