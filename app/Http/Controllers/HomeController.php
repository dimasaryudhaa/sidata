<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\Rombel;
use App\Models\Rayon;
use App\Models\Ptk;

class HomeController extends Controller
{

    public function admin()
    {
        return view('home', [
            'role' => 'admin',
            'jumlahSiswa' => Siswa::count(),
            'jumlahJurusan' => Jurusan::count(),
            'jumlahRombel' => Rombel::count(),
            'jumlahRayon' => Rayon::count(),
            'jumlahPtk' => Ptk::count(),
        ]);
    }

    public function ptk()
    {
        return view('home', [
            'role' => 'ptk',
            'jumlahSiswa' => Siswa::count(),
            'jumlahJurusan' => Jurusan::count(),
            'jumlahRombel' => Rombel::count(),
            'jumlahRayon' => Rayon::count(),
            'jumlahPtk' => Ptk::count(),
        ]);
    }

    public function siswa()
    {
        return view('home', [
            'role' => 'siswa',
            'jumlahSiswa' => Siswa::count(),
            'jumlahJurusan' => Jurusan::count(),
            'jumlahRombel' => Rombel::count(),
            'jumlahRayon' => Rayon::count(),
            'jumlahPtk' => Ptk::count(),
        ]);
    }
}
