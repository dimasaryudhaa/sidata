<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TugasTambahan;
use App\Models\Ptk;

class TugasTambahanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $tugasTambahan = DB::table('tugas_tambahan')
                ->join('ptk', 'tugas_tambahan.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'tugas_tambahan.id as tugas_tambahan_id',
                    'ptk.id as ptk_id',
                    'tugas_tambahan.jabatan_ptk',
                    'tugas_tambahan.prasarana',
                    'tugas_tambahan.nomor_sk',
                    'tugas_tambahan.tmt_tambahan',
                    'tugas_tambahan.tst_tambahan'
                )
                ->orderBy('tugas_tambahan.tmt_tambahan', 'desc')
                ->paginate(12);

        } else {
            $tugasTambahan = DB::table('ptk')
                ->leftJoin('tugas_tambahan', 'ptk.id', '=', 'tugas_tambahan.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(tugas_tambahan.id) as jumlah_tugas_tambahan')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);
        }

        return view('tugas-tambahan.index', compact('tugasTambahan', 'isPtk', 'isAdmin', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('tugas-tambahan.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('tugas-tambahan.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_ptk' => 'required|string|max:100',
            'prasarana' => 'nullable|string|max:100',
            'nomor_sk' => 'required|string|min:10|max:20',
            'tmt_tambahan' => 'required|date',
            'tst_tambahan' => 'nullable|date',
        ]);

        TugasTambahan::create($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'tugas-tambahan.index')
            ->with('success', 'Data tugas tambahan berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $tugasTambahan = TugasTambahan::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('tugas-tambahan.show', compact('tugasTambahan', 'ptk'));
    }

    public function edit(TugasTambahan $tugasTambahan)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

        $ptks = Ptk::orderBy('nama_lengkap')->get();

        return view('tugas-tambahan.edit', [
            'tugasTambahan' => $tugasTambahan,
            'ptks' => $ptks,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
        ]);
    }

    public function update(Request $request, TugasTambahan $tugasTambahan)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_ptk' => 'required|string|max:100',
            'prasarana' => 'nullable|string|max:100',
            'nomor_sk' => 'required|string|min:10|max:20',
            'tmt_tambahan' => 'required|date',
            'tst_tambahan' => 'nullable|date',
        ]);

        $tugasTambahan->update($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'tugas-tambahan.index')
            ->with('success', 'Data tugas tambahan berhasil diperbarui.');
    }

    public function destroy(TugasTambahan $tugasTambahan)
    {
        $tugasTambahan->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'tugas-tambahan.index')
            ->with('success', 'Data tugas tambahan berhasil dihapus.');
    }
}
