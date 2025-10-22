<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeluargaPtk extends Model
{
    use HasFactory;

    protected $table = 'keluarga_ptk';

    protected $fillable = [
        'ptk_id',
        'no_kk',
        'status_perkawinan',
        'nama_suami_istri',
        'nip_suami_istri',
        'pekerjaan_suami_istri',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
