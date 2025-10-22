<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikatPtk extends Model
{
    use HasFactory;

    protected $table = 'sertifikat';

    protected $fillable = [
        'ptk_id',
        'jenis_sertifikasi',
        'nomor_sertifikat',
        'tahun_sertifikasi',
        'bidang_studi',
        'nrg',
        'nomor_peserta',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
