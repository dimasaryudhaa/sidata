<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\Rombel;
use App\Models\Rayon;
use App\Models\Ptk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $jumlahSiswa = Siswa::count();
        $jumlahJurusan = Jurusan::count();
        $jumlahRombel = Rombel::count();
        $jumlahRayon = Rayon::count();
        $jumlahPtk = Ptk::count();

        return view('home', compact('jumlahSiswa', 'jumlahJurusan', 'jumlahRombel', 'jumlahRayon', 'jumlahPtk'));
    }
}

