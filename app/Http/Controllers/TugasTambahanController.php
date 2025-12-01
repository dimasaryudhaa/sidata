<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TugasTambahan;
use App\Models\Ptk;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class TugasTambahanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $tugasTambahan = DB::table('tugas_tambahan')
                ->join('ptk', 'tugas_tambahan.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'tugas_tambahan.id as tugas_tambahan_id',
                    'ptk.id as ptk_id',
                    'tugas_tambahan.jabatan_ptk',
                    'tugas_tambahan.prasarana',
                    'tugas_tambahan.nomor_sk',
                    'tugas_tambahan.tmt_tambahan',
                    'tugas_tambahan.tst_tambahan'
                )
                ->orderBy('tugas_tambahan.tmt_tambahan', 'desc')
                ->paginate(12);

        } else {
            $tugasTambahan = DB::table('ptk')
                ->leftJoin('tugas_tambahan', 'ptk.id', '=', 'tugas_tambahan.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(tugas_tambahan.id) as jumlah_tugas_tambahan')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(50);
        }

        return view('tugas-tambahan.index', compact('tugasTambahan', 'isPtk', 'isAdmin', 'prefix'));
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

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('tugas-tambahan.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('tugas-tambahan.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_ptk' => 'required|string|max:100',
            'prasarana' => 'nullable|string|max:100',
            'nomor_sk' => 'required|string|min:10|max:20',
            'tmt_tambahan' => 'required|date',
            'tst_tambahan' => 'nullable|date',
        ]);

        $tugasTambahan = TugasTambahan::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('TugasTambahan');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('TugasTambahan');
            $sheet->fromArray([
                'Nama PTK', 'Jabatan PTK', 'Prasarana', 'Nomor SK', 'TMT Tambahan', 'TST Tambahan'
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $ptk->nama_lengkap,
            $validated['jabatan_ptk'],
            $validated['prasarana'] ?? '',
            $validated['nomor_sk'],
            $validated['tmt_tambahan'],
            $validated['tst_tambahan'] ?? '',
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'tugas-tambahan.index')
            ->with('success', 'Data tugas tambahan berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $tugasTambahan = TugasTambahan::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('tugas-tambahan.show', compact('tugasTambahan', 'ptk'));
    }

    public function edit(TugasTambahan $tugasTambahan)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

        $ptks = Ptk::orderBy('nama_lengkap')->get();

        return view('tugas-tambahan.edit', [
            'tugasTambahan' => $tugasTambahan,
            'ptks' => $ptks,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
        ]);
    }

    public function update(Request $request, TugasTambahan $tugasTambahan)
    {
        $oldNomor = $tugasTambahan->nomor_sk;

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_ptk' => 'required|string|max:100',
            'prasarana' => 'nullable|string|max:100',
            'nomor_sk' => 'required|string|min:10|max:20',
            'tmt_tambahan' => 'required|date',
            'tst_tambahan' => 'nullable|date',
        ]);

        $tugasTambahan->update($validated);
        $ptk = Ptk::find($validated['ptk_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('TugasTambahan');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("D{$row}")->getValue() == $oldNomor) {
                        $sheet->setCellValue("A{$row}", $ptk->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['jabatan_ptk']);
                        $sheet->setCellValue("C{$row}", $validated['prasarana'] ?? '');
                        $sheet->setCellValue("D{$row}", $validated['nomor_sk']);
                        $sheet->setCellValue("E{$row}", $validated['tmt_tambahan']);
                        $sheet->setCellValue("F{$row}", $validated['tst_tambahan'] ?? '');
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'tugas-tambahan.index')
            ->with('success', 'Data tugas tambahan berhasil diperbarui.');
    }

    public function destroy(TugasTambahan $tugasTambahan)
    {
    $oldNomor = $tugasTambahan->nomor_sk;
    $tugasTambahan->delete();

    $path = storage_path('app/exports/sidata.xlsx');

    if (file_exists($path)) {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getSheetByName('TugasTambahan');

        if ($sheet) {
            $highestRow = $sheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($sheet->getCell("D{$row}")->getValue() == $oldNomor) {
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
        ->route($prefix.'tugas-tambahan.index')
        ->with('success', 'Data tugas tambahan berhasil dihapus.');
    }

}
