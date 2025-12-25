<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrestasiSiswa;
use App\Models\Siswa;
use App\Models\AkunSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;


class PrestasiSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'siswa.';

        if ($isSiswa) {
            $prestasi = DB::table('prestasi')
                ->join('akun_siswa', 'prestasi.peserta_didik_id', '=', 'akun_siswa.peserta_didik_id')
                ->where('akun_siswa.email', $user->email)
                ->select('prestasi.*')
                ->orderBy('tahun_prestasi', 'desc')
                ->paginate(12);

            return view('prestasi.index', compact(
                'prestasi',
                'isSiswa',
                'isAdmin',
                'prefix'
            ));
        }

        $prestasi = DB::table('peserta_didik')
            ->leftJoin('prestasi', 'peserta_didik.id', '=', 'prestasi.peserta_didik_id')
            ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
            ->select(
                'peserta_didik.id as siswa_id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.rombel_id',
                'rombel.nama_rombel',
                DB::raw('COUNT(prestasi.id) as jumlah_prestasi')
            )
            ->groupBy(
                'peserta_didik.id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.rombel_id',
                'rombel.nama_rombel'
            )
            ->orderBy('peserta_didik.nama_lengkap', 'asc')
            ->paginate(12);

        $rombels = DB::table('rombel')->orderBy('nama_rombel')->get();

        return view('prestasi.index', compact(
            'prestasi',
            'rombels',
            'isSiswa',
            'isAdmin',
            'prefix'
        ));
    }


    public function show($siswa_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $siswa = Siswa::findOrFail($siswa_id);
        $prestasi = PrestasiSiswa::where('peserta_didik_id', $siswa_id)->get();

        return view('prestasi.show', compact('prestasi', 'siswa', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $siswaId = $request->query('siswa_id');

        if ($user->role === 'siswa') {

            $akunSiswa = AkunSiswa::where('email', $user->email)->firstOrFail();
            $siswa = $akunSiswa->siswa;

            return view('prestasi.create', compact('siswa', 'prefix'));
        }

        if ($siswaId) {
            $siswa = Siswa::findOrFail($siswaId);
            return view('prestasi.create', compact('siswa', 'siswaId', 'prefix'));
        }

        $siswas = Siswa::orderBy('nama_lengkap')->get();
        return view('prestasi.create', compact('siswas', 'prefix'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_prestasi' => 'required|in:Sains,Seni,Olahraga,Lain-lain',
            'tingkat_prestasi' => 'required|in:Sekolah,Kecamatan,Kabupaten,Provinsi,Nasional,Internasional',
            'nama_prestasi' => 'required|string|max:255',
            'tahun_prestasi' => 'required|digits:4|integer',
            'penyelenggara' => 'required|string|max:255',
            'peringkat' => 'nullable|integer'
        ]);

        $prestasi = PrestasiSiswa::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('PrestasiSiswa');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('PrestasiSiswa');
            $sheet->fromArray([
                'Nama Siswa',
                'Jenis Prestasi',
                'Tingkat Prestasi',
                'Nama Prestasi',
                'Tahun Prestasi',
                'Penyelenggara',
                'Peringkat'
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $siswa->nama_lengkap,
            $validated['jenis_prestasi'],
            $validated['tingkat_prestasi'],
            $validated['nama_prestasi'],
            $validated['tahun_prestasi'],
            $validated['penyelenggara'],
            $validated['peringkat'] ?? ''
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'prestasi.index')
            ->with('success', 'Data prestasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isSiswa = $user->role === 'siswa';
        $prefix = $isAdmin ? 'admin.' : 'siswa.';

        $prestasi = PrestasiSiswa::findOrFail($id);
        $siswa = Siswa::findOrFail($prestasi->peserta_didik_id);

        return view('prestasi.edit', compact(
            'prestasi',
            'siswa',
            'isAdmin',
            'isSiswa',
            'prefix'
        ));
    }

    public function update(Request $request, $id)
    {
        $prestasi = PrestasiSiswa::findOrFail($id);
        $oldId = $prestasi->peserta_didik_id;

        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_prestasi' => 'required|in:Sains,Seni,Olahraga,Lain-lain',
            'tingkat_prestasi' => 'required|in:Sekolah,Kecamatan,Kabupaten,Provinsi,Nasional,Internasional',
            'nama_prestasi' => 'required|string|max:255',
            'tahun_prestasi' => 'required|digits:4|integer',
            'penyelenggara' => 'required|string|max:255',
            'peringkat' => 'nullable|integer',
        ]);

        $prestasi->update($validated);
        $siswa = Siswa::find($validated['peserta_didik_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('PrestasiSiswa');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                $oldNama = Siswa::find($oldId)->nama_lengkap;
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("A{$row}")->getValue() == $oldNama) {
                        $sheet->setCellValue("A{$row}", $siswa->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['jenis_prestasi']);
                        $sheet->setCellValue("C{$row}", $validated['tingkat_prestasi']);
                        $sheet->setCellValue("D{$row}", $validated['nama_prestasi']);
                        $sheet->setCellValue("E{$row}", $validated['tahun_prestasi']);
                        $sheet->setCellValue("F{$row}", $validated['penyelenggara']);
                        $sheet->setCellValue("G{$row}", $validated['peringkat'] ?? '');
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'prestasi.index')
            ->with('success', 'Data prestasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $prestasi = PrestasiSiswa::findOrFail($id);
        $namaSiswa = Siswa::find($prestasi->peserta_didik_id)->nama_lengkap;

        PrestasiSiswa::where('peserta_didik_id', $prestasi->peserta_didik_id)->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('PrestasiSiswa');

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

        return redirect()->route($prefix.'prestasi.index')
            ->with('success', 'Data prestasi berhasil dihapus.');
    }

}
