<?php

namespace App\Http\Controllers;

use App\Mail\RenewalAcknowledgement;
use App\Mail\WelcomeNewMember;
use App\Models\Club;
use App\Models\ClubMember;
use App\Models\MailDistribution;
use App\Models\Series;
use App\Models\Trial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

        $categoryArray = array('AGM', 'Committee Meetings', 'Trials', 'Social Events ', 'Other');

        $mailData = array();
        foreach ($categoryArray as $category) {
            $mails = DB::table('clubmails')
                ->where('club_id', $clubID)
                ->where('published', true)
                ->where('category', $category)
                ->orderBy('updated_at', 'desc')
                ->get(['id', 'updated_at', 'subject', 'summary']);

            if (count($mails) > 0) {
                $mailData[$category] = $mails;
            }
        }
        return view('clubs.maillist', ['mails' => $mails, 'categoryArray' => $categoryArray, 'mailData' => $mailData]);
    }

    public function mailList_()
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


    public function membershipForm(Request $request, $id)
    {
        $oldValues = $request->old();
        return view('clubs.membership', ['club_id' => $id, 'oldValues' => $oldValues]);
    }

    public function addMember(Request $request)
    {
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
            'accept' => 'required',
//            'g-recaptcha-response' => ['required', new ReCaptchaV3('register')],
        ]);

        $attributes['accept'] = true;
        $attributes['social'] = implode(',', request('social'));

//        info($attributes['membership_category']);

        if ($attributes['membership_category'] == 'life' || $attributes['membership_category'] == 'observer') {
            $attributes['confirmed'] = true;
        } else {
            $attributes['membership_category'] = 'competition';
            $attributes['confirmed'] = false;
        }

        $member = ClubMember::create($attributes);
//        Add to Observer mailing list
        if ($attributes['membership_category'] == 'observer') {
            $email = trim($attributes['email']);

            $observerList = MailDistribution::where('club_id', $request->club_id)
                ->where('name', 'Observers')
                ->first();

            $addressList = $observerList['to'];
            $addressListArray = explode(',', $addressList);
            array_push($addressListArray, $email);
            $addressListArray = array_unique($addressListArray);
            $addressList = implode(',', $addressListArray);

            $observerList->to = $addressList;
            $observerList->update();
        }

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
            ->where('membership_category', 'competition')
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

    public function membershipConfirm($id)
    {
        $club_member = ClubMember::findOrFail($id);
        $club_member->confirmed = 1;
        $club_member->save();

        $bcc = 'monster@trialmonster.uk';
        $amanda = 'ammnewhouse@gmail.com';

        if ($club_member->membership_type == 'new') {
            info("Send welcome email to $club_member->email");

            Mail::to($club_member->email)
                ->bcc($bcc)
//                ->bcc($amanda)
                ->send(new WelcomeNewMember($club_member));


        } else {
            info("Send acknowledgement email to $club_member->email");
            Mail::to($club_member->email)
                ->bcc($bcc)
//                ->bcc($amanda)
                ->send(new RenewalAcknowledgement($club_member));
        }
        return redirect('/club/member/list');
    }

    public function memberApprove()
    {
        $user = Auth::user();
        if ($user->isClubUser) {
            $clubID = $user->club_id;
            $clubName = DB::table('clubs')
                ->where('id', $clubID)
                ->select('name')
                ->first();

            $paidNames = DB::table('clubs')
                ->where('id', 5)
                ->select('confirmed_list')
                ->first();

            $membersToApprove = DB::table('club_members')
                ->where('club_id', $clubID)
                ->where('confirmed', false)
                ->orderBy('firstname', 'asc')
                ->get();

            return view('clubs.memberApprove', ['club' => $clubName, 'membersToApprove' => $membersToApprove, 'paidNames' => $paidNames]);
        } else {
            return redirect('/');
        }
    }

    public function memberApprovalUpdate(Request $request)
    {
        if (Auth::user()->isClubUser) {
            $clubID = Auth::user()->club_id;

            $memberIDs = request('approved');
            if ($memberIDs != null) {
                foreach ($memberIDs as $memberID) {
                    $club_member = ClubMember::findOrFail($memberID);
                    $club_member->confirmed = true;
                    $club_member->save();

                    $bcc = 'monster@trialmonster.uk';
                    $amanda = 'ammnewhouse@gmail.com';

                    if ($club_member->membership_type == 'new') {
                        info("Send welcome email to $club_member->email");

                        Mail::to($club_member->email)
                            ->bcc($bcc)
//                ->bcc($amanda)
                            ->send(new WelcomeNewMember($club_member));


                    } else {
                        info("Send acknowledgement email to $club_member->email");
                        Mail::to($club_member->email)
                            ->bcc($bcc)
//                ->bcc($amanda)
                            ->send(new RenewalAcknowledgement($club_member));
                    }
                }
            }
            return redirect('/club/member/approve');
        }
    }
}