<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\TrainingRecordsImport;
use App\Imports\MasterDataImport;
use App\Exports\MasterDataExport;
use App\Exports\TrainingRecordExport;
use App\Exports\TrainingMatrixExport;
use App\Exports\MatrixExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function import_peserta(Request $request)
    {
        // Validasi file yang diupload
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $userId = auth('')->id();

        // Jalankan import
        Excel::import(new MasterDataImport($userId), $request->file('file'));

        return redirect()->back()->with('success', 'Import Data Berhasil!');
    }

    public function export_peserta()
    {
        $date = date('Y-m-d'); // Format tanggal: Tahun-Bulan-Hari
        $fileName = 'Master Data - ' . $date . '.xlsx';
        return Excel::download(new MasterDataExport(), $fileName);
    }

    public function import_training(Request $request)
    {
        // Validasi file yang diupload
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);


        // Jalankan import
        Excel::import(new TrainingRecordsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Import Data Training Berhasil!');
    }

    public function export_training()
    {
        $date = date('Y-m-d'); // Format tanggal: Tahun-Bulan-Hari
        $fileName = 'Training Record - ' . $date . '.xlsx';
        return Excel::download(new TrainingRecordExport(), $fileName);
    }

    public function export_matrix()
    {

        $date = date('Y-m-d'); // Format tanggal: Tahun-Bulan-Hari
        $fileName = 'Matrix - ' . $date . '.xlsx';
        return Excel::download(new MatrixExport(), $fileName);
    }

    public function export_training_matrix(request $request)
    {
        $date = date('Y-m-d'); // Format tanggal: Tahun-Bulan-Hari
        $fileName = 'Training Matrix - ' . $date . '.xlsx';
        return Excel::download(new TrainingMatrixExport(), $fileName);
    }
}
