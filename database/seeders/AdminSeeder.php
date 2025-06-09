<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user admin default
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@polimedia.ac.id',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'is_admin' => true,
            'bio' => 'System Administrator',
            'angkatan' => '2021',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat beberapa user biasa untuk testing
        $users = [
            [
                'name' => 'Samuel Januarto Hutahaean',
                'username' => 'samuel21',
                'email' => '21240124@polimedia.ac.id',
                'angkatan' => '2021',
            ],
            [
                'name' => 'Lorem Ipsum',
                'username' => 'lorem1',
                'email' => '21240198@polimedia.ac.id',
                'angkatan' => '2021',
            ],
            [
                'name' => 'Lorem Ipsum',
                'username' => 'lorem2',
                'email' => '21240199@polimedia.ac.id',
                'angkatan' => '2021',
            ],
            [
                'name' => 'Lorem Ipsum',
                'username' => 'lorem3',
                'email' => '21240197@polimedia.ac.id',
                'angkatan' => '2021',
            ],
            [
                'name' => 'Lorem Ipsum',
                'username' => 'lorem4',
                'email' => '21240196@polimedia.ac.id',
                'angkatan' => '2021',
            ],
            [
                'name' => 'Lorem Ipsum',
                'username' => 'lorem5',
                'email' => '21240195@polimedia.ac.id',
                'angkatan' => '2021',
            ],
            [
                'name' => 'Lorem Ipsum',
                'username' => 'lorem6',
                'email' => '21240194@polimedia.ac.id',
                'angkatan' => '2021',
            ],
            [
                'name' => 'Lorem Ipsum',
                'username' => 'lorem7',
                'email' => '21240193@polimedia.ac.id',
                'angkatan' => '2021',
            ]
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'username' => $userData['username'],
                'email' => $userData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'bio' => 'Mahasiswa Politeknik Negeri Media Kreatif',
                'angkatan' => $userData['angkatan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
