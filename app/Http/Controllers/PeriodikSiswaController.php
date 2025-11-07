<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodikSiswa;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriodikSiswaController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';

        if ($isSiswa) {
            $periodik = DB::table('data_periodik')
                ->join('akun_siswa', 'data_periodik.peserta_didik_id', '=', 'akun_siswa.peserta_didik_id')
                ->join('peserta_didik', 'data_periodik.peserta_didik_id', '=', 'peserta_didik.id')
                ->where('akun_siswa.email', $user->email)
                ->select(
                    'data_periodik.id',
                    'peserta_didik.nama_lengkap',
                    'data_periodik.tinggi_badan_cm',
                    'data_periodik.berat_badan_kg',
                    'data_periodik.lingkar_kepala_cm',
                    'data_periodik.jarak_ke_sekolah',
                    'data_periodik.jarak_sebenarnya_km',
                    'data_periodik.waktu_tempuh_jam',
                    'data_periodik.waktu_tempuh_menit',
                    'data_periodik.jumlah_saudara'
                )
                ->paginate(12);

            return view('periodik.index', compact('periodik', 'isSiswa'));
        } else {
            $query = DB::table('peserta_didik')
                ->leftJoin('data_periodik', 'peserta_didik.id', '=', 'data_periodik.peserta_didik_id')
                ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
                ->select(
                    'peserta_didik.id as siswa_id',
                    'peserta_didik.rombel_id',
                    'peserta_didik.nama_lengkap',
                    'peserta_didik.rombel_id',
                    'data_periodik.id as periodik_id',
                    'data_periodik.tinggi_badan_cm',
                    'data_periodik.berat_badan_kg',
                    'data_periodik.lingkar_kepala_cm',
                    'data_periodik.jarak_sebenarnya_km'
                )
                ->orderBy('peserta_didik.nama_lengkap', 'asc');

            $periodik = $query->paginate(12);
            $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

            return view('periodik.index', compact('periodik', 'rombels', 'isSiswa'));
        }
    }

    public function create()
    {
        $siswa = Siswa::all();
        return view('periodik.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'tinggi_badan_cm' => 'nullable|numeric',
            'berat_badan_kg' => 'nullable|numeric',
            'lingkar_kepala_cm' => 'nullable|numeric',
            'jarak_ke_sekolah' => 'nullable|in:Kurang dari 1 km,Lebih dari 1 km',
            'jarak_sebenarnya_km' => 'nullable|numeric',
            'waktu_tempuh_jam' => 'nullable|integer',
            'waktu_tempuh_menit' => 'nullable|integer',
            'jumlah_saudara' => 'nullable|integer',
        ]);

        PeriodikSiswa::create($request->all());

        return redirect()->route('periodik.index')->with('success', 'Data periodik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $periodik = PeriodikSiswa::find($id);

        if (!$periodik) {
            $siswa = Siswa::findOrFail($id);
            $existing = PeriodikSiswa::where('peserta_didik_id', $siswa->id)->first();

            if ($existing) {
                $periodik = $existing;

            } else {
                $periodik = new PeriodikSiswa();
                $periodik->peserta_didik_id = $siswa->id;
            }

        } else {
            $siswa = Siswa::find($periodik->peserta_didik_id);
        }

        $semuaSiswa = Siswa::all();

        return view('periodik.edit', [
            'periodik' => $periodik,
            'siswa' => $semuaSiswa,
        ]);
    }

    public function update(Request $request, PeriodikSiswa $periodik)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'tinggi_badan_cm' => 'nullable|numeric',
            'berat_badan_kg' => 'nullable|numeric',
            'lingkar_kepala_cm' => 'nullable|numeric',
            'jarak_ke_sekolah' => 'nullable|in:Kurang dari 1 km,Lebih dari 1 km',
            'jarak_sebenarnya_km' => 'nullable|numeric',
            'waktu_tempuh_jam' => 'nullable|integer',
            'waktu_tempuh_menit' => 'nullable|integer',
            'jumlah_saudara' => 'nullable|integer',
        ]);

        $periodik->update($request->all());

        return redirect()->route('periodik.index')->with('success', 'Data periodik berhasil diperbarui.');
    }

    public function destroy($id)
    {

        $periodik = PeriodikSiswa::findOrFail($id);
        PeriodikSiswa::where('peserta_didik_id', $periodik->peserta_didik_id)->delete();

        return redirect()->route('periodik.index')->with('success', 'Semua data periodik siswa berhasil dihapus.');
    }

}
