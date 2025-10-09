<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubMember;
use App\Models\MailDistribution;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClubController extends Controller
{
    //

    public function list()
    {
        $clubs = Club::all()
            ->sortBy('name');
        return view('clubs.list', compact('clubs'));
    }

    public function profile(Request $request)
    {
        $user = Auth::user();

        if (!$user->isClubUser) {
            abort(404);
        }
        $clubID = $user->club_id;

        $club = Club::findOrfail($clubID);
        $series = Series::where('clubID', $clubID)
            ->get();
//        dd($series);

        return view('clubs.profile', ['club' => $club, 'series' => $series]);
    }

    public function clublist()
    {
        $clubs = Club::all()
            ->sortBy('name');

        foreach ($clubs as $club) {
            $club->series = DB::table('series')
                ->where('clubID', $club->id)
                ->get();
        }

        return view('clubs.clublist', ['clubs' => $clubs]);
    }

//    public function detail(Request $request) {
//        $club = Club::find(request('id'));
////        dd($club);
//        return view('clubs.detail', ['club' => $club]);
//    }
    public function edit(Request $request)
    {
        $club = Club::find(request('id'));
        return view('clubs.edit', ['club' => $club]);
    }

    public function add()
    {
        return view('clubs.new');
    }

    public function store(Request $request)
    {
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

    public function clubUpdate(Request $request)
    {
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

    public function update(Request $request)
    {
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

    public function editProfile()
    {
        $user = Auth::user();
        $clubID = $user->club_id;
        $club = Club::find($clubID);
        return view('clubs.editprofile', ['club' => $club]);
    }

    public function mailList()
    {
        $user = Auth::user();
        $clubID = $user->club_id;

        $mails = DB::table('clubmails')
            ->where('club_id', $clubID)
            ->orWhere('isLibrary', true)
            ->orderBy('isLibrary', 'desc')
            ->orderBy('category')
            ->orderBy('subject')
            ->get();

        return view('clubs.maillist', ['mails' => $mails]);
    }

    public function membershipForm($id)
    {
        return view('clubs.membership', ['club_id' => $id]);
    }

    public function addMember(Request $request)
    {
//        dd($request->all());
        $attributes = $request->validate([
            'firstname' => ['required', 'min:2', 'max:255'],
            'lastname' => ['required', 'min:2', 'max:255'],
            'club_id' => 'required',
            'email' => ['required', 'email'],
            'phone' => 'required',
            'address' => 'required',
            'postcode' => 'required',
            'emergency_contact' => 'required',
            'emergency_number' => 'required',
            'social' => 'required',
            'membership_type' => 'required',
            'accept' => 'required',
        ]);

        $attributes['social'] = implode(request('social'));
        $member = ClubMember::create($attributes);

        $product = DB::table('products')
            ->where('club_id', $request->club_id)
            ->where('product_category', 'membership')
            ->first();

//        dd($product, $member);

        return redirect('/clubs/checkout', ['member' => $member, 'product' => $product]);
    }

    public function console()
    {
        $user = Auth::user();
        $id = $user->club_id;
        $club = DB::table('clubs')
            ->where('id', $id)
            ->first();

        $distributionLists = DB::table('mail_distributions')
            ->where('club_id', $id)
            ->get();

        $countItemsArray = array();
        foreach ($distributionLists as $distributionList) {
            $to = explode(",", $distributionList->to);
            array_push($countItemsArray, sizeof($to));
        }

        $trials = DB::table('trials')
            ->where('club_id', $id)
            ->get();

        $series = Series::where('clubID', $id)
            ->get();

        return view('clubs.console', ['club' => $club, 'distributionLists' => $distributionLists, 'trials' => $trials, 'series' => $series, 'countItemsArray' => $countItemsArray]);
    }

    public function addDistribution()
    {
        return view('clubs.addDistributionList');
    }

    public function editDistribution($id)
    {
        $listItem = MailDistribution::findOrFail($id);
        return view('clubs.editDistributionList', ['listItem' => $listItem]);
    }

    public function storeDistribution(Request $request)
    {
        $user = Auth::user();
        $club_id = $user->club_id;
        $created_by = $user->id;
        $attributes = $request->validate([
            'name' => 'required',
            'to' => 'required',
            'description' => 'required',
        ]);

        $attributes['club_id'] = $club_id;
        $attributes['created_by'] = $created_by;
        MailDistribution::create($attributes);
    }

    public function updateDistribution(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required',
            'to' => 'required',
            'description' => 'required',
        ]);
        $item = MailDistribution::find(request('itemID'));

        $item->update($attributes);
        $item->save();
    }
}