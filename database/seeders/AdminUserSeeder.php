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
        // Create Directie (Admin) user
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin Test Account',
                'password' => Hash::make('password123'),
                'role' => 'Directie',
                'email_verified_at' => now(),
            ]
        );

        // Create Magazijnmedewerker (Worker) user
        User::updateOrCreate(
            ['email' => 'worker@test.com'],
            [
                'name' => 'Worker Test Account',
                'password' => Hash::make('password123'),
                'role' => 'Magazijnmedewerker',
                'email_verified_at' => now(),
            ]
        );

        // Create Vrijwilliger (Volunteer/User) user
        User::updateOrCreate(
            ['email' => 'volunteer@test.com'],
            [
                'name' => 'Volunteer Test Account',
                'password' => Hash::make('password123'),
                'role' => 'Vrijwilliger',
                'email_verified_at' => now(),
            ]
        );
    }
}
