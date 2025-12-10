<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Models\Rayon;
use App\Models\BeasiswaSiswa;
use App\Models\PrestasiSiswa;

class DataAkademikController extends Controller
{
    public function index() {
        $rombel = Rombel::all();

        $labels = [];
        $data = [];

        foreach ($rombel as $r) {

            $jumlahPrestasi = PrestasiSiswa::whereHas('siswa', function ($q) use ($r) {
                $q->where('rombel_id', $r->id);
            })->count();

            $labels[] = $r->nama_rombel;
            $data[] = $jumlahPrestasi;
        }

        $prestasiPerRayon = Rayon::withCount([
            'siswa as jumlah_prestasi' => function ($query) {
                $query->join('prestasi', 'peserta_didik.id', '=', 'prestasi.peserta_didik_id');
            }
        ])->get();

        $piePrestasi = PrestasiSiswa::selectRaw('jenis_prestasi, COUNT(*) as total')
        ->groupBy('jenis_prestasi')
        ->get();

        $pieTingkat = PrestasiSiswa::selectRaw('tingkat_prestasi, COUNT(*) as total')
        ->groupBy('tingkat_prestasi')
        ->get();

        $pieBeasiswa = BeasiswaSiswa::selectRaw('jenis_beasiswa, COUNT(*) as total')
        ->groupBy('jenis_beasiswa')
        ->get();

        return view('data-akademik', [
            'rombelLabels' => $labels,
            'rombelPrestasi' => $data,
            'prestasiPerRayon' => $prestasiPerRayon,
            'pieLabels' => $piePrestasi->pluck('jenis_prestasi'),
            'pieValues' => $piePrestasi->pluck('total'),
            'tingkatLabels' => $pieTingkat->pluck('tingkat_prestasi'),
            'tingkatValues' => $pieTingkat->pluck('total'),
            'beasiswaLabels' => $pieBeasiswa->pluck('jenis_beasiswa'),
            'beasiswaValues' => $pieBeasiswa->pluck('total'),
        ]);
    }
}
