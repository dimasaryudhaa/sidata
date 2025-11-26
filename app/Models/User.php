<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'peserta_didik_id');
    }

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }

    public function akunSiswa()
    {
        return $this->hasOne(AkunSiswa::class, 'email', 'email');
    }

    public function akunPtk()
    {
        return $this->hasOne(AkunPtk::class, 'email', 'email');
    }


}
