<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AkunPtk;
use App\Models\Ptk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class AkunPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role == 'ptk';
        $isAdmin = $user->role == 'admin';

        $ptkId = null;
        if ($isPtk) {
            $ptkId = DB::table('akun_ptk')
                ->where('email', $user->email)
                ->value('ptk_id');
        }

        if ($isPtk) {
            $data = DB::table('akun_ptk')
                ->join('ptk', 'akun_ptk.ptk_id', '=', 'ptk.id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'akun_ptk.id as akun_id',
                    'akun_ptk.email',
                    'ptk.nama_lengkap',
                    'ptk.id as ptk_id'
                )
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(50);

        } else {
            $data = DB::table('ptk')
                ->leftJoin('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    'akun_ptk.id as akun_id',
                    'akun_ptk.email'
                )
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(50);
        }

        return view('akun-ptk.index', compact('data', 'isPtk', 'isAdmin', 'ptkId'));
    }

    public function search(Request $request)
    {
        $keyword = $request->get('q');

        $data = DB::table('ptk')
            ->where('nama_lengkap', 'LIKE', "%$keyword%")
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        return response()->json($data);
    }

    public function create()
    {
        $ptks = Ptk::all();
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
        return view('akun-ptk.create', compact('ptks', 'prefix'));
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

        $sheet = $spreadsheet->getSheetByName('AkunPTK');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('AkunPTK');
            $sheet->fromArray(['Nama PTK', 'Email', 'Password (Hash)'], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $ptk->nama_lengkap,
            $validated['email'],
            $validated['password'],
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'akun-ptk.index')
                        ->with('success', 'Akun PTK berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';
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

        return view('akun-ptk.edit', [
            'data' => $akun,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
            'ptk' => $ptk,
        ]);
    }

    public function update(Request $request, AkunPtk $akun_ptk)
    {
        $oldEmail = $akun_ptk->email;

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'email' => 'required|email|unique:akun_ptk,email,' . $akun_ptk->id . '|unique:users,email,' . $oldEmail . ',email',
            'password' => 'nullable|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $akun_ptk->update($validated);

        $ptk = Ptk::find($validated['ptk_id']);

        $updateData = [
            'name' => $ptk->nama_lengkap,
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
            $sheet = $spreadsheet->getSheetByName('AkunPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("B{$row}")->getValue() == $oldEmail) {
                        $sheet->setCellValue("A{$row}", $ptk->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['email']);
                        if (isset($validated['password'])) {
                            $sheet->setCellValue("C{$row}", $validated['password']);
                        }
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'akun-ptk.index')
            ->with('success', 'Akun PTK dan user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $akun = AkunPtk::findOrFail($id);

        DB::table('users')->where('email', $akun->email)->delete();

        $akun->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('AkunPTK');

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
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'akun-ptk.index')
            ->with('success', 'Akun PTK berhasil dihapus.');
    }


}
