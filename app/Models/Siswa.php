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

    public function beasiswa()
    {
        return $this->hasMany(BeasiswaSiswa::class, 'peserta_didik_id');
    }

    public function prestasi()
    {
        return $this->hasMany(PrestasiSiswa::class, 'peserta_didik_id');
    }

    public function kesejahteraan()
    {
        return $this->hasMany(KesejahteraanSiswa::class, 'peserta_didik_id');
    }

    public function dokumenSiswa()
    {
        return $this->hasOne(DokumenSiswa::class, 'peserta_didik_id');
    }

    public function periodik()
    {
        return $this->hasOne(PeriodikSiswa::class, 'peserta_didik_id');
    }

        public function orangTua()
    {
        return $this->hasOne(OrangTua::class, 'peserta_didik_id');
    }

    public function registrasiSiswa()
    {
        return $this->hasOne(RegistrasiSiswa::class, 'peserta_didik_id');
    }

    public function kontakSiswa()
    {
        return $this->hasOne(KontakSiswa::class, 'peserta_didik_id');
    }

}
