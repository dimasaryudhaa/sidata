<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KontakSiswa;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class KontakSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $rombels = collect();

        if ($isSiswa) {
            $data = DB::table('kontak_peserta_didik')
                ->join('peserta_didik', 'kontak_peserta_didik.peserta_didik_id', '=', 'peserta_didik.id')
                ->join('akun_siswa', 'peserta_didik.id', '=', 'akun_siswa.peserta_didik_id')
                ->where('akun_siswa.email', $user->email)
                ->select('kontak_peserta_didik.*', 'peserta_didik.nama_lengkap')
                ->orderBy('peserta_didik.nama_lengkap')
                ->paginate(12);

            return view('kontak-siswa.index', compact('data', 'isSiswa', 'prefix'));
        } else {
            $data = DB::table('peserta_didik')
                ->leftJoin('kontak_peserta_didik', 'peserta_didik.id', '=', 'kontak_peserta_didik.peserta_didik_id')
                ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
                ->select(
                    'peserta_didik.id as siswa_id',
                    'peserta_didik.nama_lengkap',
                    'peserta_didik.rombel_id',
                    'rombel.nama_rombel',
                    'kontak_peserta_didik.*'
                )
                ->orderBy('peserta_didik.nama_lengkap')
                ->paginate(12);

            $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

            return view('kontak-siswa.index', compact('data', 'rombels', 'isSiswa', 'prefix'));
        }
    }

    public function create()
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $siswa = Siswa::all();
        return view('kontak-siswa.create', compact('siswa', 'prefix'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:100',
            'alamat_jalan' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'tempat_tinggal' => 'nullable|in:Bersama Orang Tua,Kos,Asrama,Lainnya',
            'moda_transportasi' => 'nullable|string|max:255',
            'anak_ke' => 'nullable|integer',
        ]);

        $kontak = KontakSiswa::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('KontakSiswa');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('KontakSiswa');
            $sheet->fromArray([
                'Nama Siswa',
                'No. HP',
                'Email',
                'Alamat Jalan',
                'RT',
                'RW',
                'Kelurahan',
                'Kecamatan',
                'Kode Pos',
                'Tempat Tinggal',
                'Moda Transportasi',
                'Anak Ke'
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $siswa->nama_lengkap,
            $validated['no_hp'] ?? '',
            $validated['email'] ?? '',
            $validated['alamat_jalan'] ?? '',
            $validated['rt'] ?? '',
            $validated['rw'] ?? '',
            $validated['kelurahan'] ?? '',
            $validated['kecamatan'] ?? '',
            $validated['kode_pos'] ?? '',
            $validated['tempat_tinggal'] ?? '',
            $validated['moda_transportasi'] ?? '',
            $validated['anak_ke'] ?? '',
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'kontak-siswa.index')
            ->with('success', 'Data kontak siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $kontak = KontakSiswa::find($id);

        if (!$kontak) {
            $siswa = Siswa::findOrFail($id);
            $existing = KontakSiswa::where('peserta_didik_id', $siswa->id)->first();

            $kontak = $existing ?? new KontakSiswa(['peserta_didik_id' => $siswa->id]);
        }

        $semuaSiswa = Siswa::all();

        return view('kontak-siswa.edit', [
            'data' => $kontak,
            'siswa' => $semuaSiswa,
            'prefix' => $prefix
        ]);
    }

    public function update(Request $request, $id)
    {
        $kontak = KontakSiswa::findOrFail($id);

        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:100',
            'alamat_jalan' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'tempat_tinggal' => 'nullable|in:Bersama Orang Tua,Kos,Asrama,Lainnya',
            'moda_transportasi' => 'nullable|string|max:255',
            'anak_ke' => 'nullable|integer',
        ]);

        $oldId = $kontak->peserta_didik_id;
        $kontak->update($validated);
        $siswa = Siswa::find($validated['peserta_didik_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('KontakSiswa');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("A{$row}")->getValue() == Siswa::find($oldId)->nama_lengkap) {
                        $sheet->setCellValue("A{$row}", $siswa->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['no_hp'] ?? '');
                        $sheet->setCellValue("C{$row}", $validated['email'] ?? '');
                        $sheet->setCellValue("D{$row}", $validated['alamat_jalan'] ?? '');
                        $sheet->setCellValue("E{$row}", $validated['rt'] ?? '');
                        $sheet->setCellValue("F{$row}", $validated['rw'] ?? '');
                        $sheet->setCellValue("G{$row}", $validated['kelurahan'] ?? '');
                        $sheet->setCellValue("H{$row}", $validated['kecamatan'] ?? '');
                        $sheet->setCellValue("I{$row}", $validated['kode_pos'] ?? '');
                        $sheet->setCellValue("J{$row}", $validated['tempat_tinggal'] ?? '');
                        $sheet->setCellValue("K{$row}", $validated['moda_transportasi'] ?? '');
                        $sheet->setCellValue("L{$row}", $validated['anak_ke'] ?? '');
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'kontak-siswa.index')
            ->with('success', 'Data kontak siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kontak = KontakSiswa::findOrFail($id);
        $namaSiswa = Siswa::find($kontak->peserta_didik_id)->nama_lengkap;
        KontakSiswa::where('peserta_didik_id', $kontak->peserta_didik_id)->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('KontakSiswa');

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

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'kontak-siswa.index')
            ->with('success', 'Data kontak siswa berhasil dihapus.');
    }

}
