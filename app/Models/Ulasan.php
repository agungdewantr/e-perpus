<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;
    protected $dates = ['created_at', 'updated_at'];
    public function profilAnggota()
    {
        // Relasi one-to-one dengan model Profil
        return $this->belongsTo(ProfilAnggota::class, 'profil_anggota_id','id');
    }

    public function waktuYangLalu()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
