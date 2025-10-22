<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AkunPtk;
use App\Models\Ptk;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AkunPtkController extends Controller
{
    public function index()
    {
        $data = DB::table('ptk')
            ->leftJoin('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
            ->select(
                'ptk.id as ptk_id',
                'ptk.nama_lengkap',
                'akun_ptk.id as akun_id',
                'akun_ptk.email'
            )
            ->orderBy('ptk.nama_lengkap', 'asc')
            ->paginate(12);

        return view('akun-ptk.index', compact('data'));
    }

    public function create()
    {
        $ptks = Ptk::all();
        return view('akun-ptk.create', compact('ptks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'email' => 'required|email|unique:akun_ptk,email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $akun = AkunPtk::create($validated);

        $ptk = Ptk::find($validated['ptk_id']);

        DB::table('users')->insert([
            'name' => $ptk->nama_lengkap,
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'ptk',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('akun-ptk.index')->with('success', 'Akun PTK berhasil ditambahkan ke tabel users.');
    }

    public function edit($id)
    {
        $akun = AkunPtk::find($id);

        if (!$akun) {
            $ptk = Ptk::findOrFail($id);
            $existing = AkunPtk::where('ptk_id', $ptk->id)->first();

            if ($existing) {
                $akun = $existing;
            } else {
                $akun = new AkunPtk();
                $akun->ptk_id = $ptk->id;
            }
        } else {
            $ptk = Ptk::find($akun->ptk_id);
        }

        $semuaPtk = Ptk::all();

        return view('akun-ptk.edit', [
            'data' => $akun,
            'ptks' => $semuaPtk,
        ]);
    }

    public function update(Request $request, AkunPtk $akun_ptk)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'email' => 'required|email|unique:akun_ptk,email,' . $akun_ptk->id . '|unique:users,email,' . $akun_ptk->email . ',email',
            'password' => 'nullable|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $akun_ptk->update($validated);

        $ptk = Ptk::find($validated['ptk_id']);

        DB::table('users')
            ->where('email', $akun_ptk->email)
            ->update([
                'name' => $ptk->nama_lengkap,
                'email' => $validated['email'],
                'password' => $validated['password'] ?? DB::raw('password'),
                'updated_at' => now(),
            ]);

        return redirect()->route('akun-ptk.index')->with('success', 'Akun PTK dan user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $akun = AkunPtk::findOrFail($id);

        DB::table('users')->where('email', $akun->email)->delete();

        $akun->delete();

        return redirect()->route('akun-ptk.index')->with('success', 'Akun PTK dan user berhasil dihapus.');
    }

}
