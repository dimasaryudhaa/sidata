<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AkunSiswa;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AkunSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';

        if ($isSiswa) {
            $data = DB::table('akun_siswa')
                ->join('peserta_didik', 'akun_siswa.peserta_didik_id', '=', 'peserta_didik.id')
                ->where('akun_siswa.email', $user->email)
                ->select(
                    'akun_siswa.id as akun_id',
                    'akun_siswa.email',
                    'peserta_didik.nama_lengkap',
                    'peserta_didik.id as peserta_didik_id'
                )
                ->orderBy('peserta_didik.nama_lengkap', 'asc')
                ->paginate(12);

            return view('akun-siswa.index', compact('data', 'isSiswa'));
        } else {
            $data = DB::table('peserta_didik')
                ->leftJoin('akun_siswa', 'peserta_didik.id', '=', 'akun_siswa.peserta_didik_id')
                ->select(
                    'peserta_didik.id as peserta_didik_id',
                    'peserta_didik.nama_lengkap',
                    'akun_siswa.id as akun_id',
                    'akun_siswa.email'
                )
                ->orderBy('peserta_didik.nama_lengkap', 'asc')
                ->paginate(12);

            return view('akun-siswa.index', compact('data', 'isSiswa'));
        }
    }

    public function create()
    {
        $siswas = Siswa::all();
        return view('akun-siswa.create', compact('siswas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'email' => 'required|email|unique:akun_siswa,email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $akun = AkunSiswa::create($validated);

        $siswa = Siswa::find($validated['peserta_didik_id']);

        DB::table('users')->insert([
            'name' => $siswa->nama_lengkap,
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'siswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('akun-siswa.index')->with('success', 'Akun siswa berhasil ditambahkan ke tabel users.');
    }

    public function edit($id)
    {
        $akun = AkunSiswa::find($id);

        if (!$akun) {
            $siswa = Siswa::findOrFail($id);
            $existing = AkunSiswa::where('peserta_didik_id', $siswa->id)->first();

            if ($existing) {
                $akun = $existing;
            } else {
                $akun = new AkunSiswa();
                $akun->peserta_didik_id = $siswa->id;
            }
        } else {
            $siswa = Siswa::find($akun->peserta_didik_id);
        }

        $semuaSiswa = Siswa::all();

        return view('akun-siswa.edit', [
            'data' => $akun,
            'siswas' => $semuaSiswa,
        ]);
    }

    public function update(Request $request, AkunSiswa $akun_siswa)
    {
        $oldEmail = $akun_siswa->email;

        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'email' => 'required|email|unique:akun_siswa,email,' . $akun_siswa->id . '|unique:users,email,' . $oldEmail . ',email',
            'password' => 'nullable|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $akun_siswa->update($validated);

        $siswa = Siswa::find($validated['peserta_didik_id']);

        $updateData = [
            'name' => $siswa->nama_lengkap,
            'email' => $validated['email'],
            'updated_at' => now(),
        ];

        if (isset($validated['password'])) {
            $updateData['password'] = $validated['password'];
        }

        DB::table('users')
            ->where('email', $oldEmail)
            ->update($updateData);

        return redirect()->route('akun-siswa.index')->with('success', 'Akun siswa dan user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $akun = AkunSiswa::findOrFail($id);

        DB::table('users')->where('email', $akun->email)->delete();

        $akun->delete();

        return redirect()->route('akun-siswa.index')->with('success', 'Akun siswa dan user berhasil dihapus.');
    }
}
