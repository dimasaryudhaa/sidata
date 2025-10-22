<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tunjangan;
use App\Models\Ptk;
use App\Models\Semester;

class TunjanganController extends Controller
{
    public function index()
    {
        $tunjangan = DB::table('ptk')
            ->leftJoin('tunjangan', 'ptk.id', '=', 'tunjangan.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(tunjangan.id) as jumlah_tunjangan')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('tunjangan.index', compact('tunjangan'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        $semesters = Semester::orderBy('nama_semester')->get();

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('tunjangan.create', compact('ptkId', 'ptk', 'semesters'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('tunjangan.create', compact('ptks', 'semesters'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_tunjangan' => 'required|string|max:100',
            'nama_tunjangan' => 'required|string|max:150',
            'instansi' => 'nullable|string|max:150',
            'sk_tunjangan' => 'nullable|string|max:100',
            'tgl_sk_tunjangan' => 'nullable|date',
            'semester_id' => 'required|exists:semester,id',
            'sumber_dana' => 'nullable|string|max:100',
            'dari_tahun' => 'nullable|integer|min:1900|max:2100',
            'sampai_tahun' => 'nullable|integer|min:1900|max:2100',
            'nominal' => 'nullable|numeric|min:0',
            'status' => 'required|in:Masih Menerima,Tidak Menerima',
        ]);

        Tunjangan::create($validated);

        return redirect()->route('tunjangan.index')
            ->with('success', 'Data tunjangan berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $tunjangan = Tunjangan::where('ptk_id', $ptk_id)->with('semester')->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('tunjangan.show', compact('tunjangan', 'ptk'));
    }

    public function edit(Tunjangan $tunjangan)
    {
        $ptks = Ptk::orderBy('nama_lengkap')->get();
        $semesters = Semester::orderBy('nama_semester')->get();

        return view('tunjangan.edit', compact('tunjangan', 'ptks', 'semesters'));
    }

    public function update(Request $request, Tunjangan $tunjangan)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_tunjangan' => 'required|string|max:100',
            'nama_tunjangan' => 'required|string|max:150',
            'instansi' => 'nullable|string|max:150',
            'sk_tunjangan' => 'nullable|string|max:100',
            'tgl_sk_tunjangan' => 'nullable|date',
            'semester_id' => 'required|exists:semester,id',
            'sumber_dana' => 'nullable|string|max:100',
            'dari_tahun' => 'nullable|integer|min:1900|max:2100',
            'sampai_tahun' => 'nullable|integer|min:1900|max:2100',
            'nominal' => 'nullable|numeric|min:0',
            'status' => 'required|in:Masih Menerima,Tidak Menerima',
        ]);

        $tunjangan->update($validated);

        return redirect()->route('tunjangan.index')
            ->with('success', 'Data tunjangan berhasil diperbarui.');
    }

    public function destroy(Tunjangan $tunjangan)
    {
        $tunjangan->delete();

        return redirect()->route('tunjangan.index')
            ->with('success', 'Data tunjangan berhasil dihapus.');
    }
}
