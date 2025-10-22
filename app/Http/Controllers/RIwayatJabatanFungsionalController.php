<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ptk;
use App\Models\RiwayatJabatanFungsional;

class RiwayatJabatanFungsionalController extends Controller
{
    public function index()
    {
        $riwayatJabatanFungsional = DB::table('ptk')
            ->leftJoin('riwayat_jabatan_fungsional', 'ptk.id', '=', 'riwayat_jabatan_fungsional.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(riwayat_jabatan_fungsional.id) as jumlah_riwayat_jabfung')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('riwayat-jabatan-fungsional.index', compact('riwayatJabatanFungsional'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('riwayat-jabatan-fungsional.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('riwayat-jabatan-fungsional.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_fungsional' => 'required|string|max:100',
            'sk_jabfung' => 'required|string|min:10|max:50',
            'tmt_jabatan' => 'required|date',
        ]);

        RiwayatJabatanFungsional::create($validated);

        return redirect()->route('riwayat-jabatan-fungsional.index')
            ->with('success', 'Data riwayat jabatan fungsional berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $ptk = Ptk::findOrFail($ptk_id);
        $riwayatJabatanFungsional = RiwayatJabatanFungsional::where('ptk_id', $ptk_id)->get();

        return view('riwayat-jabatan-fungsional.show', compact('ptk', 'riwayatJabatanFungsional'));
    }

    public function edit(RiwayatJabatanFungsional $riwayatJabatanFungsional)
    {
        $ptks = Ptk::orderBy('nama_lengkap')->get();
        return view('riwayat-jabatan-fungsional.edit', compact('riwayatJabatanFungsional', 'ptks'));
    }

    public function update(Request $request, RiwayatJabatanFungsional $riwayatJabatanFungsional)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_fungsional' => 'required|string|max:100',
            'sk_jabfung' => 'required|string|min:10|max:50',
            'tmt_jabatan' => 'required|date',
        ]);

        $riwayatJabatanFungsional->update($validated);

        return redirect()->route('riwayat-jabatan-fungsional.index')
            ->with('success', 'Data riwayat jabatan fungsional berhasil diperbarui.');
    }

    public function destroy(RiwayatJabatanFungsional $riwayatJabatanFungsional)
    {
        $riwayatJabatanFungsional->delete();

        return redirect()->route('riwayat-jabatan-fungsional.index')
            ->with('success', 'Data riwayat jabatan fungsional berhasil dihapus.');
    }
}
