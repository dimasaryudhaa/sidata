<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ptk;

class Rayon extends Model
{
    use HasFactory;

    protected $table = 'rayon';
    protected $fillable = ['ptk_id', 'nama_rayon'];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }
}
