<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    use HasFactory;
    public function get_dataIsActvie($param){
        $data =  Self::query()->where('is_active',$param);

        return $data;
    }
}
