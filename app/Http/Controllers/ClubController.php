<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubMember;
use App\Models\MailDistribution;
use App\Models\Series;
use App\Models\Trial;
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
            abort(code: 404);
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
        return redirect('/club/profile?tab=profile');
    }

    public function clubUpdate(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'email:rfc,dns'],
            'memSecEmail' => 'email:rfc,dns',
            'name' => ['required', 'min:5', 'max:255'],
            'phone' => 'required',
            'area' => 'required',
        ]);

        $attributes['website'] = request('website', '');
        $attributes['facebook'] = request('facebook', '');
        $attributes['description'] = request('description', '');
        $attributes['section_markers'] = request('section_markers', '');
        $attributes['membershipSecretary'] = request('membershipSecretary', '');
        $attributes['memSecPhone'] = request('memSecPhone', '');

//        dd($attributes);
        $club = Club::find(request('id'));

        $club->update($attributes);
        $club->save();

        return redirect('/club/profile?tab=profile');
    }

    public function update(Request $request)
    {
        $attributes = $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'email' => ['required', 'email:rfc,dns'],
            'memSecEmail' => 'email:rfc,dns',
            'phone' => 'required',
            'area' => 'required',
        ]);

        $attributes['website'] = request('website', '');
        $attributes['facebook'] = request('facebook', '');
        $attributes['description'] = request('description', '');
        $attributes['section_markers'] = request('section_markers', '');
        $attributes['membershipSecretary'] = request('membershipSecretary', '');
        $attributes['memSecPhone'] = request('memSecPhone', '');
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
            ->where('published', true)
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
            'membership_category' => 'required',
        ]);

        $attributes['accept'] = true;
        $attributes['social'] = implode(',', request('social'));

        info($attributes['membership_category']);

        if ($attributes['membership_category'] == 'Life' || $attributes['membership_category'] == 'Observer') {
            $attributes['confirmed'] = true;
        }

        $member = ClubMember::create($attributes);

//        $product = DB::table('products')
//            ->where('club_id', $request->club_id)
//            ->where('product_category', 'membership')
//            ->first();

        return view('/clubs/confirmRegistered', ['member' => $member]);
    }

    public function console(Request $request)
    {
        $selectedTab = "Profile";
        if (isset($request->tab)) {
            $selectedTab = $request->tab;
        }
        $user = Auth::user();
        $id = $user->club_id;
        $club = DB::table('clubs')
            ->where('id', $id)
            ->first();

        $user = Auth::user();
        $userID = $user->id;
        $trials = Trial::all()
            ->where('created_by', $userID)
            ->sortByDesc('date');

        $distributionLists = DB::table('mail_distributions')
            ->where('club_id', $id)
            ->orderBy('name')
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

        return view('clubs.console', ['club' => $club, 'distributionLists' => $distributionLists, 'series' => $series, 'countItemsArray' => $countItemsArray, 'trials' => $trials, 'selectedTab' => $selectedTab]);
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
        return redirect('/club/profile?tab=mailinglist');
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
        return redirect('/club/profile?tab=mailinglist');
    }

    public function confirmRegistered()
    {
        return view('clubs.confirmRegistered');
    }

    public function memberList()
    {
        $clubID = Auth::user()->club_id;
        $clubName = DB::table('clubs')
            ->where('id', $clubID)
            ->select('name')
            ->first();

        $allMembers = DB::table('club_members')
            ->where('club_id', $clubID)
            ->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')
            ->get();

        $riders = DB::table('club_members')
            ->where('club_id', $clubID)
            ->where('membership_category', 'rider')
            ->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')
            ->get();

        $observers = DB::table('club_members')
            ->where('club_id', $clubID)
            ->where('membership_category', 'observer')
            ->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')
            ->get();

        $lifers = DB::table('club_members')
            ->where('club_id', $clubID)
            ->where('membership_category', 'life')
            ->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')
            ->get();

        return view('clubs.memberList', ['allmembers' => $allMembers, 'riders' => $riders, 'observers' => $observers, 'clubName' => $clubName, 'lifers' => $lifers]);
    }

    public function memberDetail()
    {
        $clubID = Auth::user()->club_id;
        $memberID = request('id');

        $member = DB::table('club_members')
            ->where('id', $memberID)
            ->first();

        return view('clubs.memberDetail', ['member' => $member]);
    }

}