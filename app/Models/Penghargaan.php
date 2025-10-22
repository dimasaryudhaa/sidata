<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penghargaan extends Model
{
    use HasFactory;

    protected $table = 'penghargaan';

    protected $fillable = [
        'ptk_id',
        'tingkat_penghargaan',
        'jenis_penghargaan',
        'nama_penghargaan',
        'tahun',
        'instansi',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }
}
