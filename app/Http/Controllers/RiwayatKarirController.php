<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatKarir;
use App\Models\Ptk;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class RiwayatKarirController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        if ($isPtk) {
            $riwayatKarir = DB::table('riwayat_karir')
                ->join('ptk', 'riwayat_karir.ptk_id', '=', 'ptk.id')
                ->join('akun_ptk', 'ptk.id', '=', 'akun_ptk.ptk_id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'riwayat_karir.id as riwayat_karir_id',
                    'ptk.id as ptk_id',
                    'riwayat_karir.jenjang_pendidikan',
                    'riwayat_karir.jenis_lembaga',
                    'riwayat_karir.status_kepegawaian',
                    'riwayat_karir.jenis_ptk',
                    'riwayat_karir.lembaga_pengangkat',
                    'riwayat_karir.no_sk_kerja',
                    'riwayat_karir.tgl_sk_kerja',
                    'riwayat_karir.tmt_kerja',
                    'riwayat_karir.tst_kerja',
                    'riwayat_karir.tempat_kerja',
                    'riwayat_karir.ttd_sk_kerja'
                )
                ->orderBy('riwayat_karir.tgl_sk_kerja', 'desc')
                ->paginate(12);

        } else {
            $riwayatKarir = DB::table('ptk')
                ->leftJoin('riwayat_karir', 'ptk.id', '=', 'riwayat_karir.ptk_id')
                ->select(
                    'ptk.id as ptk_id',
                    'ptk.nama_lengkap',
                    DB::raw('COUNT(riwayat_karir.id) as jumlah_riwayat_karir')
                )
                ->groupBy('ptk.id', 'ptk.nama_lengkap')
                ->orderBy('ptk.nama_lengkap')
                ->paginate(50);
        }

        return view('riwayat-karir.index', compact('riwayatKarir', 'isPtk', 'isAdmin', 'prefix'));
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
            return view('riwayat-karir.create', compact('ptkId', 'ptk', 'prefix'));
        } else {
            $ptks = Ptk::orderBy('nama_lengkap')->get();
            return view('riwayat-karir.create', compact('ptks', 'prefix'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenjang_pendidikan' => 'required|string|max:100',
            'jenis_lembaga' => 'required|string|max:100',
            'status_kepegawaian' => 'required|string|max:50',
            'jenis_ptk' => 'required|string|max:100',
            'lembaga_pengangkat' => 'required|string|max:150',
            'no_sk_kerja' => 'required|string|max:50',
            'tgl_sk_kerja' => 'required|date',
            'tmt_kerja' => 'required|date',
            'tst_kerja' => 'nullable|date',
            'tempat_kerja' => 'required|string|max:100',
            'ttd_sk_kerja' => 'required|string|max:100',
        ]);

        $riwayatKarir = RiwayatKarir::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('RiwayatKarir');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('RiwayatKarir');
            $sheet->fromArray([
                'Nama PTK', 'Jenjang Pendidikan', 'Jenis Lembaga', 'Status Kepegawaian', 'Jenis PTK',
                'Lembaga Pengangkat', 'Nomor SK', 'Tanggal SK', 'TMT Kerja', 'TST Kerja', 'Tempat Kerja', 'TTD SK Kerja'
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $ptk->nama_lengkap,
            $validated['jenjang_pendidikan'],
            $validated['jenis_lembaga'],
            $validated['status_kepegawaian'],
            $validated['jenis_ptk'],
            $validated['lembaga_pengangkat'],
            $validated['no_sk_kerja'],
            $validated['tgl_sk_kerja'],
            $validated['tmt_kerja'],
            $validated['tst_kerja'] ?? '',
            $validated['tempat_kerja'],
            $validated['ttd_sk_kerja'],
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'riwayat-karir.index')
            ->with('success', 'Data riwayat karir berhasil ditambahkan.');
    }

    public function show($ptk_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        $riwayatKarir = RiwayatKarir::where('ptk_id', $ptk_id)->get();
        $ptk = Ptk::findOrFail($ptk_id);

        return view('riwayat-karir.show', compact('riwayatKarir', 'ptk', 'prefix'));
    }

    public function edit(RiwayatKarir $riwayatKarir)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';
        $prefix = $isAdmin ? 'admin.' : 'ptk.';

        $ptks = Ptk::orderBy('nama_lengkap')->get();

        return view('riwayat-karir.edit', compact(
            'riwayatKarir',
            'ptks',
            'isAdmin',
            'isPtk',
            'prefix'
        ));
    }

    public function update(Request $request, RiwayatKarir $riwayatKarir)
    {
        $oldNomor = $riwayatKarir->no_sk_kerja;

        $validated = $request->validate([
            'ptk_id' => 'required|exists:ptk,id',
            'jenjang_pendidikan' => 'required|string|max:100',
            'jenis_lembaga' => 'required|string|max:100',
            'status_kepegawaian' => 'required|string|max:50',
            'jenis_ptk' => 'required|string|max:100',
            'lembaga_pengangkat' => 'required|string|max:150',
            'no_sk_kerja' => 'required|string|max:50',
            'tgl_sk_kerja' => 'required|date',
            'tmt_kerja' => 'required|date',
            'tst_kerja' => 'nullable|date',
            'tempat_kerja' => 'required|string|max:100',
            'ttd_sk_kerja' => 'required|string|max:100',
        ]);

        $riwayatKarir->update($validated);
        $ptk = Ptk::find($validated['ptk_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('RiwayatKarir');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("G{$row}")->getValue() == $oldNomor) {
                        $sheet->setCellValue("A{$row}", $ptk->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['jenjang_pendidikan']);
                        $sheet->setCellValue("C{$row}", $validated['jenis_lembaga']);
                        $sheet->setCellValue("D{$row}", $validated['status_kepegawaian']);
                        $sheet->setCellValue("E{$row}", $validated['jenis_ptk']);
                        $sheet->setCellValue("F{$row}", $validated['lembaga_pengangkat']);
                        $sheet->setCellValue("G{$row}", $validated['no_sk_kerja']);
                        $sheet->setCellValue("H{$row}", $validated['tgl_sk_kerja']);
                        $sheet->setCellValue("I{$row}", $validated['tmt_kerja']);
                        $sheet->setCellValue("J{$row}", $validated['tst_kerja'] ?? '');
                        $sheet->setCellValue("K{$row}", $validated['tempat_kerja']);
                        $sheet->setCellValue("L{$row}", $validated['ttd_sk_kerja']);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()
            ->route($prefix.'riwayat-karir.index')
            ->with('success', 'Data riwayat karir berhasil diperbarui.');
    }

    public function destroy(RiwayatKarir $riwayatKarir)
    {
        $oldNomor = $riwayatKarir->no_sk_kerja;
        $riwayatKarir->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('RiwayatKarir');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("G{$row}")->getValue() == $oldNomor) {
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
            ->route($prefix.'riwayat-karir.index')
            ->with('success', 'Data riwayat karir berhasil dihapus.');
    }

}
