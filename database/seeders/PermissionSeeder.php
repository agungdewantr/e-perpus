<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \DB::table('permissions')->insert([
        //     'role_id' => 4,
        //     'menu_id' => 1,
        //     'can_read' => 1,
        //     'can_create' => 1,
        //     'can_update' => 1,
        //     'can_delete' => 1,
        // ]);
        \DB::table('permissions')->insert([
            'role_id' => 4,
            'menu_id' => 2,
            'can_read' => 1,
            'can_create' => 1,
            'can_update' => 1,
            'can_delete' => 1,
        ]);
        \DB::table('permissions')->insert([
            'role_id' => 4,
            'menu_id' => 3,
            'can_read' => 1,
            'can_create' => 1,
            'can_update' => 1,
            'can_delete' => 1,
        ]);
        \DB::table('permissions')->insert([
            'role_id' => 4,
            'menu_id' => 4,
            'can_read' => 1,
            'can_create' => 1,
            'can_update' => 1,
            'can_delete' => 1,
        ]);
        \DB::table('permissions')->insert([
            'role_id' => 4,
            'menu_id' => 5,
            'can_read' => 1,
            'can_create' => 1,
            'can_update' => 1,
            'can_delete' => 1,
        ]);
        //ROLE ID 1 (ANGGOTA)
            // \DB::table('permissions')->insert([
            //     'role_id' => 1,
            //     'menu_id' => 1,
            //     'can_read' => 0,
            //     'can_create' => 0,
            //     'can_update' => 0,
            //     'can_delete' => 0,
            // ]);
            \DB::table('permissions')->insert([
                'role_id' => 1,
                'menu_id' => 2,
                'can_read' => 0,
                'can_create' => 0,
                'can_update' => 0,
                'can_delete' => 0,
            ]);
            \DB::table('permissions')->insert([
                'role_id' => 1,
                'menu_id' => 3,
                'can_read' => 0,
                'can_create' => 0,
                'can_update' => 0,
                'can_delete' => 0,
            ]);
            \DB::table('permissions')->insert([
                'role_id' => 1,
                'menu_id' => 4,
                'can_read' => 0,
                'can_create' => 0,
                'can_update' => 0,
                'can_delete' => 0,
            ]);
            \DB::table('permissions')->insert([
                'role_id' => 1,
                'menu_id' => 5,
                'can_read' => 0,
                'can_create' => 0,
                'can_update' => 0,
                'can_delete' => 0,
            ]);
        //CLOSE ROLE ID 1 (ANGGOTA)
        //ROLE ID 2 (PETUGAS)
            // \DB::table('permissions')->insert([
            //     'role_id' => 2,
            //     'menu_id' => 1,
            //     'can_read' => 0,
            //     'can_create' => 0,
            //     'can_update' => 0,
            //     'can_delete' => 0,
            // ]);
            \DB::table('permissions')->insert([
                'role_id' => 2,
                'menu_id' => 2,
                'can_read' => 0,
                'can_create' => 0,
                'can_update' => 0,
                'can_delete' => 0,
            ]);
            \DB::table('permissions')->insert([
                'role_id' => 2,
                'menu_id' => 3,
                'can_read' => 0,
                'can_create' => 0,
                'can_update' => 0,
                'can_delete' => 0,
            ]);
            \DB::table('permissions')->insert([
                'role_id' => 2,
                'menu_id' => 4,
                'can_read' => 0,
                'can_create' => 0,
                'can_update' => 0,
                'can_delete' => 0,
            ]);
            \DB::table('permissions')->insert([
                'role_id' => 2,
                'menu_id' => 5,
                'can_read' => 0,
                'can_create' => 0,
                'can_update' => 0,
                'can_delete' => 0,
            ]);
        //CLOSE ROLE ID 2 (PETUGAS)
        //ROLE ID 3 (ADMIN)
            // \DB::table('permissions')->insert([
            //     'role_id' => 3,
            //     'menu_id' => 1,
            //     'can_read' => 0,
            //     'can_create' => 0,
            //     'can_update' => 0,
            //     'can_delete' => 0,
            // ]);
            \DB::table('permissions')->insert([
                'role_id' => 3,
                'menu_id' => 2,
                'can_read' => 0,
                'can_create' => 0,
                'can_update' => 0,
                'can_delete' => 0,
            ]);
            \DB::table('permissions')->insert([
                'role_id' => 3,
                'menu_id' => 3,
                'can_read' => 0,
                'can_create' => 0,
                'can_update' => 0,
                'can_delete' => 0,
            ]);
            \DB::table('permissions')->insert([
                'role_id' => 3,
                'menu_id' => 4,
                'can_read' => 0,
                'can_create' => 0,
                'can_update' => 0,
                'can_delete' => 0,
            ]);
            \DB::table('permissions')->insert([
                'role_id' => 3,
                'menu_id' => 5,
                'can_read' => 0,
                'can_create' => 0,
                'can_update' => 0,
                'can_delete' => 0,
            ]);
        //CLOSE ROLE ID 3 (ADMIN)
    }
}
