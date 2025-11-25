<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Urutan kode di sini menentukan urutan kolom di database
            $table->string('name');
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            
            $table->enum('role', ['admin', 'karyawan', 'ketua_divisi', 'hrd'])->default('karyawan');
            
            // Note: Pastikan tabel 'divisions' SUDAH ADA sebelum migrasi ini dijalankan.
            // Jika error, hapus "->constrained('divisions')" sementara.
            $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete();
            
            $table->integer('annual_leave_quota')->default(12);
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->date('join_date')->nullable();
            $table->boolean('active_status')->default(true);
            $table->enum('status', ['pending', 'active', 'rejected'])->default('active');
            
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::enableForeignKeyConstraints();
    }
};