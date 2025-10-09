<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeriesController extends Controller
{
    //
    public function list()
    {
        $user = auth()->user();
        if (!$user->isClubUser) {
            abort(403);
        }

        $clubID = $user->club_id;

        $club = Club::find($clubID);
        $clubName = $club->name;

        $allSeries = DB::table('series')
            ->join('clubs', 'series.clubID', '=', 'clubs.id')
            ->select('series.*', 'clubs.name as club')
            ->where('clubs.id', $clubID)
            ->orderBy('clubs.name')
            ->get();

        return view('series.list', ['series' => $allSeries, 'clubName' => $clubName]);
    }

    public function detail(Request $request)
    {

        $series = DB::table('series')
            ->join('clubs', 'series.clubID', '=', 'clubs.id')
            ->where('series.id', request('id'))
            ->select('series.*', 'clubs.name as club')
            ->first();

        return view('series.detail', ['series' => $series]);
    }

    public function edit()
    {
        $series = DB::table('series')
            ->join('clubs', 'series.clubID', '=', 'clubs.id')
            ->where('series.id', request('id'))
            ->select('series.*', 'clubs.name as club')
            ->first();
        return view('series.edit', ['series' => $series]);
    }

    public function add()
    {
        $user = auth()->user();
        $clubID = $user->club_id;

        $club = Club::find($clubID);
        $clubName = $club->name;

        return view('series.add', ['club' => $club]);
    }

    public function store()
    {
        $attributes = request()->validate([
            'name' => 'required',
            'clubID' => 'required',
            'classes' => 'required',
            'courses' => 'required',
            'notes' => 'required',
            'description' => 'required',
        ]);
        $series = Series::create($attributes);
        return redirect('/club/profile');
    }

    public function update()
    {
        $seriesID = request()->id;
        $series = Series::findOrFail($seriesID);
        $attributes = request()->validate([
            'name' => 'required',
            'classes' => 'required',
            'courses' => 'required',
            'notes' => 'required',
            'description' => 'required',
        ]);
        $series->update($attributes);
        $series->save();
        return redirect('/club/profile');
    }
}
