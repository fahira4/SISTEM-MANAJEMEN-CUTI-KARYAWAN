<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    
    public function run(): void
    {
        $existingAdmin = User::where('role', 'admin')->first();
        
        if ($existingAdmin) {
            $this->command->info('Admin user already exists. Skipping...');
            return;
        }

        User::create([
            'username' => 'superadmin',
            'name' => 'Super Administrator',
            'email' => 'admin@company.com',
            'password' => Hash::make('password123'), 
            'role' => 'admin',
            'division_id' => null, 
            'annual_leave_quota' => 0, 
            'phone_number' => '081234567890',
            'address' => 'Jl. Contoh Alamat No. 123',
            'join_date' => Carbon::now(),
            'active_status' => true,
            'email_verified_at' => Carbon::now(),
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@company.com');
        $this->command->info('Password: password123');
    }
}