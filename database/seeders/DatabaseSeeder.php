<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $this->call(RoleSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(MenuSeeder::class);
        // $this->call(PermissionSeeder::class);
        // $this->call(ProfilPetugasSeeder::class);
        // $this->call(KategoriSeeder::class);
        // $this->call(SubKategoriSeeder::class);
        // $this->call(RakSeeder::class);
        $this->call(PenerbitSeeder::class);
    }
}
