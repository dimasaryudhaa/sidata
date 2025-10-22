<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatGaji extends Model
{
    use HasFactory;

    protected $table = 'riwayat_gaji';

    protected $fillable = [
        'ptk_id',
        'pangkat_golongan',
        'nomor_sk',
        'tanggal_sk',
        'tmt_kgb',
        'masa_kerja_thn',
        'masa_kerja_bln',
        'gaji_pokok',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }
}
