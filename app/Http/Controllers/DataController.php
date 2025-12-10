<?php

namespace App\Http\Controllers;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\PrestasiSiswa;
use App\Models\Rombel;
use App\Models\Ptk;
use App\Models\Rayon;
use App\Models\Penghargaan;
use App\Models\BeasiswaSiswa;
use App\Models\BeasiswaPtk;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    public function index()
    {
        $jumlahAdmin = User::where('role', 'admin')->count();
        $jumlahSiswa = User::where('role', 'siswa')->count();
        $jumlahPtk   = User::where('role', 'ptk')->count();

        $dataPrestasi = PrestasiSiswa::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $statistikPrestasi = [];
        for ($i = 1; $i <= 12; $i++) {
            $statistikPrestasi[$i] = $dataPrestasi[$i] ?? 0;
        }

        $dataPenghargaan = Penghargaan::select(
        DB::raw('MONTH(created_at) as bulan'),
        DB::raw('COUNT(*) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $statistikPenghargaan = [];
        for ($i = 1; $i <= 12; $i++) {
            $statistikPenghargaan[$i] = $dataPenghargaan[$i] ?? 0;
        }

        $dataBeasiswa = BeasiswaSiswa::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $statistikBeasiswa = [];
        for ($i = 1; $i <= 12; $i++) {
            $statistikBeasiswa[$i] = $dataBeasiswa[$i] ?? 0;
        }

        $dataBeasiswaPtk = BeasiswaPtk::select(
        DB::raw('MONTH(created_at) as bulan'),
        DB::raw('COUNT(*) as total')
            )
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $statistikBeasiswaPtk = [];
        for ($i = 1; $i <= 12; $i++) {
            $statistikBeasiswaPtk[$i] = $dataBeasiswaPtk[$i] ?? 0;
        }

        $jurusan = Jurusan::with('rombels.pesertaDidik')->get();

        $dataJurusan = [];
        $jumlahSiswaJurusan = [];

        foreach ($jurusan as $j) {
            $total = 0;

            foreach ($j->rombels as $rombel) {
                $total += optional($rombel->pesertaDidik)->count() ?? 0;
            }

            $dataJurusan[] = $j->nama_jurusan;
            $jumlahSiswaJurusan[] = $total;
        }

        $rayon = Rayon::withCount('siswa')->get();

        $namaRayon = $rayon->pluck('nama_rayon');
        $jumlahSiswaRayon = $rayon->pluck('siswa_count');

        return view('data', [
            'jumlahSiswa' => Siswa::count(),
            'jumlahJurusan' => Jurusan::count(),
            'jumlahRombel' => Rombel::count(),
            'jumlahRayon' => Rayon::count(),
            'jumlahPtk' => Ptk::count(),
            'jumlahGuru' => Ptk::where('jenis_ptk', 'Guru')->count(),
            'jumlahStaf' => Ptk::where('jenis_ptk', 'Staf')->count(),
            'jumlahLaboran' => Ptk::where('jenis_ptk', 'Laboran')->count(),

            'jumlahPrestasiSiswa' => PrestasiSiswa::count(),

            'statistikPrestasi' => $statistikPrestasi,
            'statistikPenghargaan' => $statistikPenghargaan,
            'statistikBeasiswa' => $statistikBeasiswa,
            'statistikBeasiswaPtk' => $statistikBeasiswaPtk,
            'jumlahAdmin' => $jumlahAdmin,
            'jumlahSiswa' => $jumlahSiswa,
            'jumlahPtk'   => $jumlahPtk,
            'dataJurusan' => $dataJurusan,
            'jumlahSiswaJurusan' => $jumlahSiswaJurusan,
            'namaRayon' => $namaRayon,
            'jumlahSiswaRayon' => $jumlahSiswaRayon,

        ]);
    }
}
