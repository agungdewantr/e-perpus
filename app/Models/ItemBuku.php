<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBuku extends Model
{
    use HasFactory;
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'bukus_id','id');
    }
}
