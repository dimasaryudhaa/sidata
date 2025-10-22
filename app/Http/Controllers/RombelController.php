<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Models\Jurusan;

class RombelController extends Controller
{
    public function index()
    {
        $rombel = Rombel::with('jurusan')->orderBy('nama_rombel', 'asc')->paginate(12);
        return view('rombel.index', compact('rombel'));
    }

    public function create()
    {
        $jurusan = Jurusan::all(); 
        return view('rombel.create', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jurusan_id' => 'required|integer',
            'nama_rombel' => 'required|string|max:100',
        ]);

        Rombel::create($request->all());
        return redirect()->route('rombel.index')->with('success', 'Rombel berhasil ditambahkan.');
    }

    public function edit(Rombel $rombel)
    {
        $jurusan = Jurusan::all();
        return view('rombel.edit', compact('rombel', 'jurusan'));
    }

    public function update(Request $request, Rombel $rombel)
    {
        $request->validate([
            'jurusan_id' => 'required|integer',
            'nama_rombel' => 'required|string|max:100',
        ]);

        $rombel->update($request->all());
        return redirect()->route('rombel.index')->with('success', 'Rombel berhasil diupdate.');
    }

    public function destroy(Rombel $rombel)
    {
        $rombel->delete();
        return redirect()->route('rombel.index')->with('success', 'Rombel berhasil dihapus.');
    }
}
