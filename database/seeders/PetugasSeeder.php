<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = [
            [
                'name' => 'Ahmad Budiman',
                'email' => 'petugas1@jalan.id',
                'password' => Hash::make('petugas123'),
                'role' => 'petugas',
                'is_active' => true,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'petugas2@jalan.id',
                'password' => Hash::make('petugas123'),
                'role' => 'petugas',
                'is_active' => true,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'petugas3@jalan.id',
                'password' => Hash::make('petugas123'),
                'role' => 'petugas',
                'is_active' => true,
            ],
        ];

        foreach ($petugas as $p) {
            User::updateOrCreate(
                ['email' => $p['email']],
                $p
            );
        }
    }
}