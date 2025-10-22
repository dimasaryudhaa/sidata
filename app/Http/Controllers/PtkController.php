<?php

namespace App\Http\Controllers;

use App\Models\Ptk;
use Illuminate\Http\Request;

class PtkController extends Controller
{
    public function index()
    {
        $ptks = Ptk::orderBy('nama_lengkap')->paginate(12);
        return view('ptk.index', compact('ptks'));
    }

    public function create()
    {
        return view('ptk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:ptk,nik',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'nama_ibu_kandung' => 'required|string|max:255',
        ]);

        Ptk::create($request->all());

        return redirect()->route('ptk.index')->with('success', 'Data PTK berhasil ditambahkan!');
    }

    public function show(Ptk $ptk)
    {
        return view('ptk.show', compact('ptk'));
    }

    public function edit(Ptk $ptk)
    {
        return view('ptk.edit', compact('ptk'));
    }

    public function update(Request $request, Ptk $ptk)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:ptk,nik,' . $ptk->id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'nama_ibu_kandung' => 'required|string|max:255',
        ]);

        $ptk->update($request->all());

        return redirect()->route('ptk.index')->with('success', 'Data PTK berhasil diperbarui!');
    }

    public function destroy(Ptk $ptk)
    {
        $ptk->delete();
        return redirect()->route('ptk.index')->with('success', 'Data PTK berhasil dihapus!');
    }
}
