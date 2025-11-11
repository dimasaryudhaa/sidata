<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatGaji;
use App\Models\Ptk;

class RiwayatGajiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';

        if ($isPtk) {
            $riwayatGaji = DB::table('riwayat_gaji')
                ->join('ptk', 'riwayat_gaji.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'riwayat_gaji.id as riwayat_gaji_id',
                    'ptk.id as ptk_id',
                    'riwayat_gaji.pangkat_golongan',
                    'riwayat_gaji.nomor_sk',
                    'riwayat_gaji.tanggal_sk',
                    'riwayat_gaji.tmt_kgb',
                    'riwayat_gaji.masa_kerja_thn',
                    'riwayat_gaji.masa_kerja_bln',
                    'riwayat_gaji.gaji_pokok'
                )
                ->orderBy('riwayat_gaji.tanggal_sk', 'desc')
                ->paginate(12);

            return view('riwayat-gaji.index', compact('riwayatGaji', 'isPtk'));

        } else {
            $riwayatGaji = DB::table('ptk')
                ->leftJoin('riwayat_gaji', 'ptk.id', '=', 'riwayat_gaji.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(riwayat_gaji.id) as jumlah_riwayat_gaji')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);

            return view('riwayat-gaji.index', compact('riwayatGaji', 'isPtk'));
        }
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('riwayat-gaji.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('riwayat-gaji.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'pangkat_golongan' => 'required|string|max:50',
            'nomor_sk' => 'required|string|max:50',
            'tanggal_sk' => 'required|date',
            'tmt_kgb' => 'required|date',
            'masa_kerja_thn' => 'required|integer|min:0',
            'masa_kerja_bln' => 'required|integer|min:0|max:11',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);

        RiwayatGaji::create($validated);

        return redirect()->route('riwayat-gaji.index')
            ->with('success', 'Data riwayat gaji berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $riwayatGaji = RiwayatGaji::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('riwayat-gaji.show', compact('riwayatGaji', 'ptk'));
    }

    public function edit(RiwayatGaji $riwayatGaji)
    {
        $ptks = Ptk::orderBy('nama_lengkap')->get();
        return view('riwayat-gaji.edit', compact('riwayatGaji', 'ptks'));
    }

    public function update(Request $request, RiwayatGaji $riwayatGaji)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'pangkat_golongan' => 'required|string|max:50',
            'nomor_sk' => 'required|string|max:50',
            'tanggal_sk' => 'required|date',
            'tmt_kgb' => 'required|date',
            'masa_kerja_thn' => 'required|integer|min:0',
            'masa_kerja_bln' => 'required|integer|min:0|max:11',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);

        $riwayatGaji->update($validated);

        return redirect()->route('riwayat-gaji.index')
            ->with('success', 'Data riwayat gaji berhasil diperbarui.');
    }

    public function destroy(RiwayatGaji $riwayatGaji)
    {
        $riwayatGaji->delete();

        return redirect()->route('riwayat-gaji.index')
            ->with('success', 'Data riwayat gaji berhasil dihapus.');
    }
}
