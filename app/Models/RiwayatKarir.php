<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKarir extends Model
{
    use HasFactory;

    protected $table = 'riwayat_karir';

    protected $fillable = [
        'ptk_id',
        'jenjang_pendidikan',
        'jenis_lembaga',
        'status_kepegawaian',
        'jenis_ptk',
        'lembaga_pengangkat',
        'no_sk_kerja',
        'tgl_sk_kerja',
        'tmt_kerja',
        'tst_kerja',
        'tempat_kerja',
        'ttd_sk_kerja',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }
}
