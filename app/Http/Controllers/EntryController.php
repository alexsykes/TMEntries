<?php

namespace App\Http\Controllers;

use App\Models\Trial;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    //
    public function create($id) {
//        dd($id);
        $trial = Trial::findOrFail($id);
        return view('entries.create', ['trial' => $trial]);
    }

    public function edit(Request $request) {
        return view('entries.edit');
    }
}
