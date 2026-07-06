<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '123',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $user->assignRole('Admin');

        $user = User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'phone' => '1234',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $user->assignRole('User');
    }
}
