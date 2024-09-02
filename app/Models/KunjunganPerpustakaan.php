<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunjunganPerpustakaan extends Model
{
    use HasFactory;
    public function profilAnggota()
    {
        return $this->belongsTo(ProfilAnggota::class, 'profil_anggota_id','id');
    }
}
