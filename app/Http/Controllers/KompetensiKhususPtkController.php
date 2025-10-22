<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KompetensiKhususPtk;
use App\Models\Ptk;

class KompetensiKhususPtkController extends Controller
{
    public function index()
    {
        $kompetensiKhusus = DB::table('ptk')
            ->leftJoin('kompetensi_khusus', 'ptk.id', '=', 'kompetensi_khusus.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(kompetensi_khusus.id) as jumlah_kompetensi_khusus')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('kompetensi-khusus-ptk.index', compact('kompetensiKhusus'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('kompetensi-khusus-ptk.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('kompetensi-khusus-ptk.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'punya_lisensi_kepala_sekolah' => 'nullable|boolean',
            'nomor_unik_kepala_sekolah' => 'nullable|string|max:50',
            'keahlian_lab_oratorium' => 'nullable|string|max:255',
            'mampu_menangani' => 'nullable|in:Tidak,Netra (A),Rungu (B),Grahita Sedang (C1),Grahita Ringan (D),Daksa Sedang (D1),Laras,Daksa Ringan,Wicara,Tuna Ganda,Hiper Aktif (H),Cerdas Istimewa (I),Bakat Istimewa (J),Kesulitan Belajar (K),Narkoba (N),Indigo (O),Down Sindrome (P),Autis (Q)',
            'keahlian_braile' => 'nullable|boolean',
            'keahlian_bahasa_isyarat' => 'nullable|boolean',
        ]);

        KompetensiKhususPtk::create($validated);

        return redirect()->route('kompetensi-khusus-ptk.index')
            ->with('success', 'Data kompetensi khusus PTK berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $kompetensiKhusus = KompetensiKhususPtk::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('kompetensi-khusus-ptk.show', compact('kompetensiKhusus', 'ptk'));
    }

    public function edit($id)
    {
        $kompetensiKhusus = KompetensiKhususPtk::findOrFail($id);
        $ptk = Ptk::findOrFail($kompetensiKhusus->ptk_id);

        return view('kompetensi-khusus-ptk.edit', compact('kompetensiKhusus', 'ptk'));
    }

    public function update(Request $request, KompetensiKhususPtk $kompetensiKhususPtk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'punya_lisensi_kepala_sekolah' => 'nullable|boolean',
            'nomor_unik_kepala_sekolah' => 'nullable|string|max:50',
            'keahlian_lab_oratorium' => 'nullable|string|max:255',
            'mampu_menangani' => 'nullable|in:Tidak,Netra (A),Rungu (B),Grahita Sedang (C1),Grahita Ringan (D),Daksa Sedang (D1),Laras,Daksa Ringan,Wicara,Tuna Ganda,Hiper Aktif (H),Cerdas Istimewa (I),Bakat Istimewa (J),Kesulitan Belajar (K),Narkoba (N),Indigo (O),Down Sindrome (P),Autis (Q)',
            'keahlian_braile' => 'nullable|boolean',
            'keahlian_bahasa_isyarat' => 'nullable|boolean',
        ]);

        $kompetensiKhususPtk->update($validated);

        return redirect()->route('kompetensi-khusus-ptk.index')
            ->with('success', 'Data kompetensi khusus PTK berhasil diperbarui.');
    }

    public function destroy(KompetensiKhususPtk $kompetensiKhususPtk)
    {
        $kompetensiKhususPtk->delete();

        return redirect()->route('kompetensi-khusus-ptk.index')
            ->with('success', 'Data kompetensi khusus PTK berhasil dihapus.');
    }
}
