<?php

namespace App\Http\Controllers;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\PrestasiSiswa;
use App\Models\Rombel;
use App\Models\Ptk;
use App\Models\Rayon;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function index()
    {
        // Ambil data prestasi per bulan
        $dataPrestasi = PrestasiSiswa::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Buat array 12 bulan
        $statistikPrestasi = [];
        for ($i = 1; $i <= 12; $i++) {
            $statistikPrestasi[$i] = $dataPrestasi[$i] ?? 0;
        }

        return view('data', [
            'jumlahSiswa' => Siswa::count(),
            'jumlahJurusan' => Jurusan::count(),
            'jumlahRombel' => Rombel::count(),
            'jumlahRayon' => Rayon::count(),
            'jumlahPtk' => Ptk::count(),
            'jumlahPrestasiSiswa' => PrestasiSiswa::count(),

            // kirim data lengkap 12 bulan
            'statistikPrestasi' => $statistikPrestasi,
        ]);
    }
}
