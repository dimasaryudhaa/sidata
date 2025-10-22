<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TugasTambahan;
use App\Models\Ptk;

class TugasTambahanController extends Controller
{
    public function index()
    {
        $tugasTambahan = DB::table('ptk')
            ->leftJoin('tugas_tambahan', 'ptk.id', '=', 'tugas_tambahan.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(tugas_tambahan.id) as jumlah_tugas_tambahan')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('tugas-tambahan.index', compact('tugasTambahan'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('tugas-tambahan.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('tugas-tambahan.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_ptk' => 'required|string|max:100',
            'prasarana' => 'nullable|string|max:100',
            'nomor_sk' => 'required|string|min:10|max:20',
            'tmt_tambahan' => 'required|date',
            'tst_tambahan' => 'nullable|date',
        ]);

        TugasTambahan::create($validated);

        return redirect()->route('tugas-tambahan.index')
            ->with('success', 'Data tugas tambahan berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $tugasTambahan = TugasTambahan::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('tugas-tambahan.show', compact('tugasTambahan', 'ptk'));
    }

    public function edit(TugasTambahan $tugasTambahan)
    {
        $ptks = Ptk::orderBy('nama_lengkap')->get();
        return view('tugas-tambahan.edit', compact('tugasTambahan', 'ptks'));
    }

    public function update(Request $request, TugasTambahan $tugasTambahan)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_ptk' => 'required|string|max:100',
            'prasarana' => 'nullable|string|max:100',
            'nomor_sk' => 'required|string|min:10|max:20',
            'tmt_tambahan' => 'required|date',
            'tst_tambahan' => 'nullable|date',
        ]);

        $tugasTambahan->update($validated);

        return redirect()->route('tugas-tambahan.index')
            ->with('success', 'Data tugas tambahan berhasil diperbarui.');
    }

    public function destroy(TugasTambahan $tugasTambahan)
    {
        $tugasTambahan->delete();

        return redirect()->route('tugas-tambahan.index')
            ->with('success', 'Data tugas tambahan berhasil dihapus.');
    }
}
