<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder admin terlebih dahulu
        $this->call([
            AdminUserSeeder::class,
            // Seeder lainnya bisa ditambahkan di sini
            // DivisionSeeder::class,
            // UserSeeder::class,
        ]);
    }
}