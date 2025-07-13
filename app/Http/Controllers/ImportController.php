<?php

namespace App\Http\Controllers;

use App\Imports\EntriesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    //

    public function showImportForm()
    {
        return view('.imports.import');
    }

    public function importEntries(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new EntriesImport, $request->file('file'));
            return back()->with('success', 'File imported successfully.');
        } catch (Exception $e) {
            back()->with('error', 'Error importing file',$e->getMessage());
        }
    }
}
