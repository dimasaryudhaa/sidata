<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KesejahteraanSiswa extends Model
{
    use HasFactory;

    protected $table = 'kesejahteraan';

    protected $fillable = [
        'peserta_didik_id',
        'jenis_kesejahteraan',
        'no_kartu',
        'nama_di_kartu',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'peserta_didik_id');
    }
}
