<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatJabatan;
use App\Models\Ptk;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class RiwayatJabatanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $riwayatJabatan = DB::table('riwayat_jabatan')
                ->join('ptk', 'riwayat_jabatan.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'riwayat_jabatan.id as riwayat_jabatan_id',
                    'ptk.id as ptk_id',
                    'riwayat_jabatan.jabatan_ptk',
                    'riwayat_jabatan.sk_jabatan',
                    'riwayat_jabatan.tmt_jabatan'
                )
                ->orderBy('riwayat_jabatan.tmt_jabatan', 'desc')
                ->paginate(12);

        } else {
            $riwayatJabatan = DB::table('ptk')
                ->leftJoin('riwayat_jabatan', 'ptk.id', '=', 'riwayat_jabatan.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(riwayat_jabatan.id) as jumlah_riwayat_jabatan')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap')
                ->paginate(12);
        }

        return view('riwayat-jabatan.index', compact('riwayatJabatan', 'isPtk', 'isAdmin', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('riwayat-jabatan.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('riwayat-jabatan.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_ptk' => 'required|string|max:150',
            'sk_jabatan' => 'required|string|max:100',
            'tmt_jabatan' => 'required|date',
        ]);

        $riwayatJabatan = RiwayatJabatan::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('RiwayatJabatan');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('RiwayatJabatan');
            $sheet->fromArray(['Nama PTK', 'Jabatan PTK', 'SK Jabatan', 'TMT Jabatan'], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $ptk->nama_lengkap,
            $validated['jabatan_ptk'],
            $validated['sk_jabatan'],
            $validated['tmt_jabatan'],
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'riwayat-jabatan.index')
            ->with('success', 'Data riwayat jabatan berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $riwayatJabatan = RiwayatJabatan::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('riwayat-jabatan.show', compact('riwayatJabatan', 'ptk', 'prefix'));
    }

    public function edit(RiwayatJabatan $riwayatJabatan)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        $ptks = Ptk::orderBy('nama_lengkap')->get();

        return view('riwayat-jabatan.edit', compact(
            'riwayatJabatan',
            'ptks',
            'isAdmin',
            'isPtk',
            'prefix'
        ));
    }

    public function update(Request $request, RiwayatJabatan $riwayatJabatan)
    {
        $oldSK = $riwayatJabatan->sk_jabatan;

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jabatan_ptk' => 'required|string|max:150',
            'sk_jabatan' => 'required|string|max:100',
            'tmt_jabatan' => 'required|date',
        ]);

        $riwayatJabatan->update($validated);
        $ptk = Ptk::find($validated['ptk_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('RiwayatJabatan');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("C{$row}")->getValue() == $oldSK) {
                        $sheet->setCellValue("A{$row}", $ptk->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['jabatan_ptk']);
                        $sheet->setCellValue("C{$row}", $validated['sk_jabatan']);
                        $sheet->setCellValue("D{$row}", $validated['tmt_jabatan']);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'riwayat-jabatan.index')
            ->with('success', 'Data riwayat jabatan berhasil diperbarui.');
    }

    public function destroy(RiwayatJabatan $riwayatJabatan)
    {
        $oldSK = $riwayatJabatan->sk_jabatan;
        $riwayatJabatan->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('RiwayatJabatan');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("C{$row}")->getValue() == $oldSK) {
                        $sheet->removeRow($row, 1);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'riwayat-jabatan.index')
            ->with('success', 'Data riwayat jabatan berhasil dihapus.');
    }

}
