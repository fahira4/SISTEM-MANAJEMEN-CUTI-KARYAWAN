<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ✅ UBAH DARI ENUM KE VARCHAR
        Schema::table('holidays', function (Blueprint $table) {
            $table->string('type', 20)->default('national')->change();
        });
    }

    public function down()
    {
        // ✅ KEMBALIKAN KE ENUM (jika perlu rollback)
        Schema::table('holidays', function (Blueprint $table) {
            $table->enum('type', ['national', 'company', 'joint_leave'])->default('national')->change();
        });
    }
};