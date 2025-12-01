<?php

namespace App\Http\Controllers;

use App\Models\AnakPtk;
use App\Models\Ptk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class AnakPtkController extends Controller
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
            $anakPtk = DB::table('anak')
                ->join('ptk', 'anak.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'anak.id as anak_id',
                    'anak.ptk_id',
                    'anak.nama_anak',
                    'anak.status_anak',
                    'anak.jenjang',
                    'anak.nisn',
                    'anak.jenis_kelamin',
                    'anak.tempat_lahir',
                    'anak.tanggal_lahir',
                    'anak.tahun_masuk'
                )
                ->orderBy('anak.nama_anak', 'asc')
                ->paginate(12);
        } else {
            $anakPtk = DB::table('ptk')
                ->leftJoin('anak', 'ptk.id', '=', 'anak.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(anak.id) as jumlah_anak')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);
        }

        return view('anak-ptk.index', compact('anakPtk', 'isPtk', 'isAdmin', 'ptkId'));
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
            return view('anak-ptk.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::all();
            return view('anak-ptk.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'nama_anak' => 'required|string|max:255',
            'status_anak' => 'required|string|max:100',
            'jenjang' => 'required|string|max:100',
            'nisn' => 'nullable|digits:10',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tahun_masuk' => 'required|digits:4',
        ]);

        $anak = AnakPtk::create($validated);

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

        $sheet = $spreadsheet->getSheetByName('AnakPTK');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('AnakPTK');
            $sheet->fromArray(['Nama PTK', 'Nama Anak', 'Status Anak', 'Jenjang', 'NISN', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Tahun Masuk'], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $ptk->nama_lengkap,
            $validated['nama_anak'],
            $validated['status_anak'],
            $validated['jenjang'],
            $validated['nisn'] ?? '',
            $validated['jenis_kelamin'],
            $validated['tempat_lahir'],
            $validated['tanggal_lahir'],
            $validated['tahun_masuk'],
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'anak-ptk.index')
                        ->with('success', 'Data anak PTK berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $anak = AnakPtk::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('anak-ptk.show', compact('anak', 'ptk'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

        $anak = AnakPtk::find($id);

        if (!$anak) {
            $ptk = Ptk::findOrFail($id);
            $existing = AnakPtk::where('ptk_id', $ptk->id)->first();

            if ($existing) {
                $anak = $existing;
            } else {
                $anak = new AnakPtk();
                $anak->ptk_id = $ptk->id;
            }
        } else {
            $ptk = Ptk::find($anak->ptk_id);
        }

        $ptks = Ptk::all();

        return view('anak-ptk.edit', [
            'anak' => $anak,
            'ptks' => $ptks,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
            'ptk' => $ptk,
        ]);
    }

    public function update(Request $request, $id)
    {
        $anak = AnakPtk::findOrFail($id);
        $oldNisn = $anak->nisn;

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'nama_anak' => 'required|string|max:255',
            'status_anak' => 'required|string|max:100',
            'jenjang' => 'required|string|max:100',
            'nisn' => 'nullable|digits:10',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tahun_masuk' => 'required|digits:4',
        ]);

        $anak->update($validated);
        $ptk = Ptk::find($validated['ptk_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('AnakPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("E{$row}")->getValue() == $oldNisn) {
                        $sheet->setCellValue("A{$row}", $ptk->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['nama_anak']);
                        $sheet->setCellValue("C{$row}", $validated['status_anak']);
                        $sheet->setCellValue("D{$row}", $validated['jenjang']);
                        $sheet->setCellValue("E{$row}", $validated['nisn'] ?? '');
                        $sheet->setCellValue("F{$row}", $validated['jenis_kelamin']);
                        $sheet->setCellValue("G{$row}", $validated['tempat_lahir']);
                        $sheet->setCellValue("H{$row}", $validated['tanggal_lahir']);
                        $sheet->setCellValue("I{$row}", $validated['tahun_masuk']);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'anak-ptk.index')
            ->with('success', 'Data Anak PTK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $anak = AnakPtk::findOrFail($id);
        $oldNisn = $anak->nisn;
        $anak->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('AnakPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("E{$row}")->getValue() == $oldNisn) {
                        $sheet->removeRow($row, 1);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'anak-ptk.index')
            ->with('success', 'Data Anak PTK berhasil dihapus.');
    }

}
