<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrestasiSiswa;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PrestasiSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'siswa.';

        if ($isSiswa) {
            $prestasi = DB::table('prestasi')
                ->join('akun_siswa', 'prestasi.peserta_didik_id', '=', 'akun_siswa.peserta_didik_id')
                ->where('akun_siswa.email', $user->email)
                ->select('prestasi.*')
                ->orderBy('tahun_prestasi', 'desc')
                ->paginate(12);

            return view('prestasi.index', compact(
                'prestasi',
                'isSiswa',
                'isAdmin',
                'prefix'
            ));
        }

        $prestasi = DB::table('peserta_didik')
            ->leftJoin('prestasi', 'peserta_didik.id', '=', 'prestasi.peserta_didik_id')
            ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
            ->select(
                'peserta_didik.id as siswa_id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.rombel_id',
                'rombel.nama_rombel',
                DB::raw('COUNT(prestasi.id) as jumlah_prestasi')
            )
            ->groupBy(
                'peserta_didik.id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.rombel_id',
                'rombel.nama_rombel'
            )
            ->orderBy('peserta_didik.nama_lengkap', 'asc')
            ->paginate(12);

        $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

        return view('prestasi.index', compact(
            'prestasi',
            'rombels',
            'isSiswa',
            'isAdmin',
            'prefix'
        ));
    }


    public function show($siswa_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $siswa = Siswa::findOrFail($siswa_id);
        $prestasi = PrestasiSiswa::where('peserta_didik_id', $siswa_id)->get();

        return view('prestasi.show', compact('prestasi', 'siswa', 'prefix'));
    }


    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $siswaId = $request->query('siswa_id');

        if ($siswaId) {
            $siswa = Siswa::findOrFail($siswaId);
            return view('prestasi.create', compact('siswaId', 'siswa', 'prefix'));
        }

        $siswa = Siswa::orderBy('nama_lengkap')->get();
        return view('prestasi.create', compact('siswa', 'prefix'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_prestasi' => 'required|in:Sains,Seni,Olahraga,Lain-lain',
            'tingkat_prestasi' => 'required|in:Sekolah,Kecamatan,Kabupaten,Provinsi,Nasional,Internasional',
            'nama_prestasi' => 'required|string|max:255',
            'tahun_prestasi' => 'required|digits:4|integer',
            'penyelenggara' => 'required|string|max:255',
            'peringkat' => 'nullable|integer'
        ]);

        PrestasiSiswa::create($request->all());

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix . 'prestasi.index')
            ->with('success', 'Data prestasi berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isSiswa = $user->role === 'siswa';
        $prefix = $isAdmin ? 'admin.' : 'siswa.';

        $prestasi = PrestasiSiswa::findOrFail($id);
        $siswa = Siswa::findOrFail($prestasi->peserta_didik_id);

        return view('prestasi.edit', compact(
            'prestasi',
            'siswa',
            'isAdmin',
            'isSiswa',
            'prefix'
        ));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_prestasi' => 'required|in:Sains,Seni,Olahraga,Lain-lain',
            'tingkat_prestasi' => 'required|in:Sekolah,Kecamatan,Kabupaten,Provinsi,Nasional,Internasional',
            'nama_prestasi' => 'required|string|max:255',
            'tahun_prestasi' => 'required|digits:4|integer',
            'penyelenggara' => 'required|string|max:255',
            'peringkat' => 'nullable|integer',
        ]);

        $prestasi = PrestasiSiswa::findOrFail($id);
        $prestasi->update($request->all());

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix . 'prestasi.index')
            ->with('success', 'Data prestasi berhasil diperbarui.');
    }


    public function destroy($id)
    {
        PrestasiSiswa::findOrFail($id)->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix . 'prestasi.index')
            ->with('success', 'Data prestasi berhasil dihapus.');
    }
}
