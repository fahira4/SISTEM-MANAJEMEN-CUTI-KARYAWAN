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
        Schema::table('users', function (Blueprint $table) {
            // Tambah username (unique)
            $table->string('username')->unique()->after('id');
            
            // Tambah join_date (tanggal bergabung)
            $table->date('join_date')->after('role');
            
            // Tambah active_status (status aktif/tidak aktif)
            $table->boolean('active_status')->default(true)->after('join_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'join_date', 'active_status']);
        });
    }
};