<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

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
        $toPays = DB::table('entries')
            ->join('trials', 'entries.trial_id', '=', 'trials.id')
            ->where('entries.created_by', $userID)
            ->where('entries.status', 0)
            ->whereIn('entries.trial_id', $futureTrialsArray)
            ->orderBy('entries.status')
            ->select('entries.id', 'entries.status', 'entries.name', 'entries.class', 'entries.course', 'trials.name as trial')
            ->get();

        $entries = DB::table('entries')
            ->join('trials', 'entries.trial_id', '=', 'trials.id')
            ->where('entries.created_by', $userID)
            ->whereIn('entries.status', [1, 2, 3, 4, 5, 7, 8, 9])
            ->whereIn('entries.trial_id', $futureTrialsArray)
            ->orderBy('entries.status')
            ->select('entries.id', 'entries.status', 'entries.name', 'entries.class', 'entries.course', 'trials.name as trial')
            ->get();

//        $query = DB::select('SELECT COUNT(e.id) AS numToPays FROM tme_entries AS e LEFT JOIN tme_trials AS t ON e.trial_id = t.id WHERE e.created_by = 1 AND e.status = 0 AND t.`date` > NOW()');
//    $numToPays = $query[0]->numToPays;

        return view('user.entry_list', compact('entries', 'toPays'));
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

    public function updateEntry(Request $request) {
//dd(request()->all());
        $id = $request->entryID;
        $entry = Entry::findorfail($id);
        $request->validate([
            'class' => 'required',
            'course' => 'required',
            'make' => 'required',
            'type' => 'required',
        ]);

        $entry->class = $request->class;
        $entry->course = $request->course;
        $entry->make = $request->make;
        $entry->type = $request->type;
        $entry->size = $request->size;
        $entry->save();


        return redirect('/user/entries');
    }

    public function checkout() {


        return redirect('stripe/usercheckout');
    }
}
