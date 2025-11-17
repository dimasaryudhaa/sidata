<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanPtk extends Model
{
    use HasFactory;

    protected $table = 'penugasan';

    protected $fillable = [
        'ptk_id',
        'nomor_surat_tugas',
        'tanggal_surat_tugas',
        'tmt_tugas',
        'status_sekolah_induk',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
