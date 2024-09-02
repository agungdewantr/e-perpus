<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    use HasFactory;
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }
    public function get_dataIsActvie($param)
    {
        $data =  Self::query()->where('is_active', $param);

        return $data;
    }
    public function find_dataIsActvie($param, $param2)
    {
        $data =  Self::query()->where('is_active', $param)->where('kategori_id', $param2);

        return $data;
    }
}
