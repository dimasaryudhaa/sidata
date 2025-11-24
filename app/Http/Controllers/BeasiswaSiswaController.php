<?php

namespace App\Http\Controllers;

use App\Models\BeasiswaSiswa;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BeasiswaSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'siswa.';

        if ($isSiswa) {
            $beasiswa = DB::table('beasiswa')
                ->join('akun_siswa', 'beasiswa.peserta_didik_id', '=', 'akun_siswa.peserta_didik_id')
                ->where('akun_siswa.email', $user->email)
                ->select('beasiswa.*')
                ->orderBy('tahun_mulai', 'desc')
                ->paginate(12);

            return view('beasiswa.index', compact('beasiswa', 'isSiswa', 'isAdmin', 'prefix'));
        }

        $beasiswa = DB::table('peserta_didik')
            ->leftJoin('beasiswa', 'peserta_didik.id', '=', 'beasiswa.peserta_didik_id')
            ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
            ->select(
                'peserta_didik.id as siswa_id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.rombel_id',
                'rombel.nama_rombel',
                DB::raw('COUNT(beasiswa.id) as jumlah_beasiswa')
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

        return view('beasiswa.index', compact('beasiswa', 'rombels', 'isSiswa', 'isAdmin', 'prefix'));
    }

    public function show($siswa_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $siswa = Siswa::findOrFail($siswa_id);
        $beasiswa = BeasiswaSiswa::where('peserta_didik_id', $siswa_id)->get();

        return view('beasiswa.show', compact('beasiswa', 'siswa', 'prefix'));
    }


    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $siswaId = $request->query('siswa_id');

        if ($siswaId) {
            $siswa = Siswa::findOrFail($siswaId);
            return view('beasiswa.create', compact('siswaId', 'siswa', 'prefix'));
        }

        $siswa = Siswa::orderBy('nama_lengkap')->get();
        return view('beasiswa.create', compact('siswa', 'prefix'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_beasiswa' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'tahun_mulai' => 'nullable|digits:4|integer',
            'tahun_selesai' => 'nullable|digits:4|integer',
        ]);

        BeasiswaSiswa::create($request->all());

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix . 'beasiswa.index')
            ->with('success', 'Data beasiswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isSiswa = $user->role === 'siswa';
        $prefix = $isAdmin ? 'admin.' : 'siswa.';

        $beasiswa = BeasiswaSiswa::findOrFail($id);
        $siswa = Siswa::findOrFail($beasiswa->peserta_didik_id);

        return view('beasiswa.edit', compact('beasiswa', 'siswa', 'isAdmin', 'isSiswa', 'prefix'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_beasiswa' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'tahun_mulai' => 'nullable|digits:4|integer',
            'tahun_selesai' => 'nullable|digits:4|integer',
        ]);

        $beasiswa = BeasiswaSiswa::findOrFail($id);
        $beasiswa->update($request->all());

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix . 'beasiswa.index')
            ->with('success', 'Data beasiswa berhasil diperbarui.');
    }


    public function destroy($id)
    {
        BeasiswaSiswa::findOrFail($id)->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix . 'beasiswa.index')
            ->with('success', 'Data beasiswa berhasil dihapus.');
    }
}
