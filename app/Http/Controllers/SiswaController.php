<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\Rayon;
use App\Models\Rombel;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::orderBy('nama_lengkap')
        ->paginate(12);

        $rombels = Rombel::orderBy('nama_rombel')->get();

        return view('siswa.index', compact('siswa', 'rombels'));
    }

    public function create()
    {
        $rayons = Rayon::all();
        $rombels = Rombel::all();
        return view('siswa.create', compact('rayons', 'rombels'));
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

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function show(Siswa $siswa)
    {
        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $siswa->update($request->all());
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus!');
    }
}
