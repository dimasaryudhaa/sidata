<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Penghargaan;
use App\Models\Ptk;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class PenghargaanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $penghargaanPtk = DB::table('penghargaan')
                ->join('ptk', 'penghargaan.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'penghargaan.id as penghargaan_id',
                    'ptk.id as ptk_id',
                    'penghargaan.tingkat_penghargaan',
                    'penghargaan.jenis_penghargaan',
                    'penghargaan.nama_penghargaan',
                    'penghargaan.tahun',
                    'penghargaan.instansi'
                )
                ->orderBy('penghargaan.tahun', 'desc')
                ->paginate(12);
        } else {
            $penghargaanPtk = DB::table('ptk')
                ->leftJoin('penghargaan', 'ptk.id', '=', 'penghargaan.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(penghargaan.id) as jumlah_penghargaan')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);
        }

        return view('penghargaan.index', compact('penghargaanPtk', 'isPtk', 'isAdmin', 'prefix'));
    }

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptk = Ptk::findOrFail($ptk_id);
        $penghargaan = Penghargaan::where('ptk_id', $ptk_id)->get();

        return view('penghargaan.show', compact('ptk', 'penghargaan', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('penghargaan.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('penghargaan.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'tingkat_penghargaan' => 'required|string|max:100',
            'jenis_penghargaan' => 'required|string|max:100',
            'nama_penghargaan' => 'required|string|max:150',
            'tahun' => 'required|digits:4|integer',
            'instansi' => 'required|string|max:150',
        ]);

        $penghargaan = Penghargaan::create($validated);
        $ptk = Ptk::find($validated['ptk_id']);

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

        $sheet = $spreadsheet->getSheetByName('PenghargaanPTK');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('PenghargaanPTK');
            $sheet->fromArray([
                'Nama PTK', 'Tingkat Penghargaan', 'Jenis Penghargaan',
                'Nama Penghargaan', 'Tahun', 'Instansi'
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $ptk->nama_lengkap,
            $validated['tingkat_penghargaan'],
            $validated['jenis_penghargaan'],
            $validated['nama_penghargaan'],
            $validated['tahun'],
            $validated['instansi'],
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'penghargaan.index')
            ->with('success', 'Data penghargaan berhasil ditambahkan.');
    }

    public function edit(Penghargaan $penghargaan)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
        $ptk = Ptk::findOrFail($penghargaan->ptk_id);

        return view('penghargaan.edit', compact('penghargaan', 'ptk', 'prefix'));
    }

    public function update(Request $request, Penghargaan $penghargaan)
    {
        $oldTahun = $penghargaan->tahun;

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'tingkat_penghargaan' => 'required|string|max:100',
            'jenis_penghargaan' => 'required|string|max:100',
            'nama_penghargaan' => 'required|string|max:150',
            'tahun' => 'required|digits:4|integer',
            'instansi' => 'required|string|max:150',
        ]);

        $penghargaan->update($validated);
        $ptk = Ptk::find($validated['ptk_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('PenghargaanPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("D{$row}")->getValue() == $oldTahun) {
                        $sheet->setCellValue("A{$row}", $ptk->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['tingkat_penghargaan']);
                        $sheet->setCellValue("C{$row}", $validated['jenis_penghargaan']);
                        $sheet->setCellValue("D{$row}", $validated['nama_penghargaan']);
                        $sheet->setCellValue("E{$row}", $validated['tahun']);
                        $sheet->setCellValue("F{$row}", $validated['instansi']);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'penghargaan.index')
            ->with('success', 'Data penghargaan berhasil diperbarui.');
    }

    public function destroy(Penghargaan $penghargaan)
    {
        $oldTahun = $penghargaan->tahun;
        $penghargaan->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('PenghargaanPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("E{$row}")->getValue() == $oldTahun) {
                        $sheet->removeRow($row, 1);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'penghargaan.index')
            ->with('success', 'Data penghargaan berhasil dihapus.');
    }

}
