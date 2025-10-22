<?php

namespace App\Http\Controllers;
use App\Models\BeasiswaSiswa;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeasiswaSiswaController extends Controller
{
    public function index()
    {
        $beasiswa = DB::table('peserta_didik')
            ->leftJoin('beasiswa', 'peserta_didik.id', '=', 'beasiswa.peserta_didik_id')
            ->select(
                'peserta_didik.id as siswa_id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.rombel_id',
                DB::raw('COUNT(beasiswa.id) as jumlah_beasiswa')
            )
            ->groupBy('peserta_didik.id', 'peserta_didik.nama_lengkap')
            ->orderBy('peserta_didik.nama_lengkap', 'asc')
            ->paginate(12);

        $rombels = Rombel::orderBy('nama_rombel')->get();

        return view('beasiswa.index', compact('beasiswa', 'rombels'));
    }

    public function create(Request $request)
    {
        $siswaId = $request->query('siswa_id');

        if ($siswaId) {
            $siswa = Siswa::findOrFail($siswaId);
            return view('beasiswa.create', compact('siswaId', 'siswa'));
        } else {
            $siswa = Siswa::all();
            return view('beasiswa.create', compact('siswa'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_beasiswa' => 'required|in:Anak Berprestasi,Anak Miskin,Pendidikan',
            'keterangan' => 'nullable|string|max:255',
            'tahun_mulai' => 'nullable|digits:4|integer',
            'tahun_selesai' => 'nullable|digits:4|integer',
        ]);

        BeasiswaSiswa::create($request->all());

        return redirect()->route('beasiswa.index')->with('success', 'Data beasiswa berhasil ditambahkan.');
    }

    public function show($siswa_id)
    {
        $beasiswa = BeasiswaSiswa::where('peserta_didik_id', $siswa_id)->get(); 
        $siswa = Siswa::findOrFail($siswa_id);

        return view('beasiswa.show', compact('beasiswa', 'siswa'));
    }

    public function edit($siswa_id)
    {
        $beasiswa = BeasiswaSiswa::where('peserta_didik_id', $siswa_id)->first();

        if (!$beasiswa) {
            $beasiswa = new BeasiswaSiswa();
            $beasiswa->peserta_didik_id = $siswa_id;
        }

        $siswa = Siswa::all();
        return view('beasiswa.edit', compact('beasiswa', 'siswa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_beasiswa' => 'required|in:Anak Berprestasi,Anak Miskin,Pendidikan',
            'keterangan' => 'nullable|string|max:255',
            'tahun_mulai' => 'nullable|digits:4|integer',
            'tahun_selesai' => 'nullable|digits:4|integer',
        ]);

        BeasiswaSiswa::updateOrCreate(
            ['peserta_didik_id' => $request->peserta_didik_id],
            $request->only('jenis_beasiswa', 'keterangan', 'tahun_mulai', 'tahun_selesai')
        );

        return redirect()->route('beasiswa.index')->with('success', 'Data beasiswa berhasil diperbarui.');
    }

    public function destroy($siswa_id)
    {
        BeasiswaSiswa::where('peserta_didik_id', $siswa_id)->delete();

        return redirect()->route('beasiswa.index')->with('success', 'Semua data beasiswa siswa berhasil dihapus.');
    }

}

