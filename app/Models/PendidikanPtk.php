<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ptk;

class PendidikanPtk extends Model
{
    use HasFactory;

    protected $table = 'pendidikan_ptk';

    protected $fillable = [
        'ptk_id',
        'bidang_studi',
        'jenjang_pendidikan',
        'gelar_akademik',
        'satuan_pendidikan_formal',
        'fakultas',
        'kependidikan',
        'tahun_masuk',
        'tahun_lulus',
        'nomor_induk',
        'masih_studi',
        'semester',
        'rata_rata_ujian',

    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }
}
