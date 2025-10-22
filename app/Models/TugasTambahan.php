<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasTambahan extends Model
{
    use HasFactory;

    protected $table = 'tugas_tambahan';

    protected $fillable = [
        'ptk_id',
        'jabatan_ptk',
        'prasarana',
        'nomor_sk',
        'tmt_tambahan',
        'tst_tambahan',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
