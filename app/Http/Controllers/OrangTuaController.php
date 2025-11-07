<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrangTuaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';

        $rombels = collect();

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

            return view('orang-tua.index', compact('data', 'isSiswa'));
        } else {
            $data = DB::table('peserta_didik')
                ->leftJoin('orang_tua', 'peserta_didik.id', '=', 'orang_tua.peserta_didik_id')
                ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
                ->select(
                    'peserta_didik.id as siswa_id',
                    'peserta_didik.rombel_id',
                    'peserta_didik.nama_lengkap',
                    'orang_tua.id as orang_tua_id',
                    'orang_tua.nama_ayah',
                    'orang_tua.nama_ibu',
                    'orang_tua.nama_wali',
                    'peserta_didik.rombel_id'
                )
                ->orderBy('peserta_didik.nama_lengkap', 'asc')
                ->paginate(12);

            $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

            return view('orang-tua.index', compact('data', 'rombels', 'isSiswa'));
        }
    }

    public function create()
    {
        $siswa = Siswa::all();
        return view('orang-tua.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_didik_id' => 'nullable|exists:peserta_didik,id',
            'nama_ayah' => 'nullable|string',
            'nama_ibu' => 'nullable|string',
            'nama_wali' => 'nullable|string',
        ]);

        OrangTua::create($request->all());
        return redirect()->route('orang-tua.index')->with('success', 'Data orang tua berhasil ditambahkan!');
    }

    public function edit($id)
    {
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
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = OrangTua::findOrFail($id);
        $data->update($request->all());
        return redirect()->route('orang-tua.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $orangTua = OrangTua::findOrFail($id);
        OrangTua::where('peserta_didik_id', $orangTua->peserta_didik_id)->delete();

        return redirect()->route('orang-tua.index')->with('success', 'Data orang tua berhasil dihapus!');
    }

}
