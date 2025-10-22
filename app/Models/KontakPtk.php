<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontakPtk extends Model
{
    use HasFactory;

    protected $table = 'kontak_ptk';

    protected $fillable = [
        'ptk_id',
        'no_hp',
        'email',
        'alamat_jalan',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kode_pos',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
