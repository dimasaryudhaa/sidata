<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPtk extends Model
{
    use HasFactory;

    protected $table = 'dokumen_ptk';

    protected $fillable = [
        'ptk_id',
        'akte_kelahiran',
        'kartu_keluarga',
        'ktp',
        'ijazah_sd',
        'ijazah_smp',
        'ijazah_sma',
        'ijazah_s1',
        'ijazah_s2',
        'ijazah_s3',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }
}
