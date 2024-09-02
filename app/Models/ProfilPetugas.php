<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPetugas extends Model
{
    use HasFactory;
    Protected $table = 'profil_petugases';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
    public function get_dataIsActvie($param){
        $data =  Self::query()
                        ->where('is_active',$param);
        return $data;
    }
}
