<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KompetensiKhususPtk;
use App\Models\Ptk;

class KompetensiKhususPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $kompetensiKhusus = DB::table('kompetensi_khusus')
                ->join('ptk', 'kompetensi_khusus.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'kompetensi_khusus.id as kompetensi_khusus_id',
                    'ptk.id as ptk_id',
                    'kompetensi_khusus.punya_lisensi_kepala_sekolah',
                    'kompetensi_khusus.nomor_unik_kepala_sekolah',
                    'kompetensi_khusus.keahlian_lab_oratorium',
                    'kompetensi_khusus.mampu_menangani',
                    'kompetensi_khusus.keahlian_braile',
                    'kompetensi_khusus.keahlian_bahasa_isyarat'
                )
                ->orderBy('kompetensi_khusus.id', 'asc')
                ->paginate(12);
        } else {
            $kompetensiKhusus = DB::table('ptk')
                ->leftJoin('kompetensi_khusus', 'ptk.id', '=', 'kompetensi_khusus.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(kompetensi_khusus.id) as jumlah_kompetensi_khusus')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);
        }

        return view('kompetensi-khusus-ptk.index', compact('kompetensiKhusus', 'isPtk', 'isAdmin', 'prefix'));
    }

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptk = Ptk::findOrFail($ptk_id);
        $kompetensiKhusus = KompetensiKhususPtk::where('ptk_id', $ptk_id)->get();

        return view('kompetensi-khusus-ptk.show', compact('ptk', 'kompetensiKhusus', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('kompetensi-khusus-ptk.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('kompetensi-khusus-ptk.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'punya_lisensi_kepala_sekolah' => 'nullable|boolean',
            'nomor_unik_kepala_sekolah' => 'nullable|string|max:50',
            'keahlian_lab_oratorium' => 'nullable|string|max:255',
            'mampu_menangani' => 'nullable|in:Tidak,Netra (A),Rungu (B),Grahita Sedang (C1),Grahita Ringan (D),Daksa Sedang (D1),Laras,Daksa Ringan,Wicara,Tuna Ganda,Hiper Aktif (H),Cerdas Istimewa (I),Bakat Istimewa (J),Kesulitan Belajar (K),Narkoba (N),Indigo (O),Down Sindrome (P),Autis (Q)',
            'keahlian_braile' => 'nullable|boolean',
            'keahlian_bahasa_isyarat' => 'nullable|boolean',
        ]);

        KompetensiKhususPtk::create($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'kompetensi-khusus-ptk.index')
            ->with('success', 'Data kompetensi khusus PTK berhasil ditambahkan.');
    }

    public function edit(KompetensiKhususPtk $kompetensiKhususPtk)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
        $ptk = Ptk::findOrFail($kompetensiKhususPtk->ptk_id);

        return view('kompetensi-khusus-ptk.edit', compact('kompetensiKhususPtk', 'ptk', 'prefix'));
    }

    public function update(Request $request, KompetensiKhususPtk $kompetensiKhususPtk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'punya_lisensi_kepala_sekolah' => 'nullable|boolean',
            'nomor_unik_kepala_sekolah' => 'nullable|string|max:50',
            'keahlian_lab_oratorium' => 'nullable|string|max:255',
            'mampu_menangani' => 'nullable|in:Tidak,Netra (A),Rungu (B),Grahita Sedang (C1),Grahita Ringan (D),Daksa Sedang (D1),Laras,Daksa Ringan,Wicara,Tuna Ganda,Hiper Aktif (H),Cerdas Istimewa (I),Bakat Istimewa (J),Kesulitan Belajar (K),Narkoba (N),Indigo (O),Down Sindrome (P),Autis (Q)',
            'keahlian_braile' => 'nullable|boolean',
            'keahlian_bahasa_isyarat' => 'nullable|boolean',
        ]);

        $kompetensiKhususPtk->update($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'kompetensi-khusus-ptk.index')
            ->with('success', 'Data kompetensi khusus PTK berhasil diperbarui.');
    }

    public function destroy(KompetensiKhususPtk $kompetensiKhususPtk)
    {
        $kompetensiKhususPtk->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'kompetensi-khusus-ptk.index')
            ->with('success', 'Data kompetensi khusus PTK berhasil dihapus.');
    }
}
