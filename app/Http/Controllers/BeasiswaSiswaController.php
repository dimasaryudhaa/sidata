<?php

namespace App\Http\Controllers;

use App\Models\BeasiswaSiswa;
use App\Models\Siswa;
use App\Models\AkunSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class BeasiswaSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'siswa.';

        if ($isSiswa) {
            $beasiswa = DB::table('beasiswa')
                ->join('akun_siswa', 'beasiswa.peserta_didik_id', '=', 'akun_siswa.peserta_didik_id')
                ->where('akun_siswa.email', $user->email)
                ->select('beasiswa.*')
                ->orderBy('tahun_mulai', 'desc')
                ->paginate(12);

            return view('beasiswa.index', compact('beasiswa', 'isSiswa', 'isAdmin', 'prefix'));
        }

        $beasiswa = DB::table('peserta_didik')
            ->leftJoin('beasiswa', 'peserta_didik.id', '=', 'beasiswa.peserta_didik_id')
            ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
            ->select(
                'peserta_didik.id as siswa_id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.rombel_id',
                'rombel.nama_rombel',
                DB::raw('COUNT(beasiswa.id) as jumlah_beasiswa')
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

        return view('beasiswa.index', compact('beasiswa', 'rombels', 'isSiswa', 'isAdmin', 'prefix'));
    }

    public function show($siswa_id)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        $siswa = Siswa::findOrFail($siswa_id);
        $beasiswa = BeasiswaSiswa::where('peserta_didik_id', $siswa_id)->get();

        return view('beasiswa.show', compact('beasiswa', 'siswa', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        if ($user->role === 'siswa') {

            $akunSiswa = AkunSiswa::where('email', $user->email)->firstOrFail();

            $siswa = $akunSiswa->siswa;

            return view('beasiswa.create', compact('siswa', 'prefix'));
        }

        $siswaId = $request->query('siswa_id');
        if ($siswaId) {
            $siswa = Siswa::findOrFail($siswaId);
            return view('beasiswa.create', compact('siswa', 'siswaId', 'prefix'));
        }

        $siswas = Siswa::orderBy('nama_lengkap')->get();
        return view('beasiswa.create', compact('siswas', 'prefix'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_beasiswa' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'tahun_mulai' => 'nullable|digits:4|integer',
            'tahun_selesai' => 'nullable|digits:4|integer',
        ]);

        $beasiswa = BeasiswaSiswa::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('BeasiswaSiswa');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('BeasiswaSiswa');
            $sheet->fromArray([
                'Nama Siswa',
                'Jenis Beasiswa',
                'Keterangan',
                'Tahun Mulai',
                'Tahun Selesai'
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $siswa->nama_lengkap,
            $validated['jenis_beasiswa'],
            $validated['keterangan'] ?? '',
            $validated['tahun_mulai'] ?? '',
            $validated['tahun_selesai'] ?? '',
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'beasiswa.index')
            ->with('success', 'Data beasiswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isSiswa = $user->role === 'siswa';
        $prefix = $isAdmin ? 'admin.' : 'siswa.';

        $beasiswa = BeasiswaSiswa::findOrFail($id);
        $siswa = Siswa::findOrFail($beasiswa->peserta_didik_id);

        return view('beasiswa.edit', compact('beasiswa', 'siswa', 'isAdmin', 'isSiswa', 'prefix'));
    }

    public function update(Request $request, $id)
    {
        $beasiswa = BeasiswaSiswa::findOrFail($id);
        $oldId = $beasiswa->peserta_didik_id;

        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_beasiswa' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'tahun_mulai' => 'nullable|digits:4|integer',
            'tahun_selesai' => 'nullable|digits:4|integer',
        ]);

        $beasiswa->update($validated);
        $siswa = Siswa::find($validated['peserta_didik_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('BeasiswaSiswa');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                $oldNama = Siswa::find($oldId)->nama_lengkap;
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("A{$row}")->getValue() == $oldNama) {
                        $sheet->setCellValue("A{$row}", $siswa->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['jenis_beasiswa']);
                        $sheet->setCellValue("C{$row}", $validated['keterangan'] ?? '');
                        $sheet->setCellValue("D{$row}", $validated['tahun_mulai'] ?? '');
                        $sheet->setCellValue("E{$row}", $validated['tahun_selesai'] ?? '');
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix.'beasiswa.index')
            ->with('success', 'Data beasiswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $beasiswa = BeasiswaSiswa::findOrFail($id);
        $namaSiswa = Siswa::find($beasiswa->peserta_didik_id)->nama_lengkap;

        BeasiswaSiswa::where('peserta_didik_id', $beasiswa->peserta_didik_id)->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('BeasiswaSiswa');

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

        return redirect()->route($prefix.'beasiswa.index')
            ->with('success', 'Data beasiswa berhasil dihapus.');
    }

}
