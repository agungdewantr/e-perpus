<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProfilPetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Petugas
        \DB::table('profil_petugases')->insert([
            'nama_lengkap' => 'petugas',
            'nomor_telepon' => '08112345678',
            'user_id' => '2',
            'jadwal_shift_mulai' => '09:00:00',
            'jadwal_shift_selesai' => '12:00:00'
        ]);
    }
}
