<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ptk extends Model
{
    use HasFactory;

    protected $table = 'ptk';
    protected $fillable = [
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_ibu_kandung',
        'kode_pos',
        'agama',
        'npwp',
        'nama_wajib_pajak',
        'kewarganegaraan',
        'negara_asal',
    ];

    public function anak()
    {
        return $this->hasMany(AnakPtk::class, 'ptk_id');
    }

    public function tunjangan()
    {
        return $this->hasMany(Tunjangan::class, 'ptk_id');
    }

    public function kesejahteraan()
    {
        return $this->hasMany(KesejahteraanPtk::class, 'ptk_id');
    }

    public function tugasTambahan()
    {
        return $this->hasMany(TugasTambahan::class, 'ptk_id');
    }

    public function riwayatGaji()
    {
        return $this->hasMany(RiwayatGaji::class, 'ptk_id');
    }

    public function riwayatKarir()
    {
        return $this->hasMany(RiwayatKarir::class, 'ptk_id');
    }

    public function riwayatJabatan()
    {
        return $this->hasMany(RiwayatJabatan::class, 'ptk_id');
    }

    public function riwayatKepangkatan()
    {
        return $this->hasMany(RiwayatKepangkatan::class, 'ptk_id');
    }

    public function riwayatJabatanFungsional()
    {
        return $this->hasMany(RiwayatJabatanFungsional::class, 'ptk_id');
    }

    public function diklat()
    {
        return $this->hasMany(Diklat::class, 'ptk_id');
    }

    public function nilaiTest()
    {
        return $this->hasMany(NilaiTest::class, 'ptk_id');
    }

    public function pendidikan()
    {
        return $this->hasMany(PendidikanPtk::class, 'ptk_id');
    }

    public function sertifikat()
    {
        return $this->hasMany(SertifikatPtk::class, 'ptk_id');
    }

    public function beasiswa()
    {
        return $this->hasMany(BeasiswaPtk::class, 'ptk_id');
    }

    public function penghargaan()
    {
        return $this->hasMany(Penghargaan::class, 'ptk_id');
    }

    public function kompetensi()
    {
        return $this->hasMany(KompetensiPtk::class, 'ptk_id');
    }

    public function dokumenPtk()
    {
        return $this->hasOne(DokumenPtk::class, 'ptk_id');
    }

    public function keluarga()
    {
        return $this->hasMany(KeluargaPtk::class, 'ptk_id');
    }

    public function penugasan()
    {
        return $this->hasMany(PenugasanPtk::class, 'ptk_id');
    }

    public function kepegawaian()
    {
        return $this->hasOne(KepegawaianPtk::class, 'ptk_id');
    }


}
