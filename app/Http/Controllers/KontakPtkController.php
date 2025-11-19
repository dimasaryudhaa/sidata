<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KontakPtk;
use App\Models\Ptk;
use Illuminate\Support\Facades\Auth;

class KontakPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';

        if ($isPtk) {
            $data = DB::table('kontak_ptk')
                ->join('ptk', 'kontak_ptk.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'kontak_ptk.id as kontak_id',
                    'ptk.id as ptk_id',
                    'kontak_ptk.no_hp',
                    'kontak_ptk.email',
                    'kontak_ptk.alamat_jalan',
                    'kontak_ptk.rt',
                    'kontak_ptk.rw',
                    'kontak_ptk.kelurahan',
                    'kontak_ptk.kecamatan',
                    'kontak_ptk.kode_pos',
                )
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);

            return view('kontak-ptk.index', compact('data', 'isPtk'));
        } else {
            $data = DB::table('ptk')
                ->leftJoin('kontak_ptk', 'ptk.id', '=', 'kontak_ptk.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    'kontak_ptk.id as kontak_id',
                    'kontak_ptk.no_hp',
                    'kontak_ptk.email',
                    'kontak_ptk.alamat_jalan',
                    'kontak_ptk.rt',
                    'kontak_ptk.rw',
                    'kontak_ptk.kelurahan',
                    'kontak_ptk.kecamatan',
                    'kontak_ptk.kode_pos'
                )
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);

            return view('kontak-ptk.index', compact('data', 'isPtk'));
        }
    }

    public function create()
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptks = Ptk::all();
        $data = new KontakPtk();

        return view('kontak-ptk.edit', compact('data', 'ptks', 'prefix'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'no_hp' => 'nullable|max:15',
            'email' => 'nullable|email|max:100',
            'alamat_jalan' => 'nullable|max:255',
            'rt' => 'nullable|max:3',
            'rw' => 'nullable|max:3',
            'kelurahan' => 'nullable|max:100',
            'kecamatan' => 'nullable|max:100',
            'kode_pos' => 'nullable|max:5',
        ]);

        KontakPtk::create($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'kontak-ptk.index')
                        ->with('success', 'Data kontak PTK berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

        $kontak = KontakPtk::find($id);

        if (!$kontak) {
            $ptk = Ptk::findOrFail($id);
            $existing = KontakPtk::where('ptk_id', $ptk->id)->first();

            if ($existing) {
                $kontak = $existing;
            } else {
                $kontak = new KontakPtk();
                $kontak->ptk_id = $ptk->id;
            }
        } else {
            $ptk = Ptk::find($kontak->ptk_id);
        }

        return view('kontak-ptk.edit', [
            'data' => $kontak,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
            'ptk' => $ptk,
        ]);
    }

    public function update(Request $request, KontakPtk $kontak_ptk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'no_hp' => 'nullable|max:15',
            'email' => 'nullable|email|max:100',
            'alamat_jalan' => 'nullable|max:255',
            'rt' => 'nullable|max:3',
            'rw' => 'nullable|max:3',
            'kelurahan' => 'nullable|max:100',
            'kecamatan' => 'nullable|max:100',
            'kode_pos' => 'nullable|max:5',
        ]);

        $kontak_ptk->update($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'.kontak-ptk.index')
            ->with('success', 'Data kontak PTK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kontak = KontakPtk::findOrFail($id);
        KontakPtk::where('ptk_id', $kontak->ptk_id)->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'kontak-ptk.index')
            ->with('success', 'Data kontak PTK berhasil dihapus.');
    }

}
