<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->insert([
            'nama' => 'anggota',
            'is_active' => 1
        ]);
        \DB::table('roles')->insert([
            'nama' => 'petugas',
            'is_active' => 1
        ]);
        \DB::table('roles')->insert([
            'nama' => 'admin',
            'is_active' => 1
        ]);
        \DB::table('roles')->insert([
            'nama' => 'superadmin',
            'is_active' => 1
        ]);
    }
}
