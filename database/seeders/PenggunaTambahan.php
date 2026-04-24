<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PenggunaTambahan extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'id_pengguna' => 'USR-260422-00010',
                'id_pencaker' => 'PCK-260422-00010',
                'nama' => 'Budi Santoso',
                'email' => 'budi@mail.com',
            ],
            [
                'id_pengguna' => 'USR-260422-00011',
                'id_pencaker' => 'PCK-260422-00011',
                'nama' => 'Budi Santoso 2',
                'email' => 'budi1@mail.com',
            ],
            [
                'id_pengguna' => 'USR-260422-00012',
                'id_pencaker' => 'PCK-260422-00012',
                'nama' => 'Budi Santoso 3',
                'email' => 'budi2@mail.com',
            ],
        ];

        foreach ($data as $item) {

            // Insert ke tabel pengguna
            DB::table('pengguna')->insert([
                'id_pengguna' => $item['id_pengguna'],
                'nama' => $item['nama'],
                'email' => 'user_' . $item['email'], // email login
                'kata_sandi' => Hash::make('password123'),
                'peran' => 'pencaker',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert ke profil pencari kerja
            DB::table('profil_pencari_kerja')->insert([
                'id_pencari_kerja' => $item['id_pencaker'],
                'id_pengguna' => $item['id_pengguna'],
                'nik' => '12345678901234' . rand(10, 99),
                'nomor_kk' => '12345678901234' . rand(10, 99),
                'nama_lengkap' => $item['nama'],
                'tempat_lahir' => 'Jayapura',
                'tanggal_lahir' => '2000-03-03',
                'jenis_kelamin' => 'L',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'alamat' => 'Jl. Contoh No. ' . rand(1, 100),
                'rt' => '003',
                'rw' => '002',
                'kelurahan' => 'Hinekombe',
                'kecamatan' => 'Sentani',
                'kab_kota' => 'Kabupaten Jayapura',
                'provinsi' => 'Papua',
                'kode_pos' => '99352',
                'nomor_hp' => '08123' . rand(1000000, 9999999),
                'email' => $item['email'], // email profil (mirip)
                'foto' => 'foto_pencaker/default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
