<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Siswa;

class BeasiswaSiswa extends Model
{
    use HasFactory;

    protected $table = 'beasiswa';
    protected $fillable = [
        'peserta_didik_id',
        'jenis_beasiswa',
        'keterangan',
        'tahun_mulai',
        'tahun_selesai',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'peserta_didik_id');
    }
}
