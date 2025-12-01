<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SertifikatPtk;
use App\Models\Ptk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class SertifikatPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $sertifikatPtk = DB::table('sertifikat')
                ->join('ptk', 'sertifikat.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'sertifikat.id as sertifikat_id',
                    'ptk.id as ptk_id',
                    'sertifikat.jenis_sertifikasi',
                    'sertifikat.nomor_sertifikat',
                    'sertifikat.tahun_sertifikasi',
                    'sertifikat.bidang_studi',
                    'sertifikat.nrg',
                    'sertifikat.nomor_peserta'
                )
                ->orderBy('sertifikat.tahun_sertifikasi', 'desc')
                ->paginate(12);

        } else {
            $sertifikatPtk = DB::table('ptk')
                ->leftJoin('sertifikat', 'ptk.id', '=', 'sertifikat.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(sertifikat.id) as jumlah_sertifikat')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(50);
        }

        return view('sertifikat-ptk.index', compact('sertifikatPtk', 'isPtk', 'isAdmin', 'prefix'));
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
        $sertifikat = SertifikatPtk::where('ptk_id', $ptk_id)->get();

        return view('sertifikat-ptk.show', compact('ptk', 'sertifikat', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('sertifikat-ptk.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('sertifikat-ptk.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_sertifikasi' => 'required|string|max:255',
            'nomor_sertifikat' => 'required|string|max:100',
            'tahun_sertifikasi' => 'required|digits:4|integer',
            'bidang_studi' => 'required|string|max:255',
            'nrg' => 'required|string|max:50',
            'nomor_peserta' => 'required|string|max:100',
        ]);

        $sertifikat = SertifikatPtk::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('SertifikatPTK');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('SertifikatPTK');
            $sheet->fromArray([
                'Nama PTK', 'Jenis Sertifikasi', 'Nomor Sertifikat',
                'Tahun Sertifikasi', 'Bidang Studi', 'NRG', 'Nomor Peserta'
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $ptk->nama_lengkap,
            $validated['jenis_sertifikasi'],
            $validated['nomor_sertifikat'],
            $validated['tahun_sertifikasi'],
            $validated['bidang_studi'],
            $validated['nrg'],
            $validated['nomor_peserta'],
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'sertifikat-ptk.index')
            ->with('success', 'Data sertifikat PTK berhasil ditambahkan.');
    }

    public function edit(SertifikatPtk $sertifikatPtk)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        $ptk = Ptk::findOrFail($sertifikatPtk->ptk_id);

        return view('sertifikat-ptk.edit', compact(
            'sertifikatPtk',
            'ptk',
            'isAdmin',
            'isPtk',
            'prefix'
        ));
    }

    public function update(Request $request, SertifikatPtk $sertifikatPtk)
    {
        $oldNo = $sertifikatPtk->nomor_peserta;

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_sertifikasi' => 'required|string|max:255',
            'nomor_sertifikat' => 'required|string|max:100',
            'tahun_sertifikasi' => 'required|digits:4|integer',
            'bidang_studi' => 'required|string|max:255',
            'nrg' => 'required|string|max:50',
            'nomor_peserta' => 'required|string|max:100',
        ]);

        $sertifikatPtk->update($validated);
        $ptk = Ptk::find($validated['ptk_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('SertifikatPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("G{$row}")->getValue() == $oldNo) {
                        $sheet->setCellValue("A{$row}", $ptk->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['jenis_sertifikasi']);
                        $sheet->setCellValue("C{$row}", $validated['nomor_sertifikat']);
                        $sheet->setCellValue("D{$row}", $validated['tahun_sertifikasi']);
                        $sheet->setCellValue("E{$row}", $validated['bidang_studi']);
                        $sheet->setCellValue("F{$row}", $validated['nrg']);
                        $sheet->setCellValue("G{$row}", $validated['nomor_peserta']);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'sertifikat-ptk.index')
            ->with('success', 'Data sertifikat PTK berhasil diperbarui.');
    }

    public function destroy(SertifikatPtk $sertifikatPtk)
    {
        $oldNo = $sertifikatPtk->nomor_peserta;
        $sertifikatPtk->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('SertifikatPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("G{$row}")->getValue() == $oldNo) {
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
            ->route($prefix.'sertifikat-ptk.index')
            ->with('success', 'Data sertifikat PTK berhasil dihapus.');
    }

}
