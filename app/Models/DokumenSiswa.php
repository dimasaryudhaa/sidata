<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenSiswa extends Model
{
    use HasFactory;

    protected $table = 'dokumen_siswa';

    protected $fillable = [
        'peserta_didik_id',
        'akte_kelahiran',
        'kartu_keluarga',
        'ktp_ayah',
        'ktp_ibu',
        'ijazah_sd',
        'ijazah_smp',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'peserta_didik_id');
    }
}
