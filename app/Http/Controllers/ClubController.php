<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    //

    public function list() {
        $clubs = Club::all()
        ->sortBy('name');
        return view('clubs.list', compact('clubs'));
    }

    public function profile(Request $request) {
        $user = Auth::user();
        $clubID = $user->club_id;

        $club = Club::find($clubID);
        $series = Series::where('clubID', $clubID)
        ->get();
//        dd($series);

        return view('clubs.profile', ['club' => $club, 'series' => $series]);
    }

    public function clublist(){
        $clubs = Club::all()
        ->sortBy('name');

        foreach ($clubs as $club) {
            $club->series = DB::table('series')
            ->where('clubID', $club->id)
            ->get();
        }

        return view('clubs.clublist', ['clubs' => $clubs]);
    }

    public function detail(Request $request) {
        $club = Club::find(request('id'));
//        dd($club);
        return view('clubs.detail', ['club' => $club]);
    }
    public function edit(Request $request) {
        $club = Club::find(request('id'));
        return view('clubs.edit', ['club' => $club]);
    }

    public function add() {
        return view('clubs.new');
    }

    public function store(Request $request) {
        $attributes = $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'email' => 'required',
            'phone' => 'required',
            'area' => 'required',
        ]);

        $attributes['website'] = request('website', '');
        $attributes['facebook'] = request('facebook', '');
        $attributes['description'] = request('description', '');
        $attributes['section_markers'] = request('section_markers', '');

        $club = Club::create($attributes);
//        dd($club);
        return redirect('/clubs/list');
    }

    public function update(Request $request) {
        $attributes = $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'email' => 'required',
            'phone' => 'required',
            'area' => 'required',
        ]);

        $attributes['website'] = request('website', '');
        $attributes['facebook'] = request('facebook', '');
        $attributes['description'] = request('description', '');
        $attributes['section_markers'] = request('section_markers', '');
        $club = Club::find(request('id'));

        $club->update($attributes);
        $club->save();

        return redirect('/clubs/list');
    }
    public function clubUpdate(Request $request) {
        $attributes = $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'email' => 'required',
            'phone' => 'required',
            'area' => 'required',
        ]);

        $attributes['website'] = request('website', '');
        $attributes['facebook'] = request('facebook', '');
        $attributes['description'] = request('description', '');
        $attributes['section_markers'] = request('section_markers', '');
        $club = Club::find(request('id'));

        $club->update($attributes);
        $club->save();

        return redirect('/club/profile');
    }

    public function editProfile(Request $request) {
        $user = Auth::user();
        $clubID = $user->club_id;

        $club = Club::find($clubID);

        return view('clubs.editprofile', ['club' => $club]);
    }
}