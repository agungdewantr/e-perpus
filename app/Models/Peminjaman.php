<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjamans';
    public function itemBuku()
    {
        return $this->belongsTo(ItemBuku::class, 'item_bukus_id','id');
    }
    public function profilAnggota()
    {
        return $this->belongsTo(ProfilAnggota::class, 'profil_anggota_id','id');
    }
}
