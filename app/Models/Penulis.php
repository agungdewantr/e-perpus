<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penulis extends Model
{
    use HasFactory;
    public function get_dataIsActvie($param){
        $data =  Self::query()->where('is_active',$param);

        return $data;
    }
    public function bukus()
    {
        return $this->belongsToMany(Buku::class);
    }
    public function get_id(){
        $data = Self::query()->orderby('id', 'desc')->pluck('id')->first();
        return $data;
    }
}
