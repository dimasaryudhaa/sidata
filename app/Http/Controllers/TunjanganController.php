<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Tunjangan;
use App\Models\Ptk;
use App\Models\Semester;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class TunjanganController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';

        $ptkId = null;
        if ($isPtk) {
            $ptkId = DB::table('akun_ptk')
                ->where('email', $user->email)
                ->value('ptk_id');
        }

        if ($isPtk) {
            $tunjangan = DB::table('tunjangan')
                ->join('ptk', 'tunjangan.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'tunjangan.id as tunjangan_id',
                    'tunjangan.ptk_id',
                    'tunjangan.jenis_tunjangan',
                    'tunjangan.nama_tunjangan',
                    'tunjangan.instansi',
                    'tunjangan.sk_tunjangan',
                    'tunjangan.tgl_sk_tunjangan',
                    'tunjangan.semester_id',
                    'tunjangan.sumber_dana',
                    'tunjangan.dari_tahun',
                    'tunjangan.sampai_tahun',
                    'tunjangan.nominal',
                    'tunjangan.status'
                )
                ->orderBy('tunjangan.nama_tunjangan', 'asc')
                ->paginate(12);
        } else {
            $tunjangan = DB::table('ptk')
                ->leftJoin('tunjangan', 'ptk.id', '=', 'tunjangan.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(tunjangan.id) as jumlah_tunjangan')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);
        }

        return view('tunjangan.index', compact('tunjangan', 'isPtk', 'isAdmin', 'ptkId'));
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

        $semesters = Semester::orderBy('nama_semester')->get();

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('tunjangan.create', compact('ptkId', 'ptk', 'semesters', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('tunjangan.create', compact('ptks', 'semesters', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_tunjangan' => 'required|string|max:100',
            'nama_tunjangan' => 'required|string|max:150',
            'instansi' => 'nullable|string|max:150',
            'sk_tunjangan' => 'nullable|string|max:100',
            'tgl_sk_tunjangan' => 'nullable|date',
            'semester_id' => 'required|exists:semester,id',
            'sumber_dana' => 'nullable|string|max:100',
            'dari_tahun' => 'nullable|integer|min:1900|max:2100',
            'sampai_tahun' => 'nullable|integer|min:1900|max:2100',
            'nominal' => 'nullable|numeric|min:0',
            'status' => 'required|in:Masih Menerima,Tidak Menerima',
        ]);

        $tunjangan = Tunjangan::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('Tunjangan');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('Tunjangan');
            $sheet->fromArray(['Nama PTK', 'Jenis Tunjangan', 'Nama Tunjangan', 'Instansi', 'SK Tunjangan', 'Tanggal SK', 'Semester', 'Sumber Dana', 'Dari Tahun', 'Sampai Tahun', 'Nominal', 'Status'], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $ptk->nama_lengkap,
            $validated['jenis_tunjangan'],
            $validated['nama_tunjangan'],
            $validated['instansi'] ?? '',
            $validated['sk_tunjangan'] ?? '',
            $validated['tgl_sk_tunjangan'] ?? '',
            $validated['semester_id'],
            $validated['sumber_dana'] ?? '',
            $validated['dari_tahun'] ?? '',
            $validated['sampai_tahun'] ?? '',
            $validated['nominal'] ?? '',
            $validated['status'],
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'tunjangan.index')
                        ->with('success', 'Data tunjangan berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {

        $tunjangan = Tunjangan::where('ptk_id', $ptk_id)->with('semester')->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('tunjangan.show', compact('tunjangan', 'ptk'));
    }


    public function edit(Tunjangan $tunjangan)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

        $ptks = Ptk::orderBy('nama_lengkap')->get();
        $semesters = Semester::orderBy('nama_semester')->get();

        $ptk = Ptk::find($tunjangan->ptk_id);

        return view('tunjangan.edit', [
            'tunjangan' => $tunjangan,
            'ptks' => $ptks,
            'semesters' => $semesters,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
            'ptk' => $ptk,
        ]);
    }

    public function update(Request $request, Tunjangan $tunjangan)
    {
        $oldNamaTunjangan = $tunjangan->nama_tunjangan;

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenis_tunjangan' => 'required|string|max:100',
            'nama_tunjangan' => 'required|string|max:150',
            'instansi' => 'nullable|string|max:150',
            'sk_tunjangan' => 'nullable|string|max:100',
            'tgl_sk_tunjangan' => 'nullable|date',
            'semester_id' => 'required|exists:semester,id',
            'sumber_dana' => 'nullable|string|max:100',
            'dari_tahun' => 'nullable|integer|min:1900|max:2100',
            'sampai_tahun' => 'nullable|integer|min:1900|max:2100',
            'nominal' => 'nullable|numeric|min:0',
            'status' => 'required|in:Masih Menerima,Tidak Menerima',
        ]);

        $tunjangan->update($validated);
        $ptk = Ptk::find($validated['ptk_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('Tunjangan');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("C{$row}")->getValue() == $oldNamaTunjangan) {
                        $sheet->setCellValue("A{$row}", $ptk->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['jenis_tunjangan']);
                        $sheet->setCellValue("C{$row}", $validated['nama_tunjangan']);
                        $sheet->setCellValue("D{$row}", $validated['instansi'] ?? '');
                        $sheet->setCellValue("E{$row}", $validated['sk_tunjangan'] ?? '');
                        $sheet->setCellValue("F{$row}", $validated['tgl_sk_tunjangan'] ?? '');
                        $sheet->setCellValue("G{$row}", $validated['semester_id']);
                        $sheet->setCellValue("H{$row}", $validated['sumber_dana'] ?? '');
                        $sheet->setCellValue("I{$row}", $validated['dari_tahun'] ?? '');
                        $sheet->setCellValue("J{$row}", $validated['sampai_tahun'] ?? '');
                        $sheet->setCellValue("K{$row}", $validated['nominal'] ?? '');
                        $sheet->setCellValue("L{$row}", $validated['status']);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $prefix = $ptk = Auth::user()->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'tunjangan.index')
            ->with('success', 'Data tunjangan berhasil diperbarui.');
    }

    public function destroy(Tunjangan $tunjangan)
    {
        $oldNamaTunjangan = $tunjangan->nama_tunjangan;
        $tunjangan->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('Tunjangan');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("C{$row}")->getValue() == $oldNamaTunjangan) {
                        $sheet->removeRow($row, 1);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'tunjangan.index')
            ->with('success', 'Data tunjangan berhasil dihapus.');
    }

}
