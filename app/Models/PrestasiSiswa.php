<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Siswa;

class PrestasiSiswa extends Model
{
    use HasFactory;

    protected $table = 'prestasi';

    protected $fillable = [
        'peserta_didik_id',
        'jenis_prestasi',
        'tingkat_prestasi',
        'nama_prestasi',
        'tahun_prestasi',
        'penyelenggara',
        'peringkat',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'peserta_didik_id');
    }
}
