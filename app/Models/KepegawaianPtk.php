<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepegawaianPtk extends Model
{
    use HasFactory;

    protected $table = 'kepegawaian';

    protected $fillable = [
        'ptk_id',
        'status_kepegawaian',
        'nip',
        'niy_nigk',
        'nuptk',
        'jenis_ptk',
        'sk_pengangkatan',
        'tmt_pengangkatan',
        'lembaga_pengangkat',
        'sk_cpns',
        'tmt_pns',
        'pangkat_golongan',
        'sumber_gaji',
        'kartu_pegawai',
        'kartu_keluarga',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
