<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KompetensiKhususPtk;
use App\Models\Ptk;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class KompetensiKhususPtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $kompetensiKhusus = DB::table('kompetensi_khusus')
                ->join('ptk', 'kompetensi_khusus.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'kompetensi_khusus.id as kompetensi_khusus_id',
                    'ptk.id as ptk_id',
                    'kompetensi_khusus.punya_lisensi_kepala_sekolah',
                    'kompetensi_khusus.nomor_unik_kepala_sekolah',
                    'kompetensi_khusus.keahlian_lab_oratorium',
                    'kompetensi_khusus.mampu_menangani',
                    'kompetensi_khusus.keahlian_braile',
                    'kompetensi_khusus.keahlian_bahasa_isyarat'
                )
                ->orderBy('kompetensi_khusus.id', 'asc')
                ->paginate(12);
        } else {
            $kompetensiKhusus = DB::table('ptk')
                ->leftJoin('kompetensi_khusus', 'ptk.id', '=', 'kompetensi_khusus.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(kompetensi_khusus.id) as jumlah_kompetensi_khusus')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(12);
        }

        return view('kompetensi-khusus-ptk.index', compact('kompetensiKhusus', 'isPtk', 'isAdmin', 'prefix'));
    }

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptk = Ptk::findOrFail($ptk_id);
        $kompetensiKhusus = KompetensiKhususPtk::where('ptk_id', $ptk_id)->get();

        return view('kompetensi-khusus-ptk.show', compact('ptk', 'kompetensiKhusus', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $ptkId = $request->query('ptk_id');

        if ($ptkId) {
            $ptk = Ptk::findOrFail($ptkId);
            return view('kompetensi-khusus-ptk.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('kompetensi-khusus-ptk.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'punya_lisensi_kepala_sekolah' => 'nullable|boolean',
            'nomor_unik_kepala_sekolah' => 'nullable|string|max:50',
            'keahlian_lab_oratorium' => 'nullable|string|max:255',
            'mampu_menangani' => 'nullable|in:Tidak,Netra (A),Rungu (B),Grahita Sedang (C1),Grahita Ringan (D),Daksa Sedang (D1),Laras,Daksa Ringan,Wicara,Tuna Ganda,Hiper Aktif (H),Cerdas Istimewa (I),Bakat Istimewa (J),Kesulitan Belajar (K),Narkoba (N),Indigo (O),Down Sindrome (P),Autis (Q)',
            'keahlian_braile' => 'nullable|boolean',
            'keahlian_bahasa_isyarat' => 'nullable|boolean',
        ]);

        $kompetensi = KompetensiKhususPtk::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('KompetensiKhususPTK');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('KompetensiKhususPTK');
            $sheet->fromArray([
                'Nama PTK',
                'Lisensi Kepala Sekolah',
                'Nomor Unik Kepala Sekolah',
                'Keahlian Lab/Oratorium',
                'Mampu Menangani',
                'Keahlian Braile',
                'Keahlian Bahasa Isyarat',
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $ptk->nama_lengkap,
            $validated['punya_lisensi_kepala_sekolah'],
            $validated['nomor_unik_kepala_sekolah'],
            $validated['keahlian_lab_oratorium'],
            $validated['mampu_menangani'],
            $validated['keahlian_braile'],
            $validated['keahlian_bahasa_isyarat'],
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'kompetensi-khusus-ptk.index')
            ->with('success', 'Data kompetensi khusus PTK berhasil ditambahkan.');
    }

    public function edit(KompetensiKhususPtk $kompetensiKhususPtk)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
        $ptk = Ptk::findOrFail($kompetensiKhususPtk->ptk_id);

        return view('kompetensi-khusus-ptk.edit', compact('kompetensiKhususPtk', 'ptk', 'prefix'));
    }

    public function update(Request $request, KompetensiKhususPtk $kompetensiKhususPtk)
    {
        $oldLisensi = $kompetensiKhususPtk->nomor_unik_kepala_sekolah;

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'punya_lisensi_kepala_sekolah' => 'nullable|boolean',
            'nomor_unik_kepala_sekolah' => 'nullable|string|max:50',
            'keahlian_lab_oratorium' => 'nullable|string|max:255',
            'mampu_menangani' => 'nullable|in:Tidak,Netra (A),Rungu (B),Grahita Sedang (C1),Grahita Ringan (D),Daksa Sedang (D1),Laras,Daksa Ringan,Wicara,Tuna Ganda,Hiper Aktif (H),Cerdas Istimewa (I),Bakat Istimewa (J),Kesulitan Belajar (K),Narkoba (N),Indigo (O),Down Sindrome (P),Autis (Q)',
            'keahlian_braile' => 'nullable|boolean',
            'keahlian_bahasa_isyarat' => 'nullable|boolean',
        ]);

        $kompetensiKhususPtk->update($validated);
        $ptk = Ptk::find($validated['ptk_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('KompetensiKhususPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("C{$row}")->getValue() == $oldLisensi) {
                        $sheet->setCellValue("A{$row}", $ptk->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['punya_lisensi_kepala_sekolah']);
                        $sheet->setCellValue("C{$row}", $validated['nomor_unik_kepala_sekolah']);
                        $sheet->setCellValue("D{$row}", $validated['keahlian_lab_oratorium']);
                        $sheet->setCellValue("E{$row}", $validated['mampu_menangani']);
                        $sheet->setCellValue("F{$row}", $validated['keahlian_braile']);
                        $sheet->setCellValue("G{$row}", $validated['keahlian_bahasa_isyarat']);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'kompetensi-khusus-ptk.index')
            ->with('success', 'Data kompetensi khusus PTK berhasil diperbarui.');
    }

    public function destroy(KompetensiKhususPtk $kompetensiKhususPtk)
    {
        $oldLisensi = $kompetensiKhususPtk->nomor_unik_kepala_sekolah;
        $kompetensiKhususPtk->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('KompetensiKhususPTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("C{$row}")->getValue() == $oldLisensi) {
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
            ->route($prefix.'kompetensi-khusus-ptk.index')
            ->with('success', 'Data kompetensi khusus PTK berhasil dihapus.');
    }

}
