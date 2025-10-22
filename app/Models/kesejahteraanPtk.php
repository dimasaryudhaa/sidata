<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KesejahteraanPtk extends Model
{
    use HasFactory;

    protected $table = 'kesejahteraan_ptk';

    protected $fillable = [
        'ptk_id',
        'jenis_kesejahteraan',
        'nama',
        'penyelenggara',
        'dari_tahun',
        'sampai_tahun',
        'status',
    ];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }
}
