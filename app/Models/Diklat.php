<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diklat extends Model
{
    use HasFactory;

    protected $table = 'diklat';
    protected $fillable = [
        'ptk_id',
        'jenis_diklat',
        'nama_diklat',
        'no_sertifikat',
        'penyelenggara',
        'tahun',
        'peran',
        'tingkat',
        'lama_jam',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }
}
