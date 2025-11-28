<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\Rayon;
use App\Models\Rombel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';

        if ($isSiswa) {

            $siswa = DB::table('akun_siswa')
                ->join('peserta_didik', 'akun_siswa.peserta_didik_id', '=', 'peserta_didik.id')
                ->leftJoin('rayon', 'peserta_didik.rayon_id', '=', 'rayon.id')
                ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
                ->where('akun_siswa.email', $user->email)
                ->select(
                    'peserta_didik.id',
                    'peserta_didik.nama_lengkap',
                    'peserta_didik.jenis_kelamin',
                    'peserta_didik.nis',
                    'peserta_didik.nisn',
                    'peserta_didik.nik',
                    'peserta_didik.no_kk',
                    'peserta_didik.tempat_lahir',
                    'peserta_didik.tanggal_lahir',
                    'peserta_didik.agama',
                    'rayon.nama_rayon',
                    'rombel.nama_rombel',
                    'peserta_didik.kewarganegaraan',
                    'peserta_didik.negara_asal',
                    'peserta_didik.berkebutuhan_khusus'
                )
                ->paginate(1);

            return view('siswa.index', compact('siswa', 'isSiswa'));

        } else {

            $siswa = DB::table('peserta_didik')
                ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
                ->leftJoin('rayon', 'peserta_didik.rayon_id', '=', 'rayon.id')
                ->select(
                    'peserta_didik.id',
                    'peserta_didik.nama_lengkap',
                    'peserta_didik.jenis_kelamin',
                    'peserta_didik.nis',
                    'peserta_didik.nisn',
                    'peserta_didik.rombel_id',
                    'rayon.nama_rayon',
                    'rombel.nama_rombel'
                )
                ->orderBy('peserta_didik.nama_lengkap', 'asc')
                ->paginate(12);

            $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

            return view('siswa.index', compact('siswa', 'rombels', 'isSiswa'));
        }
    }


    public function create()
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $rayons = Rayon::all();
        $rombels = Rombel::all();

        return view('siswa.create', compact('rayons', 'rombels', 'prefix'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required',
            'nis' => 'nullable',
            'nisn' => 'nullable',
            'nik' => 'nullable',
            'no_kk' => 'nullable',
            'tempat_lahir' => 'nullable',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable',
            'rayon_id' => 'required|exists:rayon,id',
            'rombel_id' => 'required|exists:rombel,id',
            'kewarganegaraan' => 'nullable',
            'negara_asal' => 'nullable',
            'berkebutuhan_khusus' => 'nullable',
        ]);

        $siswa = Siswa::create($validated);

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

        $sheet = $spreadsheet->getSheetByName('DataSiswa');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('DataSiswa');
            $sheet->fromArray([
                'Nama Lengkap',
                'Jenis Kelamin',
                'NIS',
                'NISN',
                'NIK',
                'No KK',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Agama',
                'Rayon',
                'Rombel',
                'Kewarganegaraan',
                'Negara Asal',
                'Berkebutuhan Khusus'
            ], null, 'A1');
        }

        $rayon = Rayon::find($validated['rayon_id']);
        $rombel = Rombel::find($validated['rombel_id']);

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $validated['nama_lengkap'],
            $validated['jenis_kelamin'],
            $validated['nis'] ?? '',
            $validated['nisn'] ?? '',
            $validated['nik'] ?? '',
            $validated['no_kk'] ?? '',
            $validated['tempat_lahir'] ?? '',
            $validated['tanggal_lahir'] ?? '',
            $validated['agama'] ?? '',
            $rayon->nama_rayon,
            $rombel->nama_rombel,
            $validated['kewarganegaraan'] ?? '',
            $validated['negara_asal'] ?? '',
            $validated['berkebutuhan_khusus'] ?? '',
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isSiswa = $user->role === 'siswa';

        $siswa = Siswa::findOrFail($id);
        $rombels = Rombel::all();
        $rayons = Rayon::all();

        return view('siswa.edit', compact('siswa', 'rombels', 'rayons', 'isAdmin', 'isSiswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $oldNama = $siswa->nama_lengkap;

        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required',
            'nis' => 'nullable',
            'nisn' => 'nullable',
            'nik' => 'nullable',
            'no_kk' => 'nullable',
            'tempat_lahir' => 'nullable',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable',
            'rayon_id' => 'required|exists:rayon,id',
            'rombel_id' => 'required|exists:rombel,id',
            'kewarganegaraan' => 'nullable',
            'negara_asal' => 'nullable',
            'berkebutuhan_khusus' => 'nullable',
        ]);

        $siswa->update($validated);

        $rayon = Rayon::find($validated['rayon_id']);
        $rombel = Rombel::find($validated['rombel_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('DataSiswa');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("A{$row}")->getValue() == $oldNama) {
                        $sheet->setCellValue("A{$row}", $validated['nama_lengkap']);
                        $sheet->setCellValue("B{$row}", $validated['jenis_kelamin']);
                        $sheet->setCellValue("C{$row}", $validated['nis'] ?? '');
                        $sheet->setCellValue("D{$row}", $validated['nisn'] ?? '');
                        $sheet->setCellValue("E{$row}", $validated['nik'] ?? '');
                        $sheet->setCellValue("F{$row}", $validated['no_kk'] ?? '');
                        $sheet->setCellValue("G{$row}", $validated['tempat_lahir'] ?? '');
                        $sheet->setCellValue("H{$row}", $validated['tanggal_lahir'] ?? '');
                        $sheet->setCellValue("I{$row}", $validated['agama'] ?? '');
                        $sheet->setCellValue("J{$row}", $rayon->nama_rayon);
                        $sheet->setCellValue("K{$row}", $rombel->nama_rombel);
                        $sheet->setCellValue("L{$row}", $validated['kewarganegaraan'] ?? '');
                        $sheet->setCellValue("M{$row}", $validated['negara_asal'] ?? '');
                        $sheet->setCellValue("N{$row}", $validated['berkebutuhan_khusus'] ?? '');
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Siswa $siswa)
    {
        $nama = $siswa->nama_lengkap;

        $siswa->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('DataSiswa');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("A{$row}")->getValue() == $nama) {
                        $sheet->removeRow($row, 1);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'siswa.index')
            ->with('success', 'Data siswa berhasil dihapus!');
    }

}
