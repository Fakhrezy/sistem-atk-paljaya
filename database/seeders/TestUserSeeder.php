<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Teknik',
                'email' => 'admin.teknik@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'bidang' => 'teknik',
            ],
            [
                'name' => 'Staff Pemasaran',
                'email' => 'staff.pemasaran@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'bidang' => 'pemasaran',
            ],
            [
                'name' => 'Staff Keuangan',
                'email' => 'staff.keuangan@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'bidang' => 'keuangan',
            ],
            [
                'name' => 'Staff Umum',
                'email' => 'staff.umum@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'bidang' => 'umum',
            ],
            [
                'name' => 'Staff Lainnya',
                'email' => 'staff.lainnya@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'bidang' => 'lainnya',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
