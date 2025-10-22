<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;

    protected $table = 'rombel';
    protected $fillable = ['jurusan_id', 'nama_rombel'];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
