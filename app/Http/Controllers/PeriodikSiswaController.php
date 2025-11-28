<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodikSiswa;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class PeriodikSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'siswa.';

        $rombels = DB::table('rombel')->select('id', 'nama_rombel')->get();

        if ($isSiswa) {
            $periodik = DB::table('data_periodik')
                ->join('akun_siswa', 'data_periodik.peserta_didik_id', '=', 'akun_siswa.peserta_didik_id')
                ->join('peserta_didik', 'data_periodik.peserta_didik_id', '=', 'peserta_didik.id')
                ->where('akun_siswa.email', $user->email)
                ->select(
                    'data_periodik.id',
                    'peserta_didik.nama_lengkap',
                    'data_periodik.tinggi_badan_cm',
                    'data_periodik.berat_badan_kg',
                    'data_periodik.lingkar_kepala_cm',
                    'data_periodik.jarak_ke_sekolah',
                    'data_periodik.jarak_sebenarnya_km',
                    'data_periodik.waktu_tempuh_jam',
                    'data_periodik.waktu_tempuh_menit',
                    'data_periodik.jumlah_saudara'
                )
                ->paginate(12);

            return view('periodik.index', compact('periodik', 'isSiswa', 'isAdmin'));
        }

        $periodik = DB::table('peserta_didik')
            ->leftJoin('data_periodik', 'peserta_didik.id', '=', 'data_periodik.peserta_didik_id')
            ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
            ->select(
                'peserta_didik.id as siswa_id',
                'peserta_didik.rombel_id',
                'peserta_didik.nama_lengkap',
                'data_periodik.id as periodik_id',
                'data_periodik.tinggi_badan_cm',
                'data_periodik.berat_badan_kg',
                'data_periodik.lingkar_kepala_cm',
                'data_periodik.jarak_sebenarnya_km'
            )
            ->orderBy('peserta_didik.nama_lengkap')
            ->paginate(12);

        return view('periodik.index', compact('periodik', 'isSiswa', 'isAdmin', 'rombels'));
    }

    public function create()
    {
        $siswas = Siswa::all();

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return view('periodik.create', compact('siswas', 'prefix'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'tinggi_badan_cm' => 'nullable|numeric',
            'berat_badan_kg' => 'nullable|numeric',
            'lingkar_kepala_cm' => 'nullable|numeric',
            'jarak_ke_sekolah' => 'nullable|in:Kurang dari 1 km,Lebih dari 1 km',
            'jarak_sebenarnya_km' => 'nullable|numeric',
            'waktu_tempuh_jam' => 'nullable|integer',
            'waktu_tempuh_menit' => 'nullable|integer',
            'jumlah_saudara' => 'nullable|integer',
        ]);

        $periodik = PeriodikSiswa::create($validated);
        $siswa = Siswa::find($validated['peserta_didik_id']);

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

        $sheet = $spreadsheet->getSheetByName('PeriodikSiswa');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('PeriodikSiswa');
            $sheet->fromArray([
                'Nama Siswa',
                'Tinggi Badan (cm)',
                'Berat Badan (kg)',
                'Lingkar Kepala (cm)',
                'Jarak ke Sekolah',
                'Jarak Sebenarnya (km)',
                'Waktu Tempuh (jam)',
                'Waktu Tempuh (menit)',
                'Jumlah Saudara'
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $siswa->nama_lengkap,
            $validated['tinggi_badan_cm'] ?? '',
            $validated['berat_badan_kg'] ?? '',
            $validated['lingkar_kepala_cm'] ?? '',
            $validated['jarak_ke_sekolah'] ?? '',
            $validated['jarak_sebenarnya_km'] ?? '',
            $validated['waktu_tempuh_jam'] ?? '',
            $validated['waktu_tempuh_menit'] ?? '',
            $validated['jumlah_saudara'] ?? '',
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'periodik.index')
            ->with('success', 'Data periodik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $periodik = PeriodikSiswa::find($id);

        if (!$periodik) {
            $siswaModel = Siswa::findOrFail($id);
            $existing = PeriodikSiswa::where('peserta_didik_id', $siswaModel->id)->first();

            if ($existing) {
                $periodik = $existing;
            } else {
                $periodik = new PeriodikSiswa();
                $periodik->peserta_didik_id = $siswaModel->id;
            }
        } else {
            $siswaModel = Siswa::find($periodik->peserta_didik_id);
        }

        $siswas = Siswa::all();

        return view('periodik.edit', [
            'periodik' => $periodik,
            'siswa' => $siswas,
            'prefix' => $prefix
        ]);
    }

    public function update(Request $request, PeriodikSiswa $periodik)
    {
        $oldId = $periodik->peserta_didik_id;

        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'tinggi_badan_cm' => 'nullable|numeric',
            'berat_badan_kg' => 'nullable|numeric',
            'lingkar_kepala_cm' => 'nullable|numeric',
            'jarak_ke_sekolah' => 'nullable|in:Kurang dari 1 km,Lebih dari 1 km',
            'jarak_sebenarnya_km' => 'nullable|numeric',
            'waktu_tempuh_jam' => 'nullable|integer',
            'waktu_tempuh_menit' => 'nullable|integer',
            'jumlah_saudara' => 'nullable|integer',
        ]);

        $periodik->update($validated);
        $siswa = Siswa::find($validated['peserta_didik_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('PeriodikSiswa');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("A{$row}")->getValue() == Siswa::find($oldId)->nama_lengkap) {
                        $sheet->setCellValue("A{$row}", $siswa->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['tinggi_badan_cm'] ?? '');
                        $sheet->setCellValue("C{$row}", $validated['berat_badan_kg'] ?? '');
                        $sheet->setCellValue("D{$row}", $validated['lingkar_kepala_cm'] ?? '');
                        $sheet->setCellValue("E{$row}", $validated['jarak_ke_sekolah'] ?? '');
                        $sheet->setCellValue("F{$row}", $validated['jarak_sebenarnya_km'] ?? '');
                        $sheet->setCellValue("G{$row}", $validated['waktu_tempuh_jam'] ?? '');
                        $sheet->setCellValue("H{$row}", $validated['waktu_tempuh_menit'] ?? '');
                        $sheet->setCellValue("I{$row}", $validated['jumlah_saudara'] ?? '');
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'periodik.index')
            ->with('success', 'Data periodik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $periodik = PeriodikSiswa::findOrFail($id);
        $namaSiswa = Siswa::find($periodik->peserta_didik_id)->nama_lengkap;

        PeriodikSiswa::where('peserta_didik_id', $periodik->peserta_didik_id)->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('PeriodikSiswa');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("A{$row}")->getValue() == $namaSiswa) {
                        $sheet->removeRow($row, 1);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'periodik.index')
            ->with('success', 'Semua data periodik siswa berhasil dihapus.');
    }

}
