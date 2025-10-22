<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKepangkatan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kepangkatan';

    protected $fillable = [
        'ptk_id',
        'pangkat_golongan',
        'nomor_sk',
        'tanggal_sk',
        'tmt_pangkat',
        'masa_kerja_thn',
        'masa_kerja_bln',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
