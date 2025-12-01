<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PenugasanPtk;
use App\Models\Ptk;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class PenugasanPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $data = collect();

        if ($isPtk) {
            $data = DB::table('akun_ptk')
                ->join('ptk', 'akun_ptk.ptk_id', '=', 'ptk.id')
                ->leftJoin('penugasan', 'ptk.id', '=', 'penugasan.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    'penugasan.id as penugasan_id',
                    'penugasan.nomor_surat_tugas',
                    'penugasan.tanggal_surat_tugas',
                    'penugasan.tmt_tugas',
                    DB::raw("CASE
                                WHEN penugasan.status_sekolah_induk IS NULL THEN '-'
                                WHEN penugasan.status_sekolah_induk = 'Ya' THEN 'Ya'
                                WHEN penugasan.status_sekolah_induk = 'Tidak' THEN 'Tidak'
                                ELSE '-'
                            END as status_sekolah_induk")
                )
                ->paginate(1);

            return view('penugasan-ptk.index', compact('data', 'isPtk'));
        } else {
            $data = DB::table('ptk')
                ->leftJoin('penugasan', 'penugasan.ptk_id', '=', 'ptk.id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    'penugasan.id as penugasan_id',
                    'penugasan.nomor_surat_tugas',
                    'penugasan.tanggal_surat_tugas',
                    'penugasan.tmt_tugas',
                    DB::raw("CASE
                                WHEN penugasan.status_sekolah_induk IS NULL THEN '-'
                                WHEN penugasan.status_sekolah_induk = 'Ya' THEN 'Ya'
                                WHEN penugasan.status_sekolah_induk = 'Tidak' THEN 'Tidak'
                                ELSE '-'
                            END as status_sekolah_induk")
                )
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(50);

            return view('penugasan-ptk.index', compact('data', 'isPtk'));
        }
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
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptks = Ptk::all();
        $data = new PenugasanPtk();

        return view('penugasan-ptk.edit', compact('data', 'ptks', 'prefix'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'nomor_surat_tugas' => 'required|string|max:100',
            'tanggal_surat_tugas' => 'required|date',
            'tmt_tugas' => 'required|date',
            'status_sekolah_induk' => 'required|boolean',
        ]);

        $penugasan = PenugasanPtk::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('PenugasanPTK');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('PenugasanPTK');
            $sheet->fromArray(['Nama PTK', 'Nomor Surat Tugas', 'Tanggal Surat Tugas', 'TMT Tugas', 'Status Sekolah Induk'], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $ptk->nama_lengkap,
            $validated['nomor_surat_tugas'],
            $validated['tanggal_surat_tugas'],
            $validated['tmt_tugas'],
            $validated['status_sekolah_induk'] ? 'Ya' : 'Tidak',
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'penugasan-ptk.index')
            ->with('success', 'Penugasan PTK berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

        $penugasan = PenugasanPtk::find($id);

        if (!$penugasan) {
            $ptk = Ptk::findOrFail($id);
            $existing = PenugasanPtk::where('ptk_id', $ptk->id)->first();

            if ($existing) {
                $penugasan = $existing;
            } else {
                $penugasan = new PenugasanPtk();
                $penugasan->ptk_id = $ptk->id;
            }
        } else {
            $ptk = Ptk::find($penugasan->ptk_id);
        }

        $ptks = Ptk::all();

        return view('penugasan-ptk.edit', [
            'data' => $penugasan,
            'ptks' => $ptks,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
            'ptk' => $ptk,
        ]);
    }

    public function update(Request $request, PenugasanPtk $penugasan_ptk)
    {
        $oldNomor = $penugasan_ptk->nomor_surat_tugas;

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'nomor_surat_tugas' => 'required|string|max:100',
            'tanggal_surat_tugas' => 'required|date',
            'tmt_tugas' => 'required|date',
            'status_sekolah_induk' => 'required|boolean',
        ]);

        $penugasan_ptk->update($validated);
        $ptk = Ptk::find($validated['ptk_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('PenugasanPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("B{$row}")->getValue() == $oldNomor) {
                        $sheet->setCellValue("A{$row}", $ptk->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['nomor_surat_tugas']);
                        $sheet->setCellValue("C{$row}", $validated['tanggal_surat_tugas']);
                        $sheet->setCellValue("D{$row}", $validated['tmt_tugas']);
                        $sheet->setCellValue("E{$row}", $validated['status_sekolah_induk'] ? 'Ya' : 'Tidak');
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'penugasan-ptk.index')
            ->with('success', 'Penugasan PTK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penugasan = PenugasanPtk::findOrFail($id);
        $oldNomor = $penugasan->nomor_surat_tugas;

        PenugasanPtk::where('ptk_id', $penugasan->ptk_id)->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('PenugasanPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("B{$row}")->getValue() == $oldNomor) {
                        $sheet->removeRow($row, 1);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'penugasan-ptk.index')
            ->with('success', 'Penugasan PTK berhasil dihapus.');
    }

}
