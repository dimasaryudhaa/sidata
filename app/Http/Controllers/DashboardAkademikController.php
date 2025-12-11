<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Semester;
use App\Models\Rombel;
use App\Models\Rayon;
use App\Models\Siswa;
use App\Models\BeasiswaSiswa;
use App\Models\PrestasiSiswa;

class DashboardAkademikController extends Controller
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

        $rombelList = Rombel::all();
        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $dataRataRata = [];

        foreach ($rombelList as $rombel) {
            foreach ($bulanList as $bulanNum => $namaBulan) {

                $dataRataRata[] = (object)[
                    'rombel' => $rombel->nama_rombel,
                    'bulan' => $namaBulan,
                    'nilai_rata2' => null
                ];
            }
        }

        $rombels = Rombel::with('jurusan')->get();
        $mingguList = range(1, 9);

        $dataGrafik = [];

        foreach ($rombels as $rombel) {
            foreach ($mingguList as $minggu) {
                $dataGrafik[] = (object)[
                    'rombel'      => $rombel->nama_rombel,
                    'jurusan'     => $rombel->jurusan->nama_jurusan ?? '-',
                    'tingkat'     => $rombel->tingkat ?? '-',
                    'minggu'      => $minggu,
                    'nilai'       => null,
                    'ketuntasan'  => null
                ];
            }
        }

        $semesterList = Semester::orderBy('id')->get();
        $rombels = Rombel::all();

        $chartLabels = $semesterList->pluck('nama_semester');

        $datasets = [];

        foreach ($rombels as $rombel) {

            $nilaiPerSemester = [];

            foreach ($semesterList as $s) {

                $nilaiPerSemester[] = null;
            }

            $datasets[] = [
                'label' => $rombel->nama_rombel,
                'data' => $nilaiPerSemester,
            ];
        }

        $rombels = Rombel::all();

        $labelsMasalah = [];
        $dataMasalah = [];

        foreach ($rombels as $r) {

            $jumlahMasalah = 0;

            $labelsMasalah[] = $r->nama_rombel;
            $dataMasalah[] = $jumlahMasalah;
        }


        return view('dashboard-akademik', [
            'rombelLabels' => $labels,
            'rombelPrestasi' => $data,
            'prestasiPerRayon' => $prestasiPerRayon,
            'pieLabels' => $piePrestasi->pluck('jenis_prestasi'),
            'pieValues' => $piePrestasi->pluck('total'),
            'tingkatLabels' => $pieTingkat->pluck('tingkat_prestasi'),
            'tingkatValues' => $pieTingkat->pluck('total'),
            'beasiswaLabels' => $pieBeasiswa->pluck('jenis_beasiswa'),
            'beasiswaValues' => $pieBeasiswa->pluck('total'),
            'dataRataRata' => $dataRataRata,
            'dataGrafik' => $dataGrafik,
            'chartLabels' => $chartLabels,
            'datasets' => $datasets,
            'labelsMasalah' => $labelsMasalah,
            'dataMasalah' => $dataMasalah,
        ]);
    }
}
