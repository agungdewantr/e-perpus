<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'menu_id','id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permissions')
            ->withTimestamps();
    }
    public static function tipe_menu()
    {
        return [
            'management',
            'master data',
            'other',
        ];
    }

    public static function byRolePermission(int $roleId): ?array
    {
        $sql = "SELECT m.nama, m.route, m.url, m.icon, m.key_nama, m.tipe_menu
                FROM permissions p
                    JOIN menus m ON m.id = p.menu_id
                WHERE p.role_id = :role_id AND m.is_active is true AND p.can_read is true
                ORDER BY m.ordinal_number";

        return \DB::select($sql, [
            'role_id' => $roleId,
        ]);
    }
    public static function tipeMenu(int $roleId): ?array
    {
        $sql = "SELECT DISTINCT  m.tipe_menu
                        FROM permissions p
                        JOIN menus m ON m.id = p.menu_id
                        WHERE p.role_id = :role_id AND m.is_active IS TRUE
                        ORDER BY m.tipe_menu";

        return \DB::select($sql, [
            'role_id' => $roleId,
        ]);
    }
    public function get_dataIsActvie($param){
        $data =  Self::query()->where('is_active',$param);

        return $data;
    }
}
