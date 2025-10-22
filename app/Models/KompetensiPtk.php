<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiPtk extends Model
{
    use HasFactory;

    protected $table = 'kompetensi';

    protected $fillable = [
        'ptk_id',
        'bidang_studi',
        'urutan',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
