<?php

namespace App\Http\Controllers;

use App\Models\RegistrasiSiswa;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class RegistrasiSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        if ($isSiswa) {
            $data = DB::table('registrasi_peserta_didik')
                ->join('peserta_didik', 'registrasi_peserta_didik.peserta_didik_id', '=', 'peserta_didik.id')
                ->join('akun_siswa', 'peserta_didik.id', '=', 'akun_siswa.peserta_didik_id')
                ->where('akun_siswa.email', $user->email)
                ->select(
                    'registrasi_peserta_didik.id as registrasi_id',
                    'peserta_didik.id as siswa_id',
                    'peserta_didik.nama_lengkap',
                    'registrasi_peserta_didik.jenis_pendaftaran',
                    'registrasi_peserta_didik.tanggal_masuk',
                    'registrasi_peserta_didik.sekolah_asal',
                    'registrasi_peserta_didik.no_peserta_un',
                    'registrasi_peserta_didik.no_seri_ijazah',
                    'registrasi_peserta_didik.no_skhun'
                )
                ->orderBy('peserta_didik.nama_lengkap', 'asc')
                ->paginate(12);

            return view('registrasi-siswa.index', compact('data', 'isSiswa', 'prefix'));
        }

        $data = DB::table('peserta_didik')
            ->leftJoin('registrasi_peserta_didik', 'peserta_didik.id', '=', 'registrasi_peserta_didik.peserta_didik_id')
            ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
            ->select(
                'peserta_didik.id as siswa_id',
                'peserta_didik.rombel_id',
                'peserta_didik.nama_lengkap',
                'registrasi_peserta_didik.id as registrasi_id',
                'registrasi_peserta_didik.jenis_pendaftaran',
                'registrasi_peserta_didik.tanggal_masuk',
                'registrasi_peserta_didik.sekolah_asal',
                'rombel.nama_rombel'
            )
            ->orderBy('peserta_didik.nama_lengkap', 'asc')
            ->paginate(12);

        $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

        return view('registrasi-siswa.index', compact('data', 'rombels', 'isSiswa', 'prefix'));
    }

    public function create()
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $siswa = Siswa::all();
        return view('registrasi-siswa.create', compact('siswa', 'prefix'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_pendaftaran' => 'required|in:Siswa Baru,Pindahan,Kembali Bersekolah',
            'tanggal_masuk' => 'required|date',
            'sekolah_asal' => 'nullable|string|max:255',
            'no_peserta_un' => 'nullable|string|max:20',
            'no_seri_ijazah' => 'nullable|string|max:50',
            'no_skhun' => 'nullable|string|max:50',
        ]);

        $registrasi = RegistrasiSiswa::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('RegistrasiSiswa');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('RegistrasiSiswa');
            $sheet->fromArray([
                'Nama Siswa',
                'Jenis Pendaftaran',
                'Tanggal Masuk',
                'Sekolah Asal',
                'No. Peserta UN',
                'No. Seri Ijazah',
                'No. SKHUN'
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $siswa->nama_lengkap,
            $validated['jenis_pendaftaran'],
            $validated['tanggal_masuk'],
            $validated['sekolah_asal'] ?? '',
            $validated['no_peserta_un'] ?? '',
            $validated['no_seri_ijazah'] ?? '',
            $validated['no_skhun'] ?? '',
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'registrasi-siswa.index')
            ->with('success', 'Data registrasi siswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $registrasi = RegistrasiSiswa::find($id);

        if (!$registrasi) {
            $siswa = Siswa::findOrFail($id);
            $existing = RegistrasiSiswa::where('peserta_didik_id', $siswa->id)->first();

            if ($existing) {
                $registrasi = $existing;
            } else {
                $registrasi = new RegistrasiSiswa();
                $registrasi->peserta_didik_id = $siswa->id;
            }
        } else {
            $siswa = Siswa::find($registrasi->peserta_didik_id);
        }

        $semuaSiswa = Siswa::all();

        return view('registrasi-siswa.edit', [
            'data' => $registrasi,
            'siswa' => $semuaSiswa,
            'prefix' => $prefix,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_pendaftaran' => 'required|in:Siswa Baru,Pindahan,Kembali Bersekolah',
            'tanggal_masuk' => 'required|date',
            'sekolah_asal' => 'nullable|string|max:255',
            'no_peserta_un' => 'nullable|string|max:20',
            'no_seri_ijazah' => 'nullable|string|max:50',
            'no_skhun' => 'nullable|string|max:50',
        ]);

        $registrasi = RegistrasiSiswa::findOrFail($id);
        $oldId = $registrasi->peserta_didik_id;
        $registrasi->update($validated);
        $siswa = Siswa::find($validated['peserta_didik_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('RegistrasiSiswa');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("A{$row}")->getValue() == Siswa::find($oldId)->nama_lengkap) {
                        $sheet->setCellValue("A{$row}", $siswa->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['jenis_pendaftaran']);
                        $sheet->setCellValue("C{$row}", $validated['tanggal_masuk']);
                        $sheet->setCellValue("D{$row}", $validated['sekolah_asal'] ?? '');
                        $sheet->setCellValue("E{$row}", $validated['no_peserta_un'] ?? '');
                        $sheet->setCellValue("F{$row}", $validated['no_seri_ijazah'] ?? '');
                        $sheet->setCellValue("G{$row}", $validated['no_skhun'] ?? '');
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'registrasi-siswa.index')
            ->with('success', 'Data registrasi siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $registrasi = RegistrasiSiswa::findOrFail($id);
        $namaSiswa = Siswa::find($registrasi->peserta_didik_id)->nama_lengkap;
        RegistrasiSiswa::where('peserta_didik_id', $registrasi->peserta_didik_id)->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('RegistrasiSiswa');

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

        return redirect()->route($prefix.'registrasi-siswa.index')
            ->with('success', 'Data registrasi siswa berhasil dihapus!');
    }

}
