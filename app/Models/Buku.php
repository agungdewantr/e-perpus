<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;
    public function kategories()
    {
        return $this->belongsToMany(Kategori::class, 'buku_has_kategoris','bukus_id', 'kategoris_id');
    }
    public function penulises()
    {
        return $this->belongsToMany(Penulis::class, 'penulis_bukus','bukus_id', 'penulies');
    }
    public function cover()
    {
        return $this->belongsTo(Dokumen::class, 'cover_id','id');
    }
    public function audio()
    {
        return $this->belongsTo(Dokumen::class, 'file_audio_id','id');
    }
    public function digital()
    {
        return $this->belongsTo(Dokumen::class, 'file_digital_id','id');
    }
    public function penerbit()
    {
        return $this->belongsTo(Penerbit::class, 'penerbit_id','id');
    }
    public function rak()
    {
        return $this->belongsTo(Rak::class, 'raks_id','id');
    }
    public function itemBukus()
    {
        return $this->hasMany(ItemBuku::class, 'bukus_id','id');
    }

    public static function get_stok(int $id): int
    {
        $count = \DB::table('bukus as b')
            ->join('item_bukus as ib', 'b.id', '=', 'ib.bukus_id')
            ->where('b.id', '=', $id)
            ->where('ib.is_tersedia', '=', 1)
            ->count();

            return $count;
    }
    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class, 'sub_kategori_id','id');
    }

    public function favorit()
    {
        return $this->hasMany(Favorit::class, 'buku_id');
    }

    // Method untuk menghitung jumlah buku yang ada di tabel Favorit
    public function hitungJumlahFavorit()
    {
        return $this->favorit()->count();
    }

        public function get_dataIsActive($param){
        $data =  Self::query()->where('bukus.is_active',$param);

        return $data;
    }
}
