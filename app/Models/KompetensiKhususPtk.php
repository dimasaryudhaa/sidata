<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiKhususPtk extends Model
{
    use HasFactory;

    protected $table = 'kompetensi_khusus';

    protected $fillable = [
        'ptk_id',
        'punya_lisensi_kepala_sekolah',
        'nomor_unik_kepala_sekolah',
        'keahlian_lab_oratorium',
        'mampu_menangani',
        'keahlian_braile',
        'keahlian_bahasa_isyarat',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
