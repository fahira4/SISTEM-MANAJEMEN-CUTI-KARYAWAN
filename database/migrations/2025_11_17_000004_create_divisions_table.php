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
        // di 2025_11_17_000004_create_divisions_table.php
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            
            // PERBAIKI: Tambah onDelete
            $table->foreignId('leader_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null'); // Jika user dihapus, set leader_id jadi null
            
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
