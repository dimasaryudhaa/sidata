<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunPtk extends Model
{
    use HasFactory;

    protected $table = 'akun_ptk';

    protected $fillable = [
        'ptk_id',
        'email',
        'password',
    ];

    protected $hidden = ['password']; 

    public function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }
}
