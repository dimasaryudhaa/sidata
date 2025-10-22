<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BeasiswaPtk;
use App\Models\Ptk;

class BeasiswaPtkController extends Controller
{
    public function index()
    {
        $beasiswaPtk = DB::table('ptk')
            ->leftJoin('beasiswa_ptk', 'ptk.id', '=', 'beasiswa_ptk.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(beasiswa_ptk.id) as jumlah_beasiswa')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('beasiswa-ptk.index', compact('beasiswaPtk'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('beasiswa-ptk.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('beasiswa-ptk.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_beasiswa' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'tahun_mulai' => 'required|digits:4|integer',
            'tahun_akhir' => 'required|digits:4|integer',
            'masih_menerima' => 'nullable|boolean',
        ]);

        BeasiswaPtk::create($validated);

        return redirect()->route('beasiswa-ptk.index')
            ->with('success', 'Data beasiswa PTK berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $beasiswa = BeasiswaPtk::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('beasiswa-ptk.show', compact('beasiswa', 'ptk'));
    }

    public function edit($id)
    {
        $beasiswaPtk = BeasiswaPtk::findOrFail($id);
        $ptk = Ptk::findOrFail($beasiswaPtk->ptk_id);

        return view('beasiswa-ptk.edit', compact('beasiswaPtk', 'ptk'));
    }

    public function update(Request $request, BeasiswaPtk $beasiswaPtk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_beasiswa' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'tahun_mulai' => 'required|digits:4|integer',
            'tahun_akhir' => 'required|digits:4|integer',
            'masih_menerima' => 'nullable|boolean',
        ]);

        $beasiswaPtk->update($validated);

        return redirect()->route('beasiswa-ptk.index')
            ->with('success', 'Data beasiswa PTK berhasil diperbarui.');
    }

    public function destroy(BeasiswaPtk $beasiswaPtk)
    {
        $beasiswaPtk->delete();

        return redirect()->route('beasiswa-ptk.index')
            ->with('success', 'Data beasiswa PTK berhasil dihapus.');
    }
}
