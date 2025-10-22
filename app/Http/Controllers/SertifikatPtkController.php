<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SertifikatPtk;
use App\Models\Ptk;
use Illuminate\Support\Facades\DB;

class SertifikatPtkController extends Controller
{
    public function index()
    {
        $sertifikatPtk = DB::table('ptk')
            ->leftJoin('sertifikat', 'ptk.id', '=', 'sertifikat.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(sertifikat.id) as jumlah_sertifikat')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('sertifikat-ptk.index', compact('sertifikatPtk'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('sertifikat-ptk.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('sertifikat-ptk.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_sertifikasi' => 'required|string|max:255',
            'nomor_sertifikat' => 'required|string|max:100',
            'tahun_sertifikasi' => 'required|digits:4|integer',
            'bidang_studi' => 'required|string|max:255',
            'nrg' => 'required|string|max:50',
            'nomor_peserta' => 'required|string|max:100',
        ]);

        SertifikatPtk::create($validated);

        return redirect()->route('sertifikat-ptk.index')
            ->with('success', 'Data sertifikat PTK berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $sertifikat = SertifikatPtk::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('sertifikat-ptk.show', compact('sertifikat', 'ptk'));
    }

    public function edit($id)
    {
        $sertifikatPtk = SertifikatPtk::findOrFail($id);
        $ptk = Ptk::findOrFail($sertifikatPtk->ptk_id);

        return view('sertifikat-ptk.edit', compact('sertifikatPtk', 'ptk'));
    }


    public function update(Request $request, SertifikatPtk $sertifikatPtk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_sertifikasi' => 'required|string|max:255',
            'nomor_sertifikat' => 'required|string|max:100',
            'tahun_sertifikasi' => 'required|digits:4|integer',
            'bidang_studi' => 'required|string|max:255',
            'nrg' => 'required|string|max:50',
            'nomor_peserta' => 'required|string|max:100',
        ]);

        $sertifikatPtk->update($validated);

        return redirect()->route('sertifikat-ptk.index')
            ->with('success', 'Data sertifikat PTK berhasil diperbarui.');
    }

    public function destroy(SertifikatPtk $sertifikatPtk)
    {
        $sertifikatPtk->delete();

        return redirect()->route('sertifikat-ptk.index')
            ->with('success', 'Data sertifikat PTK berhasil dihapus.');
    }
}
