<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AkunSiswa;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class AkunSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->role == 'admin';
        $isSiswa = $user->role == 'siswa';

        $siswaId = null;
        if ($isSiswa) {
            $siswaId = DB::table('akun_siswa')
                ->where('email', $user->email)
                ->value('peserta_didik_id');
        }

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
        }

        return view('akun-siswa.index', compact('data', 'isSiswa', 'isAdmin', 'siswaId'));
    }

    public function create()
    {
        $siswas = Siswa::all();
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';
        return view('akun-siswa.create', compact('siswas', 'prefix'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'email' => 'required|email|unique:akun_siswa,email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $plainPassword = $validated['password'];

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

        if (!Storage::exists('exports')) {
            Storage::makeDirectory('exports');
        }

        $path = storage_path('app/exports/sidata.xlsx');

        if (!file_exists($path)) {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->removeSheetByIndex(0);
        } else {
            $spreadsheet = IOFactory::load($path);
        }

        $sheet = $spreadsheet->getSheetByName('AkunSISWA');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('AkunSISWA');
            $sheet->fromArray(['Nama Siswa', 'Email', 'Password'], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $siswa->nama_lengkap,
            $validated['email'],
            $plainPassword,
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'akun-siswa.index')
            ->with('success', 'Akun siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isSiswa = $user->role === 'siswa';

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

        return view('akun-siswa.edit', [
            'data' => $akun,
            'isAdmin' => $isAdmin,
            'isSiswa' => $isSiswa,
            'siswa' => $siswa,
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

        $plainPassword = null;

        if ($request->filled('password')) {
            $plainPassword = $request->password;
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

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('AkunSISWA');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("B{$row}")->getValue() == $oldEmail) {
                        $sheet->setCellValue("A{$row}", $siswa->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['email']);

                        if ($plainPassword !== null) {
                            $sheet->setCellValue("C{$row}", $plainPassword);
                        }

                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()
            ->route($prefix.'akun-siswa.index')
            ->with('success', 'Akun siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $akun = AkunSiswa::findOrFail($id);

        DB::table('users')->where('email', $akun->email)->delete();
        $akun->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('AkunSISWA');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("B{$row}")->getValue() == $akun->email) {
                        $sheet->removeRow($row, 1);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'akun-siswa.index')
            ->with('success', 'Akun siswa berhasil dihapus.');
    }
}
