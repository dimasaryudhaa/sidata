<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendidikanPtk;
use App\Models\Ptk;
use Illuminate\Support\Facades\DB;

class PendidikanPtkController extends Controller
{
    public function index()
    {
        $pendidikanPtk = DB::table('ptk')
            ->leftJoin('pendidikan_ptk', 'ptk.id', '=', 'pendidikan_ptk.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(pendidikan_ptk.id) as jumlah_pendidikan')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('pendidikan-ptk.index', compact('pendidikanPtk'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('pendidikan-ptk.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('pendidikan-ptk.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'bidang_studi' => 'required|string|max:255',
            'jenjang_pendidikan' => 'required|string|max:100',
            'gelar_akademik' => 'required|string|max:100',
            'satuan_pendidikan_formal' => 'required|string|max:255',
            'fakultas' => 'required|string|max:255',
            'kependidikan' => 'required|in:Ya,Tidak',
            'tahun_masuk' => 'required|digits:4',
            'tahun_lulus' => 'required|digits:4',
            'nomor_induk' => 'required|string|max:50',
            'masih_studi' => 'required|boolean',
            'semester' => 'required|integer|min:1',
            'rata_rata_ujian' => 'required|numeric|between:0,100',
        ]);

        PendidikanPtk::create($validated);

        return redirect()->route('pendidikan-ptk.index')->with('success', 'Data pendidikan PTK berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $pendidikan = PendidikanPtk::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('pendidikan-ptk.show', compact('pendidikan', 'ptk'));
    }

    public function edit($id)
    {
        $pendidikanPtk = PendidikanPtk::findOrFail($id);
        $ptk = Ptk::findOrFail($pendidikanPtk->ptk_id);

        return view('pendidikan-ptk.edit', compact('pendidikanPtk', 'ptk'));
    }


    public function update(Request $request, PendidikanPtk $pendidikanPtk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'bidang_studi' => 'required|string|max:255',
            'jenjang_pendidikan' => 'required|string|max:100',
            'gelar_akademik' => 'required|string|max:100',
            'satuan_pendidikan_formal' => 'required|string|max:255',
            'fakultas' => 'required|string|max:255',
            'kependidikan' => 'required|in:Ya,Tidak',
            'tahun_masuk' => 'required|digits:4',
            'tahun_lulus' => 'required|digits:4',
            'nomor_induk' => 'required|string|max:50',
            'masih_studi' => 'required|boolean',
            'semester' => 'required|integer|min:1',
            'rata_rata_ujian' => 'required|numeric|between:0,100',
        ]);

        $pendidikanPtk->update($validated);

        return redirect()->route('pendidikan-ptk.index')->with('success', 'Data pendidikan PTK berhasil diperbarui.');
    }

    public function destroy(PendidikanPtk $pendidikanPtk)
    {
        $pendidikanPtk->delete();
        return redirect()->route('pendidikan-ptk.index')->with('success', 'Data pendidikan PTK berhasil dihapus.');
    }
}
