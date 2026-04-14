<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'nama' => 'Admin User',
            'email' => 'admin@lms.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Create Teacher User
        User::create([
            'nama' => 'Guru Testing',
            'email' => 'guru@lms.test',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Create Student Users
        User::factory(5)->create([
            'role' => 'siswa',
        ]);
    }
}
