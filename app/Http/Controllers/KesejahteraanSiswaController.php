<?php

namespace App\Http\Controllers;

use App\Models\KesejahteraanSiswa;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KesejahteraanSiswaController extends Controller
{
    public function index()
    {
        $kesejahteraan = DB::table('peserta_didik')
            ->leftJoin('kesejahteraan', 'peserta_didik.id', '=', 'kesejahteraan.peserta_didik_id')
            ->select(
                'peserta_didik.id as siswa_id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.rombel_id',
                DB::raw('COUNT(kesejahteraan.id) as jumlah_kesejahteraan')
            )
            ->groupBy('peserta_didik.id', 'peserta_didik.nama_lengkap')
            ->orderBy('peserta_didik.nama_lengkap', 'asc')
            ->paginate(12);

        $rombels = Rombel::orderBy('nama_rombel')->get();

        return view('kesejahteraan-siswa.index', compact('kesejahteraan', 'rombels'));
    }

    public function create(Request $request)
    {
        $siswaId = $request->query('siswa_id');

        if ($siswaId) {
            $siswa = Siswa::findOrFail($siswaId);
            return view('kesejahteraan-siswa.create', compact('siswaId', 'siswa'));
        } else {
            $siswa = Siswa::all();
            return view('kesejahteraan-siswa.create', compact('siswa'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_kesejahteraan' => 'required|in:PKH,PIP,Kartu Perlindungan Sosial,Kartu Keluarga Sejahtera,Kartu Kesehatan',
            'no_kartu' => 'nullable|string|max:50',
            'nama_di_kartu' => 'nullable|string|max:100',
        ]);

        KesejahteraanSiswa::create($request->all());

        return redirect()->route('kesejahteraan-siswa.index')->with('success', 'Data kesejahteraan siswa berhasil ditambahkan.');
    }

    public function show($siswa_id)
    {
        $kesejahteraan = KesejahteraanSiswa::where('peserta_didik_id', $siswa_id)->get();
        $siswa = Siswa::findOrFail($siswa_id);

        return view('kesejahteraan-siswa.show', compact('kesejahteraan', 'siswa'));
    }

    public function edit($siswa_id)
    {
        $kesejahteraan = KesejahteraanSiswa::where('peserta_didik_id', $siswa_id)->first();

        if (!$kesejahteraan) {
            $kesejahteraan = new KesejahteraanSiswa();
            $kesejahteraan->peserta_didik_id = $siswa_id;
        }

        $siswa = Siswa::all();
        return view('kesejahteraan-siswa.edit', compact('kesejahteraan', 'siswa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_kesejahteraan' => 'required|in:PKH,PIP,Kartu Perlindungan Sosial,Kartu Keluarga Sejahtera,Kartu Kesehatan',
            'no_kartu' => 'nullable|string|max:50',
            'nama_di_kartu' => 'nullable|string|max:100',
        ]);

        KesejahteraanSiswa::updateOrCreate(
            ['peserta_didik_id' => $request->peserta_didik_id],
            $request->only('jenis_kesejahteraan', 'no_kartu', 'nama_di_kartu')
        );

        return redirect()->route('kesejahteraan-siswa.index')->with('success', 'Data kesejahteraan siswa berhasil diperbarui.');
    }

    public function destroy($siswa_id)
    {
        KesejahteraanSiswa::where('peserta_didik_id', $siswa_id)->delete();

        return redirect()->route('kesejahteraan-siswa.index')->with('success', 'Semua data kesejahteraan siswa berhasil dihapus.');
    }
}
