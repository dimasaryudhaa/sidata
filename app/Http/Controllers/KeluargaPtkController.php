<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KeluargaPtk;
use App\Models\Ptk;

class KeluargaPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $data = collect();

        if ($isPtk) {
            $data = DB::table('akun_ptk')
                ->join('ptk', 'akun_ptk.ptk_id', '=', 'ptk.id')
                ->leftJoin('keluarga_ptk', 'ptk.id', '=', 'keluarga_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
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
                ->paginate(1);

            return view('keluarga-ptk.index', compact('data', 'isPtk'));
        } else {
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

            return view('keluarga-ptk.index', compact('data', 'isPtk'));
        }
    }

    public function create()
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptks = Ptk::all();
        $data = new KeluargaPtk();

        return view('keluarga-ptk.edit', compact('data', 'ptks', 'prefix'));
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

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'keluarga-ptk.index')->with('success', 'Data keluarga PTK berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

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
        } else {
            $ptk = Ptk::find($keluarga->ptk_id);
        }

        $ptks = Ptk::all();

        return view('keluarga-ptk.edit', [
            'data' => $keluarga,
            'ptks' => $ptks,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
            'ptk' => $ptk,
        ]);
    }

    public function update(Request $request, KeluargaPtk $keluarga_ptk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'no_kk' => 'nullable|digits:16',
            'status_perkawinan' => 'nullable|in:Kawin,Belum Kawin,Janda/Duda',
            'nama_suami_istri' => 'nullable|string|max:255',
            'nip_suami_istri' => 'nullable|string|max:25',
            'pekerjaan_suami_istri' => 'nullable|in:Tidak Bekerja,Nelayan,Petani,Peternak,PNS,Swasta,Wiraswasta,Pedagang,Buruh,Pensiunan,Sudah Meninggal',
        ]);

        $keluarga_ptk->update($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'keluarga-ptk.index')
            ->with('success', 'Data keluarga PTK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $keluarga = KeluargaPtk::findOrFail($id);
        KeluargaPtk::where('ptk_id', $keluarga->ptk_id)->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'keluarga-ptk.index')
            ->with('success', 'Data keluarga PTK berhasil dihapus.');
    }

}
