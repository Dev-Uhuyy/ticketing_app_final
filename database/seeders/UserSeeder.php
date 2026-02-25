<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'superadmin',
            ],
            [
                'name' => 'Pengelola Event Default',
                'email' => 'pengelola@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'pengelola_event',
            ],
            [
                'name' => 'Regular Pembeli',
                'email' => 'user@gmail.com',
                'password' => bcrypt('password'),
                'no_hp' => '081234567890',
                'role' => 'pembeli',
            ]
        ];
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
