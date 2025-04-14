<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //

    public function entryList()
    {
        $userID = auth()->user()->id;

        $futureTrials = DB::table('trials')
            ->where('published', 1)
            ->whereAfterToday('date')
            ->get('id');

        $futureTrialsArray = array();
        foreach ($futureTrials as $futureTrial) {
            array_push($futureTrialsArray, $futureTrial->id);
        }
        $entries = DB::table('entries')
            ->join('trials', 'entries.trial_id', '=', 'trials.id')
            ->where('entries.created_by', $userID)
            ->whereIn('entries.status', [0, 1, 2, 3, 4, 5])
            ->whereIn('entries.trial_id', $futureTrialsArray)
            ->orderBy('entries.status')
            ->select('entries.id', 'entries.status', 'entries.name', 'entries.class', 'entries.course', 'trials.name as trial')
            ->get();
        return view('user.entry_list', compact('entries'));
    }

    public function editEntry($id)
    {
        $entryArray = DB::table('entries')
            ->join('trials', 'entries.trial_id', '=', 'trials.id')
            ->where('entries.id', $id)
            ->get(['entries.*', 'trials.name as trial_name', 'trials.club as club', 'trials.classlist', 'trials.courselist']);
        $entry = $entryArray[0];

        return view('user.edit_entry', ['entry' => $entry]);
    }
}
