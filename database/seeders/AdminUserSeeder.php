<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'Directie',
                'email_verified_at' => now(),
            ]
        );

        // Create worker user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'worker@example.com'],
            [
                'name' => 'Worker User',
                'password' => Hash::make('password'),
                'role' => 'Magazijnmedewerker',
                'email_verified_at' => now(),
            ]
        );

        // Create regular user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => 'Vrijwilliger',
                'email_verified_at' => now(),
            ]
        );
    }
}
