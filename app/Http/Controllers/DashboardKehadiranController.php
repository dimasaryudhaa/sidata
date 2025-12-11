<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Models\Ptk;
use Illuminate\Support\Facades\DB;

class DashboardKehadiranController extends Controller
{
    public function index() {
        $rombels = Rombel::all();

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dataAbsen = [];

        foreach ($rombels as $rombel) {
            foreach ($bulan as $num => $namaBulan) {

                $dataAbsen[] = (object)[
                    'rombel' => $rombel->nama_rombel,
                    'bulan'  => $namaBulan,
                    'status' => null
                ];
            }
        }

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dataKehadiran = [];

        foreach (Ptk::all() as $ptk) {
            foreach ($bulan as $num => $nama) {
                $dataKehadiran[] = (object)[
                    'nama_guru' => $ptk->nama_lengkap,
                    'bulan'     => $nama,
                ];
            }
        }


        return view('dashboard-kehadiran', [
            'dataAbsen' => $dataAbsen,
            'dataKehadiran' => $dataKehadiran,
        ]);
    }
}
