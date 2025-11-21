<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\Rombel;
use App\Models\Rayon;
use App\Models\Ptk;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'ptk':
                return redirect()->route('ptk.dashboard');
            case 'siswa':
                return redirect()->route('siswa.dashboard');
            default:
                abort(403);
        }
    } 

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
        $user = Auth::user();

        return view('home', [
            'role' => 'ptk',
            'user' => $user,
        ]);
    }

    public function siswa()
    {
        $user = Auth::user();

        return view('home', [
            'role' => 'siswa',
            'user' => $user,
        ]);
    }
}
