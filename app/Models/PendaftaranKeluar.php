<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranKeluar extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_keluar';

    protected $fillable = [
        'ptk_id',
        'orang_tua_id',
        'peserta_didik_id',
        'keluar_karena',
        'tanggal_keluar',
        'alasan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'peserta_didik_id');
    }

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }

    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'orang_tua_id');
    }
}
