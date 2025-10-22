<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RiwayatJabatan;
use App\Models\Ptk;

class RiwayatJabatanController extends Controller
{
    public function index()
    {
        $riwayatJabatan = DB::table('ptk')
            ->leftJoin('riwayat_jabatan', 'ptk.id', '=', 'riwayat_jabatan.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(riwayat_jabatan.id) as jumlah_riwayat_jabatan')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('riwayat-jabatan.index', compact('riwayatJabatan'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('riwayat-jabatan.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('riwayat-jabatan.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_ptk' => 'required|string|max:150',
            'sk_jabatan' => 'required|string|max:100',
            'tmt_jabatan' => 'required|date',
        ]);

        RiwayatJabatan::create($validated);

        return redirect()->route('riwayat-jabatan.index')
            ->with('success', 'Data riwayat jabatan berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $riwayatJabatan = RiwayatJabatan::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('riwayat-jabatan.show', compact('riwayatJabatan', 'ptk'));
    }

    public function edit(RiwayatJabatan $riwayatJabatan)
    {
        $ptks = Ptk::orderBy('nama_lengkap')->get();
        return view('riwayat-jabatan.edit', compact('riwayatJabatan', 'ptks'));
    }

    public function update(Request $request, RiwayatJabatan $riwayatJabatan)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_ptk' => 'required|string|max:150',
            'sk_jabatan' => 'required|string|max:100',
            'tmt_jabatan' => 'required|date',
        ]);

        $riwayatJabatan->update($validated);

        return redirect()->route('riwayat-jabatan.index')
            ->with('success', 'Data riwayat jabatan berhasil diperbarui.');
    }

    public function destroy(RiwayatJabatan $riwayatJabatan)
    {
        $riwayatJabatan->delete();

        return redirect()->route('riwayat-jabatan.index')
            ->with('success', 'Data riwayat jabatan berhasil dihapus.');
    }
}
