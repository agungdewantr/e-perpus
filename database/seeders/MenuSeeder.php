<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \DB::table('menus')->insert([
        //     'nama' => 'User',
        //     'key_nama' => 'user',
        //     'route' => 'user.index',
        //     'url' => '/user',
        //     'icon' => 'fas fa-users',
        //     'ordinal_number' => 1,
        //     'is_active' => 1,
        //     'tipe_menu' => 'management',
        // ]);
        \DB::table('menus')->insert([
            'id' => 2,
            'nama' => 'Role',
            'key_nama' => 'role',
            'route' => 'role.index',
            'url' => '/role',
            'icon' => 'fas fa-tags',
            'ordinal_number' => 2,
            'is_active' => 1,
            'tipe_menu' => 'management',
        ]);
        \DB::table('menus')->insert([
            'id' => 3,
            'nama' => 'Menu',
            'key_nama' => 'menu',
            'route' => 'menu.index',
            'url' => '/menu',
            'icon' => 'fas fa-bars',
            'ordinal_number' => 3,
            'is_active' => 1,
            'tipe_menu' => 'management',
        ]);
        \DB::table('menus')->insert([
            'id' => 4,
            'nama' => 'Permission',
            'key_nama' => 'permission',
            'route' => 'permission.index',
            'url' => '/permission',
            'icon' => 'fas fa-globe',
            'ordinal_number' => 4,
            'is_active' => 1,
            'tipe_menu' => 'management',
        ]);
        \DB::table('menus')->insert([
            'id' => 5,
            'nama' => 'Activity Log',
            'key_nama' => 'activity_log',
            'route' => 'activitylog.index',
            'url' => '/activity-log',
            'icon' => 'fas fa-history',
            'ordinal_number' => 5,
            'is_active' => 1,
            'tipe_menu' => 'management',
        ]);
    }
}
