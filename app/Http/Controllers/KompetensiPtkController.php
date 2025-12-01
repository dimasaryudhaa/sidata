<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KompetensiPtk;
use App\Models\Ptk;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class KompetensiPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $kompetensiPtk = DB::table('kompetensi')
                ->join('ptk', 'kompetensi.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'kompetensi.id as kompetensi_id',
                    'ptk.id as ptk_id',
                    'kompetensi.bidang_studi',
                    'kompetensi.urutan'
                )
                ->orderBy('kompetensi.urutan', 'asc')
                ->paginate(12);
        } else {
            $kompetensiPtk = DB::table('ptk')
                ->leftJoin('kompetensi', 'ptk.id', '=', 'kompetensi.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(kompetensi.id) as jumlah_kompetensi')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(50);
        }

        return view('kompetensi-ptk.index', compact('kompetensiPtk', 'isPtk', 'isAdmin', 'prefix'));
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

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptk = Ptk::findOrFail($ptk_id);
        $kompetensi = KompetensiPtk::where('ptk_id', $ptk_id)->orderBy('urutan', 'asc')->get();

        return view('kompetensi-ptk.show', compact('ptk', 'kompetensi', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('kompetensi-ptk.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('kompetensi-ptk.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'bidang_studi' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
        ]);

        $kompetensi = KompetensiPtk::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('KompetensiPTK');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('KompetensiPTK');
            $sheet->fromArray([
                'Nama PTK', 'Bidang Studi', 'Urutan'
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $ptk->nama_lengkap,
            $validated['bidang_studi'],
            $validated['urutan'],
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'kompetensi-ptk.index')
            ->with('success', 'Data kompetensi PTK berhasil ditambahkan.');
    }

    public function edit(KompetensiPtk $kompetensiPtk)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
        $ptk = Ptk::findOrFail($kompetensiPtk->ptk_id);

        return view('kompetensi-ptk.edit', compact('kompetensiPtk', 'ptk', 'prefix'));
    }

    public function update(Request $request, KompetensiPtk $kompetensiPtk)
    {
        $oldBidang = $kompetensiPtk->bidang_studi;

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'bidang_studi' => 'required|string|max:255',
            'urutan' => 'nullable|integer',
        ]);

        $kompetensiPtk->update($validated);
        $ptk = Ptk::find($validated['ptk_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('KompetensiPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("B{$row}")->getValue() == $oldBidang) {
                        $sheet->setCellValue("A{$row}", $ptk->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['bidang_studi']);
                        $sheet->setCellValue("C{$row}", $validated['urutan']);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'kompetensi-ptk.index')
            ->with('success', 'Data kompetensi PTK berhasil diperbarui.');
    }

    public function destroy(KompetensiPtk $kompetensiPtk)
    {
        $oldBidang = $kompetensiPtk->bidang_studi;
        $kompetensiPtk->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('KompetensiPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("B{$row}")->getValue() == $oldBidang) {
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
            ->route($prefix.'kompetensi-ptk.index')
            ->with('success', 'Data kompetensi PTK berhasil dihapus.');
    }

}
