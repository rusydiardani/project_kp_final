<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ServiceRequest;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@disdukcapil.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Petugas Loket 1',
            'email' => 'petugas1@disdukcapil.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        // 3. Create Dummy Service Requests
        ServiceRequest::create([
            'registration_number' => 'REG-20230101-001',
            'user_id' => 2, // Petugas 1
            'applicant_name' => 'Budi Santoso',
            'nik' => '1234567890123456',
            'submission_date' => now()->subDays(1),
            'deadline_date' => now()->addDays(2),
            'status' => 'pending',
            'notes' => 'Menunggu blanko',
        ]);
    }
}
