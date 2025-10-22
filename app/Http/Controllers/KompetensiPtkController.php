<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KompetensiPtk;
use App\Models\Ptk;

class KompetensiPtkController extends Controller
{
    public function index()
    {
        $kompetensis = DB::table('ptk')
            ->leftJoin('kompetensi', 'ptk.id', '=', 'kompetensi.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(kompetensi.id) as jumlah_kompetensi')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('kompetensi-ptk.index', compact('kompetensis'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('kompetensi-ptk.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('kompetensi-ptk.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'bidang_studi' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
        ]);

        KompetensiPtk::create($validated);

        return redirect()->route('kompetensi-ptk.index')
            ->with('success', 'Data kompetensi PTK berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $kompetensi = KompetensiPtk::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('kompetensi-ptk.show', compact('kompetensi', 'ptk'));
    }

    public function edit($id)
    {
        $kompetensi = KompetensiPtk::findOrFail($id);
        $ptk = Ptk::findOrFail($kompetensi->ptk_id);

        return view('kompetensi-ptk.edit', compact('kompetensi', 'ptk'));
    }

    public function update(Request $request, KompetensiPtk $kompetensiPtk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'bidang_studi' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
        ]);

        $kompetensiPtk->update($validated);

        return redirect()->route('kompetensi-ptk.index')
            ->with('success', 'Data kompetensi PTK berhasil diperbarui.');
    }

    public function destroy(KompetensiPtk $kompetensiPtk)
    {
        $kompetensiPtk->delete();

        return redirect()->route('kompetensi-ptk.index')
            ->with('success', 'Data kompetensi PTK berhasil dihapus.');
    }
}
