<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ptk extends Model
{
    use HasFactory;

    protected $table = 'ptk';   
    protected $fillable = [
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_ibu_kandung',
        'kode_pos',
        'agama',
        'npwp',
        'nama_wajib_pajak',
        'kewarganegaraan',
        'negara_asal',
    ];
}
