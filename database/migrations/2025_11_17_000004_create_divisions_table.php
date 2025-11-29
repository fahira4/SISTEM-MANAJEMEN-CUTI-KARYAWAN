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
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            
            // Nama Divisi (Harus Unik, misal: IT, HRD, Finance)
            $table->string('name')->unique();
            
            // Deskripsi Divisi (Boleh kosong)
            $table->text('description')->nullable();
            
            // Relasi ke tabel 'users' untuk Ketua Divisi.
            // PENTING: Karena tabel 'users' sudah dibuat di migrasi urutan 000000,
            // kita BISA langsung menggunakan constrained('users') di sini.
            $table->foreignId('leader_id')
                ->nullable()
                ->unique() // Satu user hanya bisa menjadi ketua di SATU divisi saja
                ->constrained('users')
                ->onDelete('set null'); // Jika user ketua dihapus, jabatan ketua kosong (NULL)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};