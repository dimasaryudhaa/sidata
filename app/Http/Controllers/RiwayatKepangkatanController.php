<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatKepangkatan;
use App\Models\Ptk;

class RiwayatKepangkatanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';

        if ($isPtk) {
            $riwayatKepangkatan = DB::table('riwayat_kepangkatan')
                ->join('ptk', 'riwayat_kepangkatan.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'riwayat_kepangkatan.id as riwayat_kepangkatan_id',
                    'ptk.id as ptk_id',
                    'riwayat_kepangkatan.pangkat_golongan',
                    'riwayat_kepangkatan.nomor_sk',
                    'riwayat_kepangkatan.tanggal_sk',
                    'riwayat_kepangkatan.tmt_pangkat',
                    'riwayat_kepangkatan.masa_kerja_thn',
                    'riwayat_kepangkatan.masa_kerja_bln'
                )
                ->orderBy('riwayat_kepangkatan.tmt_pangkat', 'desc')
                ->paginate(12);

            return view('riwayat-kepangkatan.index', compact('riwayatKepangkatan', 'isPtk'));

        } else {
            $riwayatKepangkatan = DB::table('ptk')
                ->leftJoin('riwayat_kepangkatan', 'ptk.id', '=', 'riwayat_kepangkatan.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(riwayat_kepangkatan.id) as jumlah_riwayat_kepangkatan')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);

            return view('riwayat-kepangkatan.index', compact('riwayatKepangkatan', 'isPtk'));
        }
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('riwayat-kepangkatan.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('riwayat-kepangkatan.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'pangkat_golongan' => 'required|string|max:50',
            'nomor_sk' => 'required|string|max:100',
            'tanggal_sk' => 'required|date',
            'tmt_pangkat' => 'required|date',
            'masa_kerja_thn' => 'required|integer|min:0',
            'masa_kerja_bln' => 'required|integer|min:0|max:11',
        ]);

        RiwayatKepangkatan::create($validated);

        return redirect()->route('riwayat-kepangkatan.index')
            ->with('success', 'Data riwayat kepangkatan berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $riwayatKepangkatan = RiwayatKepangkatan::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('riwayat-kepangkatan.show', compact('riwayatKepangkatan', 'ptk'));
    }

    public function edit(RiwayatKepangkatan $riwayatKepangkatan)
    {
        $ptks = Ptk::orderBy('nama_lengkap')->get();
        return view('riwayat-kepangkatan.edit', compact('riwayatKepangkatan', 'ptks'));
    }

    public function update(Request $request, RiwayatKepangkatan $riwayatKepangkatan)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'pangkat_golongan' => 'required|string|max:50',
            'nomor_sk' => 'required|string|max:100',
            'tanggal_sk' => 'required|date',
            'tmt_pangkat' => 'required|date',
            'masa_kerja_thn' => 'required|integer|min:0',
            'masa_kerja_bln' => 'required|integer|min:0|max:11',
        ]);

        $riwayatKepangkatan->update($validated);

        return redirect()->route('riwayat-kepangkatan.index')
            ->with('success', 'Data riwayat kepangkatan berhasil diperbarui.');
    }

    public function destroy(RiwayatKepangkatan $riwayatKepangkatan)
    {
        $riwayatKepangkatan->delete();

        return redirect()->route('riwayat-kepangkatan.index')
            ->with('success', 'Data riwayat kepangkatan berhasil dihapus.');
    }
}
