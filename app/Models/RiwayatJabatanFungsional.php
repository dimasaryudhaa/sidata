<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatJabatanFungsional extends Model
{
    use HasFactory;

    protected $table = 'riwayat_jabatan_fungsional';

    protected $fillable = [
        'ptk_id',
        'jabatan_fungsional',
        'sk_jabfung',
        'tmt_jabatan',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
