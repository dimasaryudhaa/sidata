<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontakSiswa extends Model
{
    use HasFactory;

    protected $table = 'kontak_peserta_didik';

    protected $fillable = [
        'peserta_didik_id',
        'no_hp',
        'email',
        'alamat_jalan',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kode_pos',
        'tempat_tinggal',
        'moda_transportasi',
        'anak_ke',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'peserta_didik_id');
    }

}
