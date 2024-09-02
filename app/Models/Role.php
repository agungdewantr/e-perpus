<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'permissions')
            ->where('is_active', true)
            ->withPivot('can_read', 'can_create', 'can_update', 'can_delete')
            ->withTimestamps();
    }

    public function get_dataIsActvie($param){
        $data =  Self::query()->where('is_active',$param);
        return $data;
    }

    public static function get_dataByNama($param){
        $data = self::query()->where('nama', $param)->first();
        return $data;
    }
}
