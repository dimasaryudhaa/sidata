<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeasiswaPtk extends Model
{
    use HasFactory;

    protected $table = 'beasiswa_ptk';

    protected $fillable = [
        'ptk_id',
        'jenis_beasiswa',
        'keterangan',
        'tahun_mulai',
        'tahun_akhir',
        'masih_menerima',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
