<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Models\Jurusan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class RombelController extends Controller
{
    public function index()
    {
        $rombel = Rombel::with('jurusan')->orderBy('nama_rombel', 'asc')->paginate(20);
        return view('rombel.index', compact('rombel'));
    }

    public function create()
    {
        $jurusan = Jurusan::all();
        return view('rombel.create', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jurusan_id' => 'required|integer|exists:jurusan,id',
            'nama_rombel' => 'required|string|max:100',
        ]);

        $rombel = Rombel::create($request->all());

        $namaJurusan = Jurusan::find($request->jurusan_id)->nama_jurusan;

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

        $sheet = $spreadsheet->getSheetByName('Rombel');

        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('Rombel');
            $sheet->fromArray(['Nama Jurusan', 'Nama Rombel', 'Tingkat'], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;

        $sheet->fromArray([
            $namaJurusan,
            $rombel->nama_rombel,
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        return redirect()->route('admin.rombel.index')
            ->with('success', 'Rombel berhasil ditambahkan.');
    }

    public function edit(Rombel $rombel)
    {
        $jurusan = Jurusan::all();
        return view('rombel.edit', compact('rombel', 'jurusan'));
    }

    public function update(Request $request, Rombel $rombel)
    {
        $request->validate([
            'jurusan_id' => 'required|integer|exists:jurusan,id',
            'nama_rombel' => 'required|string|max:100',
            'tingkat' => 'nullable|in:X,XI,XII',
        ]);

        $oldNamaJurusan = Jurusan::find($rombel->jurusan_id)->nama_jurusan;
        $oldNamaRombel = $rombel->nama_rombel;

        $rombel->update([
            'jurusan_id' => $request->jurusan_id,
            'nama_rombel' => $request->nama_rombel,
            'tingkat' => $request->tingkat,
        ]);

        $newNamaJurusan = Jurusan::find($request->jurusan_id)->nama_jurusan;
        $newNamaRombel = $request->nama_rombel;

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('Rombel');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++) {

                    if (
                        trim($sheet->getCell("A{$row}")->getValue()) === trim($oldNamaJurusan) &&
                        trim($sheet->getCell("B{$row}")->getValue()) === trim($oldNamaRombel)
                    ) {
                        $sheet->setCellValue("A{$row}", $newNamaJurusan);
                        $sheet->setCellValue("B{$row}", $newNamaRombel);
                        $sheet->setCellValue("C{$row}", $request->tingkat);

                        break;
                    }
                }

                (new Xlsx($spreadsheet))->save($path);
            }
        }

        return redirect()->route('admin.rombel.index')
            ->with('success', 'Rombel berhasil diupdate.');
    }

    public function destroy(Rombel $rombel)
    {
        if ($rombel->pesertaDidik()->exists()) {
            return redirect()->route('admin.rombel.index')
                ->with('error', 'Rombel tidak dapat dihapus karena masih memiliki peserta didik.');
        }

        $jurusan = $rombel->jurusan_id;
        $nama = $rombel->nama_rombel;

        $rombel->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('Rombel');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if (
                        $sheet->getCell("A{$row}")->getValue() == $jurusan &&
                        $sheet->getCell("B{$row}")->getValue() == $nama
                    ) {
                        $sheet->removeRow($row, 1);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        return redirect()->route('admin.rombel.index')->with('success', 'Rombel berhasil dihapus.');
    }

}
