<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    //

    public function create_result( $entry) {
        dd( $entry);
    }

    public function list() {
        $pastTrials = DB::table( 'trials' )
        ->where('published', 1)
            ->whereBeforeToday('date')
        ->orderBy('date', 'desc')
            ->get(['name', 'club', 'date', 'id']);
// dd($pastTrials);
        return view('results.list', ['pastTrials' => $pastTrials]);
    }

    public function display($id) {
        $trial = DB::table( 'trials' )
            ->where('id', $id)
            ->get(['name', 'club', 'date']);


        $entries = DB::table( 'entries' )
            ->join('results', 'entries.id', '=', 'results.entryID')
            ->where('entries.trial_id', $id)
            ->groupBy('entries.course')
        ->get();

        dd($entries);

        return view('results.detail', ['trial' => $trial, 'entries' => $entries]);
    }
}
