<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $raks = [
            ['kode' => '000 - Komputer, Informasi dan Referensi umum','is_active' => true],
            ['kode' => '100 - Filsafat dan Psikologi','is_active' => true],
            ['kode' => '200 - Agama','is_active' => true],
            ['kode' => '300 - Ilmu Sosial','is_active' => true],
            ['kode' => '400 - Bahasa','is_active' => true],
            ['kode' => '500 - Sains dan Matematika','is_active' => true],
            ['kode' => '600 - Teknologi','is_active' => true],
            ['kode' => '700 - Kesenian dan Rekreasi','is_active' => true],
            ['kode' => '800 - Sastra','is_active' => true],
            ['kode' => '900 - Sejarah dan Geografi','is_active' => true],
        ];

        \DB::table('raks')->insert($raks);
    }
}
