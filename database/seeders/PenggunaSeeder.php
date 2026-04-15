<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        Pengguna::create([
            'nama' => 'Disnaker',
            'email' => 'admin@disnaker.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'disnaker',
            'status' => 'aktif',
            'nip' => '1234567890',
        ]);

        Pengguna::create([
            'nama' => 'Perusahaan X',
            'email' => 'admin@perusahaan.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'perusahaan',
            'status' => 'aktif',
        ]);

        Pengguna::create([
            'nama' => 'Pencari Kerja',
            'email' => 'user@pencarikerja.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'pencari_kerja',
            'status' => 'aktif',
        ]);
    }
}
