<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiTest extends Model
{
    use HasFactory;

    protected $table = 'nilai_test';

    protected $fillable = [
        'ptk_id',
        'jenis_test',
        'nama_test',
        'penyelenggara',
        'tahun',
        'skor',
        'nomor_peserta',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }
}
