<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;

    protected $table = 'orang_tua';

    protected $fillable = [
        'peserta_didik_id',
        'nama_ayah', 'nik_ayah', 'tahun_lahir_ayah', 'pendidikan_ayah',
        'pekerjaan_ayah', 'penghasilan_ayah', 'kebutuhan_khusus_ayah',
        'nama_ibu', 'nik_ibu', 'tahun_lahir_ibu', 'pendidikan_ibu',
        'pekerjaan_ibu', 'penghasilan_ibu', 'kebutuhan_khusus_ibu',
        'nama_wali', 'nik_wali', 'tahun_lahir_wali', 'pendidikan_wali',
        'pekerjaan_wali', 'penghasilan_wali',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'peserta_didik_id');
    }
}
