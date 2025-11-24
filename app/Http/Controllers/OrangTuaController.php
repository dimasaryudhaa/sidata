<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrangTuaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        if ($isSiswa) {
            $data = DB::table('orang_tua')
                ->join('peserta_didik', 'orang_tua.peserta_didik_id', '=', 'peserta_didik.id')
                ->join('akun_siswa', 'peserta_didik.id', '=', 'akun_siswa.peserta_didik_id')
                ->where('akun_siswa.email', $user->email)
                ->select(
                    'orang_tua.id as orang_tua_id',
                    'peserta_didik.id as siswa_id',
                    'peserta_didik.nama_lengkap',
                    'orang_tua.nama_ayah', 'orang_tua.nik_ayah', 'orang_tua.tahun_lahir_ayah',
                    'orang_tua.pendidikan_ayah', 'orang_tua.pekerjaan_ayah', 'orang_tua.penghasilan_ayah',
                    'orang_tua.kebutuhan_khusus_ayah',
                    'orang_tua.nama_ibu', 'orang_tua.nik_ibu', 'orang_tua.tahun_lahir_ibu',
                    'orang_tua.pendidikan_ibu', 'orang_tua.pekerjaan_ibu', 'orang_tua.penghasilan_ibu',
                    'orang_tua.kebutuhan_khusus_ibu',
                    'orang_tua.nama_wali', 'orang_tua.nik_wali', 'orang_tua.tahun_lahir_wali',
                    'orang_tua.pendidikan_wali', 'orang_tua.pekerjaan_wali', 'orang_tua.penghasilan_wali'
                )
                ->orderBy('peserta_didik.nama_lengkap', 'asc')
                ->paginate(12);

            return view('orang-tua.index', compact('data', 'isSiswa', 'prefix'));
        }

        $data = DB::table('peserta_didik')
            ->leftJoin('orang_tua', 'peserta_didik.id', '=', 'orang_tua.peserta_didik_id')
            ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
            ->select(
                'peserta_didik.id as siswa_id',
                'peserta_didik.nama_lengkap',
                'orang_tua.id as orang_tua_id',
                'orang_tua.nama_ayah',
                'orang_tua.nama_ibu',
                'orang_tua.nama_wali',
                'peserta_didik.rombel_id',
                'rombel.nama_rombel'
            )
            ->orderBy('peserta_didik.nama_lengkap', 'asc')
            ->paginate(12);

        $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

        return view('orang-tua.index', compact('data', 'rombels', 'isSiswa', 'prefix'));
    }

    public function create()
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $siswa = Siswa::all();
        return view('orang-tua.create', compact('siswa', 'prefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
        ]);

        OrangTua::create($request->all());

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'orang-tua.index')
            ->with('success', 'Data orang tua berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $orangTua = OrangTua::find($id);

        if (!$orangTua) {
            $siswa = Siswa::findOrFail($id);
            $existing = OrangTua::where('peserta_didik_id', $siswa->id)->first();

            if ($existing) {
                $orangTua = $existing;
            } else {
                $orangTua = new OrangTua();
                $orangTua->peserta_didik_id = $siswa->id;
            }
        } else {
            $siswa = Siswa::find($orangTua->peserta_didik_id);
        }

        $semuaSiswa = Siswa::all();

        return view('orang-tua.edit', [
            'data' => $orangTua,
            'siswa' => $semuaSiswa,
            'prefix' => $prefix,
        ]);
    }

    public function update(Request $request, $id)
    {
        $orangTua = OrangTua::findOrFail($id);

        $orangTua->update($request->all());

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'orang-tua.index')
            ->with('success', 'Data orang tua berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $orangTua = OrangTua::findOrFail($id);

        OrangTua::where('peserta_didik_id', $orangTua->peserta_didik_id)->delete();

        return redirect()->route($prefix.'orang-tua.index')
            ->with('success', 'Data orang tua berhasil dihapus!');
    }

}
