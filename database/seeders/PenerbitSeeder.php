<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PenerbitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $penerbits = [
            ['namaPenerbit' => 'Al-Wafi Publishing', 'is_active' => true],
            ['namaPenerbit' => 'An-Najaa', 'is_active' => true],
            ['namaPenerbit' => 'ARIA MEDIA MANDIRI', 'is_active' => true],
            ['namaPenerbit' => 'Aryhaeko Sinergi Persada', 'is_active' => true],
            ['namaPenerbit' => 'Azka Pressindo', 'is_active' => true],
            ['namaPenerbit' => 'BRIGHT PUBLISHER', 'is_active' => true],
            ['namaPenerbit' => 'BRILLIANT BOOKS', 'is_active' => true],
            ['namaPenerbit' => 'Cakrawala', 'is_active' => true],
            ['namaPenerbit' => 'Cakrawala Ilmu', 'is_active' => true],
            ['namaPenerbit' => 'CAPS', 'is_active' => true],
            ['namaPenerbit' => 'CEMERLANG PUBLISHING', 'is_active' => true],
            ['namaPenerbit' => 'C-KLIK MEDIA', 'is_active' => true],
            ['namaPenerbit' => 'CV. Lebah Buku Group', 'is_active' => true],
            ['namaPenerbit' => 'Familia', 'is_active' => true],
            ['namaPenerbit' => 'Forum', 'is_active' => true],
            ['namaPenerbit' => 'GALANG PRESS GROUP', 'is_active' => true],
            ['namaPenerbit' => 'GALANG PUSTAKA', 'is_active' => true],
            ['namaPenerbit' => 'Gava Media', 'is_active' => true],
            ['namaPenerbit' => 'Graha Ilmu', 'is_active' => true],
            ['namaPenerbit' => 'GREAT! PUBLISHER', 'is_active' => true],
            ['namaPenerbit' => 'Griya Pustka Utama', 'is_active' => true],
            ['namaPenerbit' => 'IMMORTAL PUBLISHER', 'is_active' => true],
            ['namaPenerbit' => 'IMMORTAL PUBLISHING', 'is_active' => true],
            ['namaPenerbit' => 'IMMORTAL X OCTOPUS', 'is_active' => true],
            ['namaPenerbit' => 'INDONESIA CERDAS', 'is_active' => true],
            ['namaPenerbit' => 'Istana Agency', 'is_active' => true],
            ['namaPenerbit' => 'Istana Media', 'is_active' => true],
            ['namaPenerbit' => 'JOGJA BANGKIT', 'is_active' => true],
            ['namaPenerbit' => 'KANA MEDIA', 'is_active' => true],
            ['namaPenerbit' => 'KLIK PUBLISHING', 'is_active' => true],
            ['namaPenerbit' => 'LEBAH BUKU', 'is_active' => true],
            ['namaPenerbit' => 'Madani Berkah Abadi', 'is_active' => true],
            ['namaPenerbit' => 'Media Pressindo', 'is_active' => true],
            ['namaPenerbit' => 'NARASI', 'is_active' => true],
            ['namaPenerbit' => 'PABRIK TULISAN', 'is_active' => true],
            ['namaPenerbit' => 'Parama Ilmu', 'is_active' => true],
            ['namaPenerbit' => 'PUSTAKA ALBANA', 'is_active' => true],
            ['namaPenerbit' => 'PUSTAKA ANGGREK', 'is_active' => true],
            ['namaPenerbit' => 'Pustaka Anggrek', 'is_active' => true],
            ['namaPenerbit' => 'Pustaka Arafah', 'is_active' => true],
            ['namaPenerbit' => 'Pustaka Edukasia', 'is_active' => true],
            ['namaPenerbit' => 'PUSTAKA GRHATAMA', 'is_active' => true],
            ['namaPenerbit' => 'Pustaka Panasea', 'is_active' => true],
            ['namaPenerbit' => 'PUSTAKA WIDYATAMA', 'is_active' => true],
            ['namaPenerbit' => 'Qudsi Media', 'is_active' => true],
            ['namaPenerbit' => 'Relasi Inti Media', 'is_active' => true],
            ['namaPenerbit' => 'SECOND HOPE', 'is_active' => true],
            ['namaPenerbit' => 'Shinju Publisher', 'is_active' => true],
            ['namaPenerbit' => 'SHIRA MEDIA', 'is_active' => true],
            ['namaPenerbit' => 'Spektrum Nusantara', 'is_active' => true],
            ['namaPenerbit' => 'Suluh Media', 'is_active' => true],
            ['namaPenerbit' => 'Syalmahat', 'is_active' => true],
            ['namaPenerbit' => 'Textium', 'is_active' => true],
            ['namaPenerbit' => 'Yanita', 'is_active' => true],
        ];

        \DB::table('penerbits')->insert($penerbits);
    }
}
