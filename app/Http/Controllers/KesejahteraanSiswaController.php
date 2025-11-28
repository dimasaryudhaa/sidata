<?php

namespace App\Http\Controllers;

use App\Models\KesejahteraanSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class KesejahteraanSiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSiswa = $user->role === 'siswa';
        $isAdmin = $user->role === 'admin';
        $prefix = $isAdmin ? 'admin.' : 'siswa.';

        if ($isSiswa) {
            $kesejahteraan = DB::table('kesejahteraan')
                ->join('akun_siswa', 'kesejahteraan.peserta_didik_id', '=', 'akun_siswa.peserta_didik_id')
                ->where('akun_siswa.email', $user->email)
                ->select('kesejahteraan.*')
                ->orderBy('jenis_kesejahteraan')
                ->paginate(12);

            return view('kesejahteraan-siswa.index', compact(
                'kesejahteraan',
                'isSiswa',
                'isAdmin',
                'prefix'
            ));
        }

        $kesejahteraan = DB::table('peserta_didik')
            ->leftJoin('kesejahteraan', 'peserta_didik.id', '=', 'kesejahteraan.peserta_didik_id')
            ->leftJoin('rombel', 'peserta_didik.rombel_id', '=', 'rombel.id')
            ->select(
                'peserta_didik.id as siswa_id',
                'peserta_didik.nama_lengkap',
                'peserta_didik.rombel_id',
                'rombel.nama_rombel',
                DB::raw('COUNT(kesejahteraan.id) as jumlah_kesejahteraan')
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

        return view('kesejahteraan-siswa.index', compact(
            'kesejahteraan',
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
        $kesejahteraan = KesejahteraanSiswa::where('peserta_didik_id', $siswa_id)->get();

        return view('kesejahteraan-siswa.show', compact('kesejahteraan', 'siswa', 'prefix'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isSiswa = $user->role === 'siswa';
        $prefix = $isAdmin ? 'admin.' : 'siswa.';

        $siswaId = $request->query('siswa_id');

        if ($siswaId) {
            $siswa = Siswa::findOrFail($siswaId);
            return view('kesejahteraan-siswa.create', compact(
                'siswaId',
                'siswa',
                'isAdmin',
                'isSiswa',
                'prefix'
            ));
        }
        $siswa = Siswa::orderBy('nama_lengkap')->get();

        return view('kesejahteraan-siswa.create', compact(
            'siswa',
            'isAdmin',
            'isSiswa',
            'prefix'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_kesejahteraan' => 'required|in:PKH,PIP,Kartu Perlindungan Sosial,Kartu Keluarga Sejahtera,Kartu Kesehatan',
            'no_kartu' => 'nullable|string|max:50',
            'nama_di_kartu' => 'nullable|string|max:100',
        ]);

        $kesejahteraan = KesejahteraanSiswa::create($validated);
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

        $sheet = $spreadsheet->getSheetByName('KesejahteraanSiswa');
        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('KesejahteraanSiswa');
            $sheet->fromArray([
                'Nama Siswa',
                'Jenis Kesejahteraan',
                'No. Kartu',
                'Nama di Kartu'
            ], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->fromArray([
            $siswa->nama_lengkap,
            $validated['jenis_kesejahteraan'],
            $validated['no_kartu'] ?? '',
            $validated['nama_di_kartu'] ?? '',
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix . 'kesejahteraan-siswa.index')
            ->with('success', 'Data kesejahteraan siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isSiswa = $user->role === 'siswa';
        $prefix = $isAdmin ? 'admin.' : 'siswa.';

        $kesejahteraan = KesejahteraanSiswa::findOrFail($id);
        $siswa = Siswa::findOrFail($kesejahteraan->peserta_didik_id);

        return view('kesejahteraan-siswa.edit', compact(
            'kesejahteraan',
            'siswa',
            'isAdmin',
            'isSiswa',
            'prefix'
        ));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'peserta_didik_id' => 'required|exists:peserta_didik,id',
            'jenis_kesejahteraan' => 'required|in:PKH,PIP,Kartu Perlindungan Sosial,Kartu Keluarga Sejahtera,Kartu Kesehatan',
            'no_kartu' => 'nullable|string|max:50',
            'nama_di_kartu' => 'nullable|string|max:100',
        ]);

        $kesejahteraan = KesejahteraanSiswa::findOrFail($id);
        $oldId = $kesejahteraan->peserta_didik_id;
        $kesejahteraan->update($validated);
        $siswa = Siswa::find($validated['peserta_didik_id']);

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('KesejahteraanSiswa');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($sheet->getCell("A{$row}")->getValue() == Siswa::find($oldId)->nama_lengkap) {
                        $sheet->setCellValue("A{$row}", $siswa->nama_lengkap);
                        $sheet->setCellValue("B{$row}", $validated['jenis_kesejahteraan']);
                        $sheet->setCellValue("C{$row}", $validated['no_kartu'] ?? '');
                        $sheet->setCellValue("D{$row}", $validated['nama_di_kartu'] ?? '');
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'siswa.';

        return redirect()->route($prefix . 'kesejahteraan-siswa.index')
            ->with('success', 'Data kesejahteraan siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kesejahteraan = KesejahteraanSiswa::findOrFail($id);
        $namaSiswa = Siswa::find($kesejahteraan->peserta_didik_id)->nama_lengkap;
        $kesejahteraan->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('KesejahteraanSiswa');

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

        return redirect()->route($prefix . 'kesejahteraan-siswa.index')
            ->with('success', 'Data kesejahteraan siswa berhasil dihapus.');
    }

}
