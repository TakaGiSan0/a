<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\MasterDataImport;
use App\Exports\MasterDataExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function import(Request $request)
    {
        // Validasi file yang diupload
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        // Jalankan import
        Excel::import(new MasterDataImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data imported successfully!');
    }

    public function export(Request $request)
    {
        return Excel::download(new MasterDataExport(), 'users.xlsx');
    }
}