<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NilaiTest;
use App\Models\Ptk;

class NilaiTestController extends Controller
{
    public function index()
    {
        $nilaiTest = DB::table('ptk')
            ->leftJoin('nilai_test', 'ptk.id', '=', 'nilai_test.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(nilai_test.id) as jumlah_nilai_test')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('nilai-test.index', compact('nilaiTest'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('nilai-test.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('nilai-test.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_test' => 'required|string|max:150',
            'nama_test' => 'required|string|max:150',
            'penyelenggara' => 'required|string|max:150',
            'tahun' => 'required|digits:4|integer',
            'skor' => 'nullable|numeric',
            'nomor_peserta' => 'nullable|string|max:50',
        ]);

        NilaiTest::create($validated);

        return redirect()->route('nilai-test.index')
            ->with('success', 'Data nilai test berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $nilaiTest = NilaiTest::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('nilai-test.show', compact('nilaiTest', 'ptk'));
    }

    public function edit($id)
    {
        $nilaiTest = NilaiTest::findOrFail($id);
        $ptk = Ptk::findOrFail($nilaiTest->ptk_id);

        return view('nilai-test.edit', compact('nilaiTest', 'ptk'));
    }

    public function update(Request $request, NilaiTest $nilaiTest)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_test' => 'required|string|max:150',
            'nama_test' => 'required|string|max:150',
            'penyelenggara' => 'required|string|max:150',
            'tahun' => 'required|digits:4|integer',
            'skor' => 'nullable|numeric',
            'nomor_peserta' => 'nullable|string|max:50',
        ]);

        $nilaiTest->update($validated);

        return redirect()->route('nilai-test.index')
            ->with('success', 'Data nilai test berhasil diperbarui.');
    }

    public function destroy(NilaiTest $nilaiTest)
    {
        $nilaiTest->delete();

        return redirect()->route('nilai-test.index')
            ->with('success', 'Data nilai test berhasil dihapus.');
    }
}
