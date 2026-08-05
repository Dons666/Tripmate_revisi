<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@tripmate.com'],
            [
                'username'  => 'Admin Tripmate',
                'password'  => Hash::make('password123'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // 2. Akun User Regular
        $user = User::updateOrCreate(
            ['email' => 'user@tripmate.com'],
            [
                'username'  => 'User Tripmate',
                'password'  => Hash::make('password123'),
                'role'      => 'user',
                'is_active' => true,
            ]
        );
        $user->assignRole('user');
    }
}
