<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    Protected $table = 'permissions';
    public $incrementing = false;
    protected $primaryKey = ['role_id', 'menu_id'];

    protected $fillable = [
        'role_id', 'menu_id', 'can_read', 'can_create', 'can_update', 'can_delete',
    ];

    public static function update_dataPermission($menu_id ,$role_id,$canRead, $canCreate, $canUpdate, $canDelete){
        $sql = "UPDATE permissions
        SET can_read = :can_read,
            can_create = :can_create,
            can_update = :can_update,
            can_delete = :can_delete
        where menu_id = :menu_id AND role_id = :role_id";

        return \DB::select($sql, [
            'menu_id' => $menu_id,
            'role_id' => $role_id,
            'can_read' => $canRead,
            'can_create' => $canCreate,
            'can_update' => $canUpdate,
            'can_delete' => $canDelete
        ]);
    }
}
