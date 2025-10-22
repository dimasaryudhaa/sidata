<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KeluargaPtk;
use App\Models\Ptk;

class KeluargaPtkController extends Controller
{
    public function index()
    {
        $data = DB::table('ptk')
            ->leftJoin('keluarga_ptk', 'keluarga_ptk.ptk_id', '=', 'ptk.id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                'keluarga_ptk.id as keluarga_id',
                'keluarga_ptk.no_kk',
                'keluarga_ptk.status_perkawinan',
                'keluarga_ptk.nama_suami_istri',
                'keluarga_ptk.nip_suami_istri',
                'keluarga_ptk.pekerjaan_suami_istri'
            )
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('keluarga-ptk.index', compact('data'));
    }

    public function create()
    {
        $ptks = Ptk::all();
        $data = new KeluargaPtk();
        return view('keluarga-ptk.edit', compact('data', 'ptks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'no_kk' => 'nullable|digits:16',
            'status_perkawinan' => 'nullable|in:Kawin,Belum Kawin,Janda/Duda',
            'nama_suami_istri' => 'nullable|string|max:255',
            'nip_suami_istri' => 'nullable|string|max:25',
            'pekerjaan_suami_istri' => 'nullable|in:Tidak Bekerja,Nelayan,Petani,Peternak,PNS,Swasta,Wiraswasta,Pedagang,Buruh,Pensiunan,Sudah Meninggal',
        ]);

        KeluargaPtk::create($validated);
        return redirect()->route('keluarga-ptk.index')->with('success', 'Data keluarga PTK berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $keluarga = KeluargaPtk::find($id);

        if (!$keluarga) {
            $ptk = Ptk::findOrFail($id);

            $existing = KeluargaPtk::where('ptk_id', $ptk->id)->first();

            if ($existing) {
                $keluarga = $existing;
            } else {
                $keluarga = new KeluargaPtk();
                $keluarga->ptk_id = $ptk->id;
            }
        }

        $ptks = Ptk::all();

        return view('keluarga-ptk.edit', [
            'data' => $keluarga,
            'ptks' => $ptks,
        ]);
    }

    public function update(Request $request, $id)
    {
        $keluarga = KeluargaPtk::findOrFail($id);

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'no_kk' => 'nullable|digits:16',
            'status_perkawinan' => 'nullable|in:Kawin,Belum Kawin,Janda/Duda',
            'nama_suami_istri' => 'nullable|string|max:255',
            'nip_suami_istri' => 'nullable|string|max:25',
            'pekerjaan_suami_istri' => 'nullable|in:Tidak Bekerja,Nelayan,Petani,Peternak,PNS,Swasta,Wiraswasta,Pedagang,Buruh,Pensiunan,Sudah Meninggal',
        ]);

        $keluarga->update($validated);

        return redirect()->route('keluarga-ptk.index')->with('success', 'Data keluarga PTK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $keluarga = KeluargaPtk::findOrFail($id);
        KeluargaPtk::where('ptk_id', $keluarga->ptk_id)->delete();

        return redirect()->route('keluarga-ptk.index')->with('success', 'Data keluarga PTK berhasil dihapus.');
    }
}
