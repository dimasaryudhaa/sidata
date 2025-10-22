<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RiwayatKarir;
use App\Models\Ptk;

class RiwayatKarirController extends Controller
{
    public function index()
    {
        $riwayatKarir = DB::table('ptk')
            ->leftJoin('riwayat_karir', 'ptk.id', '=', 'riwayat_karir.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                DB::raw('COUNT(riwayat_karir.id) as jumlah_riwayat_karir')
            )
            ->groupBy('ptk.id', 'ptk.nama_lengkap')
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('riwayat-karir.index', compact('riwayatKarir'));
    }

    public function create(Request $request)
    {
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('riwayat-karir.create', compact('ptkId', 'ptk'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('riwayat-karir.create', compact('ptks'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenjang_pendidikan' => 'required|string|max:100',
            'jenis_lembaga' => 'required|string|max:100',
            'status_kepegawaian' => 'required|string|max:50',
            'jenis_ptk' => 'required|string|max:100',
            'lembaga_pengangkat' => 'required|string|max:150',
            'no_sk_kerja' => 'required|string|max:50',
            'tgl_sk_kerja' => 'required|date',
            'tmt_kerja' => 'required|date',
            'tst_kerja' => 'nullable|date',
            'tempat_kerja' => 'required|string|max:100',
            'ttd_sk_kerja' => 'required|string|max:100',
        ]);

        RiwayatKarir::create($validated);

        return redirect()->route('riwayat-karir.index')
            ->with('success', 'Data riwayat karir berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $riwayatKarir = RiwayatKarir::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('riwayat-karir.show', compact('riwayatKarir', 'ptk'));
    }

    public function edit(RiwayatKarir $riwayatKarir)
    {
        $ptks = Ptk::orderBy('nama_lengkap')->get();
        return view('riwayat-karir.edit', compact('riwayatKarir', 'ptks'));
    }

    public function update(Request $request, RiwayatKarir $riwayatKarir)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenjang_pendidikan' => 'required|string|max:100',
            'jenis_lembaga' => 'required|string|max:100',
            'status_kepegawaian' => 'required|string|max:50',
            'jenis_ptk' => 'required|string|max:100',
            'lembaga_pengangkat' => 'required|string|max:150',
            'no_sk_kerja' => 'required|string|max:50',
            'tgl_sk_kerja' => 'required|date',
            'tmt_kerja' => 'required|date',
            'tst_kerja' => 'nullable|date',
            'tempat_kerja' => 'required|string|max:100',
            'ttd_sk_kerja' => 'required|string|max:100',
        ]);

        $riwayatKarir->update($validated);

        return redirect()->route('riwayat-karir.index')
            ->with('success', 'Data riwayat karir berhasil diperbarui.');
    }

    public function destroy(RiwayatKarir $riwayatKarir)
    {
        $riwayatKarir->delete();

        return redirect()->route('riwayat-karir.index')
            ->with('success', 'Data riwayat karir berhasil dihapus.');
    }
}
