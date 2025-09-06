<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@eduverse.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create instructor user
        User::create([
            'name' => 'Instructor User', 
            'email' => 'instructor@eduverse.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        // Create student user
        User::create([
            'name' => 'Student User',
            'email' => 'student@eduverse.com', 
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);
    }
}