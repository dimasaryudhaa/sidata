<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semester;

class SemesterController extends Controller
{
    public function index()
    {
        $semester = Semester::orderBy('tahun_ajaran', 'desc')
            ->orderBy('nama_semester', 'asc')
            ->paginate(12);
        return view('semester.index', compact('semester'));
    }

    public function create()
    {
        return view('semester.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_semester' => 'required|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        Semester::create($request->all());
        return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil ditambahkan.');
    }

    public function edit(Semester $semester)
    {
        return view('semester.edit', compact('semester'));
    }

    public function update(Request $request, Semester $semester)
    {
        $request->validate([
            'nama_semester' => 'required|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        $semester->update($request->all());
        return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil diupdate.');
    }

    public function destroy(Semester $semester)
    {
        $semester->delete();
        return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil dihapus.');
    }
}
