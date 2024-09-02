<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    public function bukus()
    {
        return $this->belongsTo(Buku::class, 'buku_id', 'id');
    }
}
