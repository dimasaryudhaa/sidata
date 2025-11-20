<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PenugasanPtk;
use App\Models\Ptk;

class PenugasanPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $data = collect();

        if ($isPtk) {
            $data = DB::table('akun_ptk')
                ->join('ptk', 'akun_ptk.ptk_id', '=', 'ptk.id')
                ->leftJoin('penugasan', 'ptk.id', '=', 'penugasan.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    'penugasan.id as penugasan_id',
                    'penugasan.nomor_surat_tugas',
                    'penugasan.tanggal_surat_tugas',
                    'penugasan.tmt_tugas',
                    'penugasan.status_sekolah_induk'
                )
                ->paginate(1);

            return view('penugasan-ptk.index', compact('data', 'isPtk'));
        } else {
            $data = DB::table('ptk')
                ->leftJoin('penugasan', 'penugasan.ptk_id', '=', 'ptk.id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    'penugasan.id as penugasan_id',
                    'penugasan.nomor_surat_tugas',
                    'penugasan.tanggal_surat_tugas',
                    'penugasan.tmt_tugas',
                    'penugasan.status_sekolah_induk'
                )
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);

            return view('penugasan-ptk.index', compact('data', 'isPtk'));
        }
    }


    public function create()
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptks = Ptk::all();
        $data = new PenugasanPtk();

        return view('penugasan-ptk.edit', compact('data', 'ptks', 'prefix'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'nomor_surat_tugas' => 'required|string|max:100',
            'tanggal_surat_tugas' => 'required|date',
            'tmt_tugas' => 'required|date',
            'status_sekolah_induk' => 'required|boolean',
        ]);

        PenugasanPtk::create($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'penugasan-ptk.index')
            ->with('success', 'Penugasan PTK berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

        $penugasan = PenugasanPtk::find($id);

        if (!$penugasan) {
            // id bukan penugasan → berarti id PTK
            $ptk = Ptk::findOrFail($id);
            $existing = PenugasanPtk::where('ptk_id', $ptk->id)->first();

            if ($existing) {
                $penugasan = $existing;
            } else {
                $penugasan = new PenugasanPtk();
                $penugasan->ptk_id = $ptk->id;
            }
        } else {
            $ptk = Ptk::find($penugasan->ptk_id);
        }

        $ptks = Ptk::all();

        return view('penugasan-ptk.edit', [
            'data' => $penugasan,
            'ptks' => $ptks,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
            'ptk' => $ptk,
        ]);
    }


    public function update(Request $request, PenugasanPtk $penugasan_ptk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'nomor_surat_tugas' => 'required|string|max:100',
            'tanggal_surat_tugas' => 'required|date',
            'tmt_tugas' => 'required|date',
            'status_sekolah_induk' => 'required|boolean',
        ]);

        $penugasan_ptk->update($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'penugasan-ptk.index')
            ->with('success', 'Penugasan PTK berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $penugasan = PenugasanPtk::findOrFail($id);

        PenugasanPtk::where('ptk_id', $penugasan->ptk_id)->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'penugasan-ptk.index')
            ->with('success', 'Penugasan PTK berhasil dihapus.');
    }
}
