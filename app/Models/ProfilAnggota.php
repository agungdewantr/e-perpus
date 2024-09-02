<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilAnggota extends Model
{
    use HasFactory;
    Protected $table = 'profil_anggotas';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
    public function fotoKartuIdentitas()
    {
        return $this->belongsTo(Dokumen::class, 'foto_kartu_identitas_id','id');
    }
    public function get_dataIsActvie($param){
        $data =  Self::query()
                        ->where('is_active',$param);
        return $data;
    }
    public function get_dataIsVerified($param){
        $data =  Self::query()
                    ->where('is_verified',$param);
        return $data;
    }
    public function get_dataIsActvieAndIsVerified($param, $param2){
        $data =  Self::query()
                        ->where('is_active',$param)
                        ->where('is_verified',$param2);
        return $data;
    }
    public function find_data($param){
        $data =  Self::query()
                        ->where('user_id',$param)
                        ->first();
        return $data;
    }
    public function get_latestNomorAnggotaByYear($param){
        $data =  Self::query()
                        ->wherenotnull('tanggal_verified')
                        ->whereyear('tanggal_verified',$param)
                        ->max('nomor_anggota') ?? "000.4/000/DPA/".$param;
        return $data;
    }
    public static function generateNomorAnggota($param)
    {
        $latestNomorAnggota = self::get_latestNomorAnggotaByYear($param);
        $parts = explode('/', $latestNomorAnggota);
        $nomor_urut_value_last = intval($parts[1]);

        $nomor_urut_value_new = sprintf('%03d', $nomor_urut_value_last+1);
        $nomor_anggota = "000.4/{$nomor_urut_value_new}/DPA/{$param}";

        return $nomor_anggota;
    }
}
