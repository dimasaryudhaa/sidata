<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunSiswa extends Model
{
    use HasFactory;

    protected $table = 'akun_siswa';

    protected $fillable = [
        'peserta_didik_id',
        'email',
        'password',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'peserta_didik_id');
    }
}
