<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rayon;
use App\Models\Ptk;

class RayonController extends Controller
{
    public function index()
    {
        $rayon = Rayon::with('ptk')
            ->orderBy('nama_rayon', 'asc')
            ->paginate(12);

        return view('rayon.index', compact('rayon'));
    }

    public function create()
    {
        $ptks = Ptk::orderBy('nama_lengkap', 'asc')->get();

        return view('rayon.create', compact('ptks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ptk_id' => 'required|integer',
            'nama_rayon' => 'required|string|max:255',
        ]);

        Rayon::create($request->all());

        return redirect()->route('admin.rayon.index')
            ->with('success', 'Rayon berhasil ditambahkan.');
    }

    public function edit(Rayon $rayon)
    {
        $ptks = Ptk::orderBy('nama_lengkap', 'asc')->get();

        return view('rayon.edit', compact('rayon', 'ptks'));
    }

    public function update(Request $request, Rayon $rayon)
    {
        $request->validate([
            'ptk_id' => 'required|integer',
            'nama_rayon' => 'required|string|max:255',
        ]);

        $rayon->update($request->all());

        return redirect()->route('admin.rayon.index')
            ->with('success', 'Rayon berhasil diupdate.');
    }

    public function destroy(Rayon $rayon)
    {
        $rayon->delete();

        return redirect()->route('admin.rayon.index')
            ->with('success', 'Rayon berhasil dihapus.');
    }
}
