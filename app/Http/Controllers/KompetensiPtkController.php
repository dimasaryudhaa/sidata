<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KompetensiPtk;
use App\Models\Ptk;

class KompetensiPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $kompetensiPtk = DB::table('kompetensi')
                ->join('ptk', 'kompetensi.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'kompetensi.id as kompetensi_id',
                    'ptk.id as ptk_id',
                    'kompetensi.bidang_studi',
                    'kompetensi.urutan'
                )
                ->orderBy('kompetensi.urutan', 'asc')
                ->paginate(12);
        } else {
            $kompetensiPtk = DB::table('ptk')
                ->leftJoin('kompetensi', 'ptk.id', '=', 'kompetensi.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(kompetensi.id) as jumlah_kompetensi')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);
        }

        return view('kompetensi-ptk.index', compact('kompetensiPtk', 'isPtk', 'isAdmin', 'prefix'));
    }

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptk = Ptk::findOrFail($ptk_id);
        $kompetensi = KompetensiPtk::where('ptk_id', $ptk_id)->orderBy('urutan', 'asc')->get();

        return view('kompetensi-ptk.show', compact('ptk', 'kompetensi', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('kompetensi-ptk.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('kompetensi-ptk.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'bidang_studi' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
        ]);

        KompetensiPtk::create($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'kompetensi-ptk.index')
            ->with('success', 'Data kompetensi PTK berhasil ditambahkan.');
    }

    public function edit(KompetensiPtk $kompetensiPtk)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
        $ptk = Ptk::findOrFail($kompetensiPtk->ptk_id);

        return view('kompetensi-ptk.edit', compact('kompetensiPtk', 'ptk', 'prefix'));
    }

    public function update(Request $request, KompetensiPtk $kompetensiPtk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'bidang_studi' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
        ]);

        $kompetensiPtk->update($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'kompetensi-ptk.index')
            ->with('success', 'Data kompetensi PTK berhasil diperbarui.');
    }

    public function destroy(KompetensiPtk $kompetensiPtk)
    {
        $kompetensiPtk->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'kompetensi-ptk.index')
            ->with('success', 'Data kompetensi PTK berhasil dihapus.');
    }
}
