<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prosedur extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumens_id');
    }
}
