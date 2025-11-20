<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\NilaiTest;
use App\Models\Ptk;

class NilaiTestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $nilaiTest = DB::table('nilai_test')
                ->join('ptk', 'nilai_test.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'nilai_test.id as nilai_test_id',
                    'ptk.id as ptk_id',
                    'nilai_test.jenis_test',
                    'nilai_test.nama_test',
                    'nilai_test.penyelenggara',
                    'nilai_test.tahun',
                    'nilai_test.skor',
                    'nilai_test.nomor_peserta'
                )
                ->orderBy('nilai_test.tahun', 'desc')
                ->paginate(12);
        } else {
            $nilaiTest = DB::table('ptk')
                ->leftJoin('nilai_test', 'ptk.id', '=', 'nilai_test.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(nilai_test.id) as jumlah_nilai_test')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap')
                ->paginate(12);
        }

        return view('nilai-test.index', compact('nilaiTest', 'isPtk', 'isAdmin', 'prefix'));
    }

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptk = Ptk::findOrFail($ptk_id);
        $nilaiTest = NilaiTest::where('ptk_id', $ptk_id)->get();

        return view('nilai-test.show', compact('ptk', 'nilaiTest', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('nilai-test.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('nilai-test.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_test' => 'required|string|max:150',
            'nama_test' => 'required|string|max:150',
            'penyelenggara' => 'required|string|max:150',
            'tahun' => 'required|digits:4|integer',
            'skor' => 'nullable|numeric',
            'nomor_peserta' => 'nullable|string|max:50',
        ]);

        NilaiTest::create($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'nilai-test.index')
            ->with('success', 'Data nilai test berhasil ditambahkan.');
    }

    public function edit(NilaiTest $nilaiTest)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        $ptks = Ptk::orderBy('nama_lengkap')->get();
        $ptk = Ptk::findOrFail($nilaiTest->ptk_id);

        return view('nilai-test.edit', compact('nilaiTest', 'ptks', 'ptk', 'isAdmin', 'prefix'));
    }

    public function update(Request $request, NilaiTest $nilaiTest)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_test' => 'required|string|max:150',
            'nama_test' => 'required|string|max:150',
            'penyelenggara' => 'required|string|max:150',
            'tahun' => 'required|digits:4|integer',
            'skor' => 'nullable|numeric',
            'nomor_peserta' => 'nullable|string|max:50',
        ]);

        $nilaiTest->update($validated);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'nilai-test.index')
            ->with('success', 'Data nilai test berhasil diperbarui.');
    }

    public function destroy(NilaiTest $nilaiTest)
    {
        $nilaiTest->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'nilai-test.index')
            ->with('success', 'Data nilai test berhasil dihapus.');
    }
}
