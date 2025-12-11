<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;

    protected $table = 'rombel';
    protected $fillable = ['jurusan_id', 'nama_rombel', 'tingkat'];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function pesertaDidik()
    {
        return $this->hasMany(Siswa::class, 'rombel_id');
    }

}
