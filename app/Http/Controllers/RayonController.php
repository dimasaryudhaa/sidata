<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rayon;
use App\Models\Ptk;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class RayonController extends Controller
{
    public function index()
    {
        $rayon = Rayon::with('ptk')
            ->orderBy('nama_rayon', 'asc')
            ->paginate(10);

        return view('rayon.index', compact('rayon'));
    }

    public function create()
    {
        $ptks = Ptk::orderBy('nama_lengkap', 'asc')->get();

        return view('rayon.create', compact('ptks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ptk_id' => 'required|integer',
            'nama_rayon' => 'required|string|max:255',
        ]);

        $rayon = Rayon::create($request->all());

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

        $sheet = $spreadsheet->getSheetByName('Rayon');

        if (!$sheet) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('Rayon');
            $sheet->fromArray(['ID PTK', 'Nama Rayon'], null, 'A1');
        }

        $lastRow = $sheet->getHighestRow() + 1;

        $sheet->fromArray([
            $rayon->ptk_id,
            $rayon->nama_rayon,
        ], null, "A{$lastRow}");

        (new Xlsx($spreadsheet))->save($path);

        return redirect()->route('admin.rayon.index')->with('success', 'Rayon berhasil ditambahkan.');
    }

    public function edit(Rayon $rayon)
    {
        $ptks = Ptk::orderBy('nama_lengkap', 'asc')->get();

        return view('rayon.edit', compact('rayon', 'ptks'));
    }

    public function update(Request $request, Rayon $rayon)
    {
        $request->validate([
            'ptk_id' => 'required|integer',
            'nama_rayon' => 'required|string|max:255',
        ]);

        $oldPtk = $rayon->ptk_id;
        $oldNama = $rayon->nama_rayon;

        $rayon->update($request->all());

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('Rayon');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if (
                        $sheet->getCell("A{$row}")->getValue() == $oldPtk &&
                        $sheet->getCell("B{$row}")->getValue() == $oldNama
                    ) {
                        $sheet->setCellValue("A{$row}", $rayon->ptk_id);
                        $sheet->setCellValue("B{$row}", $rayon->nama_rayon);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        return redirect()->route('admin.rayon.index')->with('success', 'Rayon berhasil diupdate.');
    }

    public function destroy(Rayon $rayon)
    {
        $ptk = $rayon->ptk_id;
        $nama = $rayon->nama_rayon;

        $rayon->delete();

        $path = storage_path('app/exports/sidata.xlsx');

        if (file_exists($path)) {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('Rayon');

            if ($sheet) {
                $highestRow = $sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row++) {
                    if (
                        $sheet->getCell("A{$row}")->getValue() == $ptk &&
                        $sheet->getCell("B{$row}")->getValue() == $nama
                    ) {
                        $sheet->removeRow($row, 1);
                        break;
                    }
                }
                (new Xlsx($spreadsheet))->save($path);
            }
        }

        return redirect()->route('admin.rayon.index')->with('success', 'Rayon berhasil dihapus.');
    }
}
