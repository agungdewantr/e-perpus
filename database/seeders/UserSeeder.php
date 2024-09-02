<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Admin
        \DB::table('users')->insert([
            'username' => 'superadmin',
            'email' => 'superadmin@email.com',
            'password' => Hash::make('12345678'),
            'role_id' => '4',
            'is_active' => 1
        ]);
        //Petugas
        \DB::table('users')->insert([
            'username' => 'petugas',
            'email' => 'petugas@email.com',
            'password' => Hash::make('12345678'),
            'role_id' => '2',
            'is_active' => 1
        ]);
    }
}
