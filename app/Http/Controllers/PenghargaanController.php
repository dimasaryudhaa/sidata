<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Penghargaan;
use App\Models\Ptk;

class PenghargaanController extends Controller
{
    public function index()
    {
        $penghargaans = DB::table('ptk')
            ->leftJoin('penghargaan', 'ptk.id', '=', 'penghargaan.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(penghargaan.id) as jumlah_penghargaan')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('penghargaan.index', compact('penghargaans'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('penghargaan.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('penghargaan.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'tingkat_penghargaan' => 'required|string|max:100',
            'jenis_penghargaan' => 'required|string|max:100',
            'nama_penghargaan' => 'required|string|max:150',
            'tahun' => 'required|digits:4|integer',
            'instansi' => 'required|string|max:150',
        ]);

        Penghargaan::create($validated);

        return redirect()->route('penghargaan.index')
            ->with('success', 'Data penghargaan berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $penghargaan = Penghargaan::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('penghargaan.show', compact('penghargaan', 'ptk'));
    }

    public function edit($id)
    {
        $penghargaan = Penghargaan::findOrFail($id);
        $ptk = Ptk::findOrFail($penghargaan->ptk_id);

        return view('penghargaan.edit', compact('penghargaan', 'ptk'));
    }

    public function update(Request $request, Penghargaan $penghargaan)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'tingkat_penghargaan' => 'required|string|max:100',
            'jenis_penghargaan' => 'required|string|max:100',
            'nama_penghargaan' => 'required|string|max:150',
            'tahun' => 'required|digits:4|integer',
            'instansi' => 'required|string|max:150',
        ]);

        $penghargaan->update($validated);

        return redirect()->route('penghargaan.index')
            ->with('success', 'Data penghargaan berhasil diperbarui.');
    }

    public function destroy(Penghargaan $penghargaan)
    {
        $penghargaan->delete();

        return redirect()->route('penghargaan.index')
            ->with('success', 'Data penghargaan berhasil dihapus.');
    }
}
