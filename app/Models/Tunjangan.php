<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tunjangan extends Model
{
    use HasFactory;

    protected $table = 'tunjangan';

    protected $fillable = [
        'ptk_id',
        'jenis_tunjangan',
        'nama_tunjangan',
        'instansi',
        'sk_tunjangan',
        'tgl_sk_tunjangan',
        'semester_id',
        'sumber_dana',
        'dari_tahun',
        'sampai_tahun',
        'nominal',
        'status',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
