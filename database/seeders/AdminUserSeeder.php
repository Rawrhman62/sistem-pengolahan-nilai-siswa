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
        // Create initial admin user
        User::create([
            'name' => 'Administrator',
            'user_name' => 'Administrator',
            'user_id' => 'admin',
            'email' => 'admin@example.com',
            'phone_number' => null,
            'role' => 'administrator',
            'password' => Hash::make('admin123'),
            'password_set' => true,
        ]);

        // Create additional admin users
        $users = [
            [
                'name' => 'Takanashi Hoshino',
                'user_name' => 'Takanashi Hoshino',
                'user_id' => '3312501067',
                'email' => 'takanashihoshino@bmail.com',
                'role' => 'administrator',
                'password' => Hash::make('Admin123'),
                'password_set' => true,
            ],
            [
                'name' => 'Fazri Rahman',
                'user_name' => 'Fazri Rahman',
                'user_id' => '3312501038',
                'email' => 'fazri@email.com',
                'role' => 'administrator',
                'password' => Hash::make('Fazri123'),
                'password_set' => true,
            ],
            [
                'name' => 'Ahmad Rafi Sa\'id F.',
                'user_name' => 'Ahmad Rafi Sa\'id F.',
                'user_id' => '3312501051',
                'email' => 'rafi@email.com',
                'role' => 'administrator',
                'password' => Hash::make('Rafi123'),
                'password_set' => true,
            ],
            [
                'name' => 'Muradika Laksa P.',
                'user_name' => 'Muradika Laksa P.',
                'user_id' => '3312501059',
                'email' => '67murdik67@yahoo.com',
                'role' => 'administrator',
                'password' => Hash::make('Dika123'),
                'password_set' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}