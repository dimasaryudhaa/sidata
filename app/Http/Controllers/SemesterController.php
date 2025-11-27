<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semester;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class SemesterController extends Controller
{
    public function index()
    {
        $semester = Semester::orderBy('tahun_ajaran', 'desc')
            ->orderBy('nama_semester', 'asc')
            ->paginate(12);
        return view('semester.index', compact('semester'));
    }

    public function create()
    {
        return view('semester.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_semester' => 'required|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        $semester = Semester::create($request->all());

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

        $sheet = $spreadsheet->getSheetByName('Semester');

        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('Semester');
            $sheet->fromArray(['Nama Semester', 'Tahun Ajaran'], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;

        $sheet->fromArray([
            $semester->nama_semester,
            $semester->tahun_ajaran,
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil ditambahkan.');
    }

    public function edit(Semester $semester)
    {
        return view('semester.edit', compact('semester'));
    }

    public function update(Request $request, Semester $semester)
    {
        $request->validate([
            'nama_semester' => 'required|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        $oldNama = $semester->nama_semester;
        $oldTahun = $semester->tahun_ajaran;

        $semester->update($request->all());

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('Semester');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if (
                        $sheet->getCell("A{$row}")->getValue() === $oldNama &&
                        $sheet->getCell("B{$row}")->getValue() === $oldTahun
                    ) {
                        $sheet->setCellValue("A{$row}", $semester->nama_semester);
                        $sheet->setCellValue("B{$row}", $semester->tahun_ajaran);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil diupdate.');
    }

    public function destroy(Semester $semester)
    {
        $nama = $semester->nama_semester;
        $tahun = $semester->tahun_ajaran;

        $semester->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('Semester');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if (
                        $sheet->getCell("A{$row}")->getValue() === $nama &&
                        $sheet->getCell("B{$row}")->getValue() === $tahun
                    ) {
                        $sheet->removeRow($row, 1);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil dihapus.');
    }
    
}
