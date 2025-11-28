<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PendidikanPtk;
use App\Models\Ptk;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class PendidikanPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $pendidikanPtk = DB::table('pendidikan_ptk')
                ->join('ptk', 'pendidikan_ptk.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'pendidikan_ptk.id as pendidikan_id',
                    'ptk.id as ptk_id',
                    'pendidikan_ptk.bidang_studi',
                    'pendidikan_ptk.jenjang_pendidikan',
                    'pendidikan_ptk.gelar_akademik',
                    'pendidikan_ptk.satuan_pendidikan_formal',
                    'pendidikan_ptk.fakultas',
                    'pendidikan_ptk.kependidikan',
                    'pendidikan_ptk.tahun_masuk',
                    'pendidikan_ptk.tahun_lulus',
                    'pendidikan_ptk.nomor_induk',
                    'pendidikan_ptk.masih_studi',
                    'pendidikan_ptk.semester',
                    'pendidikan_ptk.rata_rata_ujian'
                )
                ->orderBy('pendidikan_ptk.tahun_lulus', 'desc')
                ->paginate(12);

        } else {
            $pendidikanPtk = DB::table('ptk')
                ->leftJoin('pendidikan_ptk', 'ptk.id', '=', 'pendidikan_ptk.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(pendidikan_ptk.id) as jumlah_pendidikan')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);
        }

        return view('pendidikan-ptk.index', compact('pendidikanPtk', 'isPtk', 'isAdmin', 'prefix'));
    }

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptk = Ptk::findOrFail($ptk_id);
        $pendidikan = PendidikanPtk::where('ptk_id', $ptk_id)->get();

        return view('pendidikan-ptk.show', compact('ptk', 'pendidikan', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('pendidikan-ptk.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('pendidikan-ptk.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'bidang_studi' => 'required|string|max:255',
            'jenjang_pendidikan' => 'required|string|max:100',
            'gelar_akademik' => 'required|string|max:100',
            'satuan_pendidikan_formal' => 'required|string|max:255',
            'fakultas' => 'required|string|max:255',
            'kependidikan' => 'required|in:Ya,Tidak',
            'tahun_masuk' => 'required|digits:4',
            'tahun_lulus' => 'required|digits:4',
            'nomor_induk' => 'required|string|max:50',
            'masih_studi' => 'required|boolean',
            'semester' => 'required|integer|min:1',
            'rata_rata_ujian' => 'required|numeric|between:0,100',
        ]);

        $pendidikan = PendidikanPtk::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('PendidikanPTK');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('PendidikanPTK');
            $sheet->fromArray([
                'Nama PTK', 'Bidang Studi', 'Jenjang Pendidikan', 'Gelar Akademik',
                'Satuan Pendidikan Formal', 'Fakultas', 'Kependidikan',
                'Tahun Masuk', 'Tahun Lulus', 'Nomor Induk', 'Masih Studi',
                'Semester', 'Rata-rata Ujian'
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $ptk->nama_lengkap,
            $validated['bidang_studi'],
            $validated['jenjang_pendidikan'],
            $validated['gelar_akademik'],
            $validated['satuan_pendidikan_formal'],
            $validated['fakultas'],
            $validated['kependidikan'],
            $validated['tahun_masuk'],
            $validated['tahun_lulus'],
            $validated['nomor_induk'],
            $validated['masih_studi'],
            $validated['semester'],
            $validated['rata_rata_ujian'],
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'pendidikan-ptk.index')
            ->with('success', 'Data pendidikan PTK berhasil ditambahkan.');
    }

    public function edit(PendidikanPtk $pendidikanPtk)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        $ptks = Ptk::orderBy('nama_lengkap')->get();
        $ptk = Ptk::findOrFail($pendidikanPtk->ptk_id);

        return view('pendidikan-ptk.edit', compact(
            'pendidikanPtk',
            'ptks',
            'ptk',
            'isAdmin',
            'isPtk',
            'prefix'
        ));
    }

    public function update(Request $request, PendidikanPtk $pendidikanPtk)
    {
        $oldNo = $pendidikanPtk->nomor_induk;

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'bidang_studi' => 'required|string|max:255',
            'jenjang_pendidikan' => 'required|string|max:100',
            'gelar_akademik' => 'required|string|max:100',
            'satuan_pendidikan_formal' => 'required|string|max:255',
            'fakultas' => 'required|string|max:255',
            'kependidikan' => 'required|in:Ya,Tidak',
            'tahun_masuk' => 'required|digits:4',
            'tahun_lulus' => 'required|digits:4',
            'nomor_induk' => 'required|string|max:50',
            'masih_studi' => 'required|boolean',
            'semester' => 'required|integer|min:1',
            'rata_rata_ujian' => 'required|numeric|between:0,100',
        ]);

        $pendidikanPtk->update($validated);
        $ptk = Ptk::find($validated['ptk_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('PendidikanPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("J{$row}")->getValue() == $oldNo) {
                        $sheet->setCellValue("A{$row}", $ptk->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['bidang_studi']);
                        $sheet->setCellValue("C{$row}", $validated['jenjang_pendidikan']);
                        $sheet->setCellValue("D{$row}", $validated['gelar_akademik']);
                        $sheet->setCellValue("E{$row}", $validated['satuan_pendidikan_formal']);
                        $sheet->setCellValue("F{$row}", $validated['fakultas']);
                        $sheet->setCellValue("G{$row}", $validated['kependidikan']);
                        $sheet->setCellValue("H{$row}", $validated['tahun_masuk']);
                        $sheet->setCellValue("I{$row}", $validated['tahun_lulus']);
                        $sheet->setCellValue("J{$row}", $validated['nomor_induk']);
                        $sheet->setCellValue("K{$row}", $validated['masih_studi']);
                        $sheet->setCellValue("L{$row}", $validated['semester']);
                        $sheet->setCellValue("M{$row}", $validated['rata_rata_ujian']);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'pendidikan-ptk.index')
            ->with('success', 'Data pendidikan PTK berhasil diperbarui.');
    }

    public function destroy(PendidikanPtk $pendidikanPtk)
    {
        $oldNo = $pendidikanPtk->nomor_induk;
        $pendidikanPtk->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('PendidikanPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("J{$row}")->getValue() == $oldNo) {
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
            ->route($prefix.'pendidikan-ptk.index')
            ->with('success', 'Data pendidikan PTK berhasil dihapus.');
    }

}
