<?php

namespace App\Http\Controllers;

use App\Models\AnakPtk;
use App\Models\Ptk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnakPtkController extends Controller
{
    public function index()
    {
        $anakPtk = DB::table('ptk')
            ->leftJoin('anak', 'ptk.id', '=', 'anak.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(anak.id) as jumlah_anak')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('anak-ptk.index', compact('anakPtk'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('anak-ptk.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::all();
            return view('anak-ptk.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'nama_anak' => 'required|string|max:255',
            'status_anak' => 'required|string|max:100',
            'jenjang' => 'required|string|max:100',
            'nisn' => 'nullable|digits:10',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tahun_masuk' => 'required|digits:4',
        ]);

        AnakPtk::create($validated);

        return redirect()->route('anak-ptk.index')->with('success', 'Data anak PTK berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $anak = AnakPtk::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('anak-ptk.show', compact('anak', 'ptk'));
    }

    public function edit($ptk_id)
    {
        $anak = AnakPtk::where('ptk_id', $ptk_id)->first();

        if (!$anak) {
            $anak = new AnakPtk();
            $anak->ptk_id = $ptk_id;
        }

        $ptks = Ptk::all();
        return view('anak-ptk.edit', compact('anak', 'ptks'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'nama_anak' => 'required|string|max:255',
            'status_anak' => 'required|string|max:100',
            'jenjang' => 'required|string|max:100',
            'nisn' => 'nullable|digits:10',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tahun_masuk' => 'required|digits:4',
        ]);

        AnakPtk::updateOrCreate(
            ['ptk_id' => $request->ptk_id],
            $validated
        );

        return redirect()->route('anak-ptk.index')->with('success', 'Data anak PTK berhasil diperbarui.');
    }

    public function destroy($ptk_id)
    {
        AnakPtk::where('ptk_id', $ptk_id)->delete();

        return redirect()->route('anak-ptk.index')->with('success', 'Semua data anak PTK berhasil dihapus.');
    }
}
