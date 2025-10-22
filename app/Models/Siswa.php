<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rayon;
use App\Models\Rombel;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'peserta_didik';

    protected $fillable = [
        'nama_lengkap',
        'jenis_kelamin',
        'nis',
        'nisn',
        'nik',
        'no_kk',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'rayon_id',
        'rombel_id',    
        'kewarganegaraan',
        'negara_asal',
        'berkebutuhan_khusus',
    ];

    public function rayon() {
        return $this->belongsTo(Rayon::class);
    }

    public function rombel() {
        return $this->belongsTo(Rombel::class);
    }


}
