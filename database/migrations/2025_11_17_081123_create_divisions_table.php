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
            $table->string('name')->unique(); // Nama Divisi, harus unik
            $table->text('description')->nullable(); // Deskripsi Divisi, boleh kosong

            // Kolom untuk Ketua Divisi
            // Kita set 'nullable' karena mungkin saja sebuah divisi belum punya ketua
            $table->foreignId('leader_id')->nullable()->constrained('users');

            $table->timestamps(); // otomatis membuat created_at dan updated_at
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
