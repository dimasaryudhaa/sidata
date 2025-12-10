<?php

namespace App\Http\Controllers;

use App\Models\Ptk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class PtkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $isAdmin = $user->role === 'admin';

        if ($isPtk) {
            $ptk = DB::table('akun_ptk')
                ->join('ptk', 'akun_ptk.ptk_id', '=', 'ptk.id')
                ->where('akun_ptk.email', $user->email)
                ->select(
                    'ptk.id',
                    'ptk.nama_lengkap',
                    'ptk.jenis_ptk',
                    'ptk.nik',
                    'ptk.jenis_kelamin',
                    'ptk.tempat_lahir',
                    'ptk.tanggal_lahir',
                    'ptk.nama_ibu_kandung',
                    'ptk.agama',
                    'ptk.npwp',
                    'ptk.nama_wajib_pajak',
                    'ptk.kewarganegaraan',
                    'ptk.negara_asal'
                )
                ->paginate(1);

            return view('ptk.index', compact('ptk', 'isPtk', 'isAdmin'));
        } else {
            $ptk = DB::table('ptk')
                ->select(
                    'ptk.id',
                    'ptk.nama_lengkap',
                    'ptk.jenis_ptk',
                    'ptk.jenis_kelamin',
                    'ptk.tempat_lahir',
                    'ptk.tanggal_lahir'
                )
                ->orderBy('ptk.nama_lengkap', 'asc')
                ->paginate(50);

            return view('ptk.index', compact('ptk', 'isPtk'));
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
        return view('ptk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_ptk' => 'required|in:Guru,Staf,Laboran',
            // 'nik' => 'required|string|size:16|unique:ptk,nik',
            // 'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            // 'tempat_lahir' => 'required|string|max:100',
            // 'tanggal_lahir' => 'required|date',
            // 'nama_ibu_kandung' => 'required|string|max:255',
        ]);

        $ptk = Ptk::create($request->all());

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

        $sheet = $spreadsheet->getSheetByName('PTK');

        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('PTK');
            $sheet->fromArray(
                ['Nama Lengkap', 'Jenis PTK', 'NIK', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Nama Ibu Kandung'],
                null,
                'A1'
            );
        }

        $lastRow = $sheet->getHighestRow() + 1;

        $sheet->fromArray([
            $ptk->nama_lengkap,
            $ptk->jenis_ptk,
            $ptk->nik,
            $ptk->jenis_kelamin,
            $ptk->tempat_lahir,
            $ptk->tanggal_lahir,
            $ptk->nama_ibu_kandung,
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'ptk.index')->with('success', 'Data PTK berhasil ditambahkan!');
    }

    public function show(Ptk $ptk)
    {
        return view('ptk.show', compact('ptk'));
    }

    public function edit(Ptk $ptk)
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $isPtk = $user->role === 'ptk';

        return view('ptk.edit', [
            'ptk' => $ptk,
            'isAdmin' => $isAdmin,
            'isPtk' => $isPtk,
        ]);
    }

    public function update(Request $request, Ptk $ptk)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_ptk' => 'required|in:Guru,Staf,Laboran',
        ]);

        $oldNama = $ptk->nama_lengkap;

        $ptk->update($request->all());

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('PTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++) {

                    if ($sheet->getCell("A{$row}")->getValue() == $oldNama) {

                        $sheet->fromArray([
                            $ptk->nama_lengkap,
                            $ptk->jenis_ptk,
                            $ptk->nik,
                            $ptk->jenis_kelamin,
                            $ptk->tempat_lahir,
                            $ptk->tanggal_lahir,
                            $ptk->nama_ibu_kandung,
                        ], null, "A{$row}");

                        break;
                    }
                }

                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $prefix = Auth::user()->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'ptk.index')->with('success', 'Data PTK berhasil diperbarui!');
    }

    public function destroy(Ptk $ptk)
    {
        $deleteKey = [$ptk->nama_lengkap, $ptk->nik];

        $ptk->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('PTK');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if (
                        $sheet->getCell("A{$row}")->getValue() == $deleteKey[0] &&
                        $sheet->getCell("B{$row}")->getValue() == $deleteKey[1]
                    ) {
                        $sheet->removeRow($row, 1);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        $user = Auth::user();
        $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';

        return redirect()->route($prefix.'ptk.index')->with('success', 'Data PTK berhasil dihapus!');
    }

}
