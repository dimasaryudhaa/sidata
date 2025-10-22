<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrasiSiswa extends Model
{
    use HasFactory;

    protected $table = 'registrasi_peserta_didik';

    protected $fillable = [
        'peserta_didik_id',
        'jenis_pendaftaran',
        'tanggal_masuk',
        'sekolah_asal',
        'no_peserta_un',
        'no_seri_ijazah',
        'no_skhun',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'peserta_didik_id');
    }
}
