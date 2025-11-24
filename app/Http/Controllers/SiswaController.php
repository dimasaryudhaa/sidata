<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\Rayon;
use App\Models\Rombel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';

        if ($isSiswa) {

            $siswa = DB::table('akun_siswa')
                ->join('peserta_didik', 'akun_siswa.peserta_didik_id', '=', 'peserta_didik.id')
                ->leftJoin('rayon', 'peserta_didik.rayon_id', '=', 'rayon.id')
                ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
                ->where('akun_siswa.email', $user->email)
                ->select(
                    'peserta_didik.id',
                    'peserta_didik.nama_lengkap',
                    'peserta_didik.jenis_kelamin',
                    'peserta_didik.nis',
                    'peserta_didik.nisn',
                    'peserta_didik.nik',
                    'peserta_didik.no_kk',
                    'peserta_didik.tempat_lahir',
                    'peserta_didik.tanggal_lahir',
                    'peserta_didik.agama',
                    'rayon.nama_rayon',
                    'rombel.nama_rombel',
                    'peserta_didik.kewarganegaraan',
                    'peserta_didik.negara_asal',
                    'peserta_didik.berkebutuhan_khusus'
                )
                ->paginate(1);

            return view('siswa.index', compact('siswa', 'isSiswa'));

        } else {

            $siswa = DB::table('peserta_didik')
                ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
                ->leftJoin('rayon', 'peserta_didik.rayon_id', '=', 'rayon.id')
                ->select(
                    'peserta_didik.id',
                    'peserta_didik.nama_lengkap',
                    'peserta_didik.jenis_kelamin',
                    'peserta_didik.nis',
                    'peserta_didik.nisn',
                    'peserta_didik.rombel_id',
                    'rayon.nama_rayon',
                    'rombel.nama_rombel'
                )
                ->orderBy('peserta_didik.nama_lengkap', 'asc')
                ->paginate(12);

            $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

            return view('siswa.index', compact('siswa', 'rombels', 'isSiswa'));
        }
    }


    public function create()
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $rayons = Rayon::all();
        $rombels = Rombel::all();

        return view('siswa.create', compact('rayons', 'rombels', 'prefix'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required',
            'rayon_id' => 'required|exists:rayon,id',
            'rombel_id' => 'required|exists:rombel,id',
        ]);

        Siswa::create($request->all());

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isSiswa = $user->role === 'siswa';

        $siswa = Siswa::findOrFail($id);
        $rombels = Rombel::all();
        $rayons = Rayon::all();

        return view('siswa.edit', compact('siswa', 'rombels', 'rayons', 'isAdmin', 'isSiswa'));
    }


    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required',
            'rayon_id' => 'required|exists:rayon,id',
            'rombel_id' => 'required|exists:rombel,id',
        ]);

        $siswa->update($request->all());

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }


    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'siswa.index')
            ->with('success', 'Data siswa berhasil dihapus!');
    }
}
