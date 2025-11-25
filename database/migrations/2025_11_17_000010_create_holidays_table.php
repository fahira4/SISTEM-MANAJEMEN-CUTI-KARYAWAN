<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama hari libur
            $table->date('date'); // Tanggal libur
            $table->enum('type', ['national', 'company', 'joint_leave']); // Jenis: nasional, perusahaan, cuti bersama
            $table->text('description')->nullable(); // Deskripsi
            $table->boolean('is_recurring')->default(false); // Berulang setiap tahun
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('holidays');
    }
};