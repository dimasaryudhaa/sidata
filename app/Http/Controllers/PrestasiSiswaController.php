<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrestasiSiswa;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Support\Facades\DB;

class PrestasiSiswaController extends Controller
{
    public function index()
    {
        $prestasi = DB::table('peserta_didik')
            ->leftJoin('prestasi', 'peserta_didik.id', '=', 'prestasi.peserta_didik_id')
            ->select(
                'peserta_didik.id as siswa_id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.rombel_id',
                DB::raw('COUNT(prestasi.id) as jumlah_prestasi')
            )
            ->groupBy('peserta_didik.id', 'peserta_didik.nama_lengkap')
            ->orderBy('peserta_didik.nama_lengkap', 'asc')
            ->paginate(12);

        $rombels = Rombel::orderBy('nama_rombel')->get();

        return view('prestasi.index', compact('prestasi', 'rombels'));
    }

    public function create(Request $request)
    {
        $siswaId = $request->query('siswa_id');

        if ($siswaId) {
            $siswa = Siswa::findOrFail($siswaId);
            return view('prestasi.create', compact('siswaId', 'siswa'));
        } else {
            $siswa = Siswa::all();
            return view('prestasi.create', compact('siswa'));
        }
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
            'peringkat' => 'nullable|integer',
        ]);

        PrestasiSiswa::create($request->all());
        return redirect()->route('prestasi.index')->with('success', 'Data prestasi berhasil ditambahkan.');
    }

    public function show($siswa_id)
    {
        $prestasi = PrestasiSiswa::where('peserta_didik_id', $siswa_id)->get();
        $siswa = Siswa::findOrFail($siswa_id);

        return view('prestasi.show', compact('prestasi', 'siswa'));
    }

    public function edit($id)
    {
        $prestasi = PrestasiSiswa::findOrFail($id);
        $siswa = Siswa::all();
        return view('prestasi.edit', compact('prestasi', 'siswa'));
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

        PrestasiSiswa::updateOrCreate(
            ['peserta_didik_id' => $request->peserta_didik_id],
            $request->only('jenis_prestasi', 'tingkat_prestasi', 'nama_prestasi', 'tahun_prestasi', 'penyelenggara', 'peringkat')
        );

        return redirect()->route('prestasi.index')->with('success', 'Data prestasi berhasil diperbarui.');
    }


    public function destroy($siswa_id)
    {
        PrestasiSiswa::where('peserta_didik_id', $siswa_id)->delete();
        return redirect()->route('prestasi.index')->with('success', 'Semua data prestasi siswa berhasil dihapus.');
    }
}
