<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function gambar()
    {
        return $this->belongsTo(Dokumen::class, 'gambar_id');
    }
}
