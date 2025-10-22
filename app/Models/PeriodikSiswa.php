<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Siswa;

class PeriodikSiswa extends Model
{
    use HasFactory;

    protected $table = 'data_periodik';

    protected $fillable = [
        'peserta_didik_id',
        'tinggi_badan_cm',
        'berat_badan_kg',
        'lingkar_kepala_cm',
        'jarak_ke_sekolah',
        'jarak_sebenarnya_km',
        'waktu_tempuh_jam',
        'waktu_tempuh_menit',
        'jumlah_saudara',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'peserta_didik_id');
    }
}
