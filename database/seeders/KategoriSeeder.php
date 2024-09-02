<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategori = [
            ['kode' => '000', 'nama' => 'Komputer, Informasi dan Referensi umum','is_active' => true],
            ['kode' => '100', 'nama' => 'Filsafat dan Psikologi','is_active' => true],
            ['kode' => '200', 'nama' => 'Agama','is_active' => true],
            ['kode' => '300', 'nama' => 'Ilmu Sosial','is_active' => true],
            ['kode' => '400', 'nama' => 'Bahasa','is_active' => true],
            ['kode' => '500', 'nama' => 'Sains dan Matematika','is_active' => true],
            ['kode' => '600', 'nama' => 'Teknologi','is_active' => true],
            ['kode' => '700', 'nama' => 'Kesenian dan Rekreasi','is_active' => true],
            ['kode' => '800', 'nama' => 'Sastra','is_active' => true],
            ['kode' => '900', 'nama' => 'Sejarah dan Geografi','is_active' => true],
        ];

        \DB::table('kategoris')->insert($kategori);
    }
}
