<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnakPtk extends Model
{
    use HasFactory;

    protected $table = 'anak';

    protected $fillable = [
        'ptk_id',
        'nama_anak',
        'status_anak',
        'jenjang',
        'nisn',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'tahun_masuk',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
