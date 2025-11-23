<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Index untuk pencarian cepat
            $table->index(['role', 'division_id']);
            $table->index('active_status');
            $table->index('join_date');
        });

        Schema::table('divisions', function (Blueprint $table) {
            $table->index('leader_id');
        });

        Schema::table('leave_applications', function (Blueprint $table) {
            // Index untuk query yang sering digunakan
            $table->index(['user_id', 'status']);
            $table->index('leave_type');
            $table->index('start_date');
            $table->index('end_date');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'division_id']);
            $table->dropIndex(['active_status']);
            $table->dropIndex(['join_date']);
        });

        Schema::table('divisions', function (Blueprint $table) {
            $table->dropIndex(['leader_id']);
        });

        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['leave_type']);
            $table->dropIndex(['start_date']);
            $table->dropIndex(['end_date']);
            $table->dropIndex(['status', 'created_at']);
        });
    }
};