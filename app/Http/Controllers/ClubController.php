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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ClubController extends Controller
{
    //

    public function list()
    {
        $clubs = Club::all()
            ->sortBy('name');

        return view('clubs.list', compact('clubs'));
    }

//    public function profile(Request $request)
//    {
//        $user = Auth::user();
//        if (!$user->isClubUser) {
//            abort(code: 404);
//        }
//        $clubID = $user->club_id;
//
//        $club = Club::findOrfail($clubID);
//        $series = Series::where('clubID', $clubID)
//            ->get();
//
//        return view('clubs.profile', ['club' => $club, 'series' => $series]);
//    }

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
    // //        dd($club);
    //        return view('clubs.detail', ['club' => $club]);
    //    }
//    public function edit(Request $request)
//    {
//        $club = Club::find(request('id'));
//        return view('clubs.edit', ['club' => $club]);
//    }
//
//    public function add()
//    {
//        return view('clubs.new');
//    }
//
//    public function store(Request $request)
//    {
//        $attributes = $request->validate([
//            'name' => ['required', 'min:5', 'max:255'],
//            'email' => 'required',
//            'phone' => 'required',
//            'area' => 'required',
//        ]);
//
//        $attributes['website'] = request('website', '');
//        $attributes['facebook'] = request('facebook', '');
//        $attributes['description'] = request('description', '');
//        $attributes['section_markers'] = request('section_markers', '');
//
//        $club = Club::create($attributes);
//
//        return redirect('/club/profile?tab=profile');
//    }

    public function clubUpdate(Request $request)
    {
        $user = Auth::user();
        $isPermitted = $user->club_id == request('id') ? true : false;

        $ip = $request->ip();
        if (!$isPermitted) {
            info("Club member $user->id illegal access attempt - IP: $ip");
            abort('403');
        } else {
            info("$user->name clubUpdate - IP: $ip");
        }
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

//    public function update(Request $request)
//    {
//        $attributes = $request->validate([
//            'name' => ['required', 'min:5', 'max:255'],
//            'email' => ['required', 'email:rfc,dns'],
//            'memSecEmail' => 'email:rfc,dns',
//            'phone' => 'required',
//            'area' => 'required',
//        ]);
//
//        $attributes['website'] = request('website', '');
//        $attributes['facebook'] = request('facebook', '');
//        $attributes['description'] = request('description', '');
//        $attributes['section_markers'] = request('section_markers', '');
//        $attributes['membershipSecretary'] = request('membershipSecretary', '');
//        $attributes['memSecPhone'] = request('memSecPhone', '');
//        $club = Club::find(request('id'));
//
//        $club->update($attributes);
//        $club->save();
//
//        return redirect('/clubs/list');
//    }

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

        $categoryArray = ['AGM', 'Committee Meetings', 'Trials', 'Social Events ', 'Other'];

        $mailData = [];
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
//dd($mailData);
        $mailshotData = DB::table('mailshots')
            ->where('club_id', $clubID)
            ->whereFuture('send_at')
            ->orderBy('send_at')
            ->get(['id', 'updated_at', 'subject', 'send_at', 'distribution']);

        $sent = DB::table('mailshots')
            ->where('club_id', $clubID)
            ->where('sent', true)
            ->orderBy('send_at', 'desc')
            ->get(['id', 'updated_at', 'subject', 'sent_at', 'distribution']);

        return view('clubs.maillist', ['mails' => $mails, 'categoryArray' => $categoryArray, 'mailData' => $mailData, 'mailshotData' => $mailshotData, 'sent' => $sent]);
    }

//    public function mailList_()
//    {
//        $user = Auth::user();
//        $clubID = $user->club_id;
//
//        $mails = DB::table('clubmails')
//            ->where('club_id', $clubID)
//            ->where('published', true)
//            ->orWhere('isLibrary', true)
//            ->orderBy('isLibrary', 'desc')
//            ->orderBy('category')
//            ->orderBy('subject')
//            ->get();
//
//        return view('clubs.maillist', ['mails' => $mails]);
//    }

    public function membershipForm(Request $request, $id)
    {
        $oldValues = $request->old();
        $club = Club::findOrFail($id);

        return view('clubs.membership', ['club_id' => $id, 'oldValues' => $oldValues, 'club' => $club]);
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

        $attributes['dob'] = date_create($request->dob);
        $attributes['firstname'] = $this->nameize($attributes['firstname']);
        $attributes['lastname'] = $this->nameize($attributes['lastname']);

        $attributes['accept'] = true;

        $attributes['social'] = implode(',', request('social'));

        //        info($attributes['membership_category']);

        if ($attributes['membership_category'] == 'competition') {
            $attributes['confirmed'] = false;
        } else {
            $attributes['confirmed'] = true;
        }

        $attributes['token'] = bin2hex(random_bytes(16));
        $attributes['amca_reg'] = $request->input('amca_reg');
        $attributes['acu_reg'] = $request->input('acu_reg');

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

    public function nameize($str, $a_char = ["'", '-', ' '])
    {
        // $str contains the complete raw name string
        // $a_char is an array containing the characters we use as separators for capitalization. If you don't pass anything, there are three in there as default.
        $string = strtolower($str);
        foreach ($a_char as $temp) {
            $pos = strpos($string, $temp);
            if ($pos) {
                // we are in the loop because we found one of the special characters in the array, so lets split it up into chunks and capitalize each one.
                $mend = '';
                $a_split = explode($temp, $string);
                foreach ($a_split as $temp2) {
                    // capitalize each portion of the string which was separated at a special character
                    $mend .= ucfirst($temp2) . $temp;
                }
                $string = substr($mend, 0, -1);
            }
        }

        return ucfirst($string);
    }

    public function console(Request $request)
    {
        $selectedTab = 'Profile';
        if (isset($request->tab)) {
            $selectedTab = $request->tab;
        }
        $user = Auth::user();
        $id = $user->club_id;
        $userID = $user->id;
        $club = DB::table('clubs')
            ->where('id', $id)
            ->first();

        $trials = Trial::all()
            ->where('created_by', $userID)
            ->sortByDesc('date');

        $distributionLists = DB::table('mail_distributions')
            ->where('club_id', $id)
            ->orderBy('name')
            ->get();

        $countItemsArray = [];
        foreach ($distributionLists as $distributionList) {
            $to = explode(',', $distributionList->to);
            array_push($countItemsArray, count($to));
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

        $toArray = array_unique(explode(',', $attributes['to']));
        sort($toArray, SORT_REGULAR);

        $attributes['to'] = implode(',', $toArray);

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

        $club = DB::table('clubs')
            ->where('id', $clubID)
            ->select('name', 'membership_categories', 'id')
            ->first();

        $categories = explode(',', $club->membership_categories);

        $membershipData = array();

        foreach ($categories as $category) {
            $riders = DB::table('club_members')
                ->where('club_id', $clubID)
                ->where('membership_category', $category)
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->get();

            $membershipData[$category] = $riders;
        }

        $allMembers = DB::table('club_members')
            ->where('club_id', $clubID)
            ->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')
            ->get();

        return view('clubs.members', ['allmembers' => $allMembers, 'membershipData' => $membershipData, 'club' => $club]);
    }

//    public function memberListOld()
//    {
//        $clubID = Auth::user()->club_id;
//
//        $club = DB::table('clubs')
//            ->where('id', $clubID)
//            ->select('name', 'membership_categories')
//            ->first();
//
//        $allMembers = DB::table('club_members')
//            ->where('club_id', $clubID)
//            ->orderBy('firstname', 'asc')
//            ->orderBy('lastname', 'asc')
//            ->get();
//
//        $riders = DB::table('club_members')
//            ->where('club_id', $clubID)
//            ->where('membership_category', 'competition')
//            ->orderBy('firstname', 'asc')
//            ->orderBy('lastname', 'asc')
//            ->get();
//
//        $observers = DB::table('club_members')
//            ->where('club_id', $clubID)
//            ->where('membership_category', 'observer')
//            ->orderBy('firstname', 'asc')
//            ->orderBy('lastname', 'asc')
//            ->get();
//
//        $lifers = DB::table('club_members')
//            ->where('club_id', $clubID)
//            ->where('membership_category', 'life')
//            ->orderBy('firstname', 'asc')
//            ->orderBy('lastname', 'asc')
//            ->get();
//
//        return view('clubs.members', ['allmembers' => $allMembers, 'riders' => $riders, 'observers' => $observers, 'lifers' => $lifers, 'club' => $club]);
//    }

    public function memberDetail()
    {
        $clubID = Auth::user()->club_id;
        $memberID = request('id');

        $member = DB::table('club_members')
            ->where('id', $memberID)
            ->where('club_id', $clubID)
            ->first();

        if (is_null($member)) {
            return redirect('/club/member/list');
        }
        return view('clubs.memberDetail', ['member' => $member]);
    }

    public function membershipConfirm($id)
    {
        $club_member = ClubMember::findOrFail($id);
        $club_member->confirmed = 1;
        $club_member->save();

        $clubID = $club_member->club_id;
        $club = DB::table('clubs')->where('id', $clubID)->first();

        $bcc = 'monster@trialmonster.uk';

        if ($club_member->membership_type == 'new') {
            info("Send New member email to $club_member->email");

            Mail::to($club_member->email)
                ->bcc($bcc)
                ->send(new WelcomeNewMember($club_member, $club));

        } else {
            info("Send Renewal email to $club_member->email");
            Mail::to($club_member->email)
                ->bcc($bcc)
                ->send(new RenewalAcknowledgement($club_member, $club));
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
                ->where('id', $clubID)
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
            $club = DB::table('clubs')->where('id', $clubID)->first();

            $memberIDs = request('approved');
            if ($memberIDs != null) {
                foreach ($memberIDs as $memberID) {
                    $club_member = ClubMember::findOrFail($memberID);
                    $memberClubID = $club_member->club_id;

                    if ($memberClubID == $clubID) {

                        $club_member->confirmed = true;
                        $club_member->save();

                        $bcc = 'monster@trialmonster.uk';

                        if ($club_member->membership_type == 'new') {
                            info("Send welcome email to $club_member->email");

                            Mail::to($club_member->email)
                                ->bcc($bcc)
                                ->send(new WelcomeNewMember($club_member, $club));

                        } else {
                            info("Send acknowledgement email to $club_member->email");
                            Mail::to($club_member->email)
                                ->bcc($bcc)
                                ->send(new RenewalAcknowledgement($club_member, $club));
                        }

                        info("Approved: $club_member->id");
                    } else {
                        info("Not approved: $club_member->id");
                    }
                }
            }
            return redirect('/club/member/approve');
        } else {
            return redirect('/');
        }
    }

    public function addManual(Request $request)
    {
        $user = Auth::user();
        $clubID = Auth::user()->club_id;
        $club = DB::table('clubs')->where('id', $clubID)->first();

        $attributes = $request->validate([
            'firstname' => ['required', 'min:2', 'max:255'],
            'lastname' => ['required', 'min:2', 'max:255'],
            'club_id' => 'required',
            'email' => ['required', 'email'],
            'phone' => 'required',
            'membership_type' => 'required',
            'membership_category' => 'required',
        ]);

        $attributes['firstname'] = $this->nameize($attributes['firstname']);
        $attributes['lastname'] = $this->nameize($attributes['lastname']);
        $attributes['amca_reg'] = $request->input('amca_reg');
        $attributes['acu_reg'] = $request->input('acu_reg');
        $attributes['dob'] = date_create($request->dob);
        $attributes['token'] = bin2hex(random_bytes(16));

        $attributes['accept'] = true;

        if (is_null($request->social)) {
            $attributes['social'] = 'TBA';
        } else {
            $attributes['social'] = implode(',', request('social'));
        }

        if (is_null($request->address)) {
            $attributes['address'] = 'TBA';
        } else {
            $attributes['address'] = request('address');
        }

        if (is_null($request->postcode)) {
            $attributes['postcode'] = 'TBA';
        } else {
            $attributes['postcode'] = request('postcode');
        }
        if (is_null($request->emergency_contact)) {
            $attributes['emergency_contact'] = 'TBA';
        } else {
            $attributes['emergency_contact'] = request('emergency_contact');
        }

        if (is_null($request->emergency_number)) {
            $attributes['emergency_number'] = 'TBA';
        } else {
            $attributes['emergency_number'] = request('emergency_number');
        }

        if ($attributes['membership_category'] == 'life' || $attributes['membership_category'] == 'observer') {
            $attributes['confirmed'] = true;
        } elseif ($attributes['membership_category'] == 'associate') {
            $attributes['confirmed'] = false;

        } else {
            $attributes['membership_category'] = 'competition';
            $attributes['confirmed'] = false;
        }

        if (!is_null($request->confirmed)) {
            $attributes['confirmed'] = true;

        }

        $member = ClubMember::create($attributes);

        if ($member->confirmed) {
            if ($member->membership_type == 'new') {
                info("Send welcome email to $member->email");

                Mail::to($member->email)
                    ->send(new WelcomeNewMember($member, $club));

            } else {
                info("Send acknowledgement email to $member->email");
                Mail::to($member->email)
                    ->send(new RenewalAcknowledgement($member, $club));
            }
        }

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

        return redirect('/club/member/list');

    }

    public function membershipEdit(string $id)
    {
        $member = ClubMember::find($id);
        $club = Club::find($member->club_id);

        return view('clubs.membership.edit', ['member' => $member, 'club' => $club]);
    }

    public function memberUpdate(Request $request)
    {
        $id = request('id');
        info("Updating memberID: $id");
        $member = ClubMember::find($id);
        $user = Auth::user();
        $clubID = Auth::user()->club_id;
        $club = DB::table('clubs')->where('id', $clubID)->first();

        $attributes = $request->validate([
            'firstname' => ['required', 'min:2', 'max:255'],
            'lastname' => ['required', 'min:2', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => 'required',
            'membership_type' => 'required',
            'membership_category' => 'required',
        ]);

        $attributes['firstname'] = $this->nameize($attributes['firstname']);
        $attributes['lastname'] = $this->nameize($attributes['lastname']);
        $attributes['amca_reg'] = $request->input('amca_reg');
        $attributes['acu_reg'] = $request->input('acu_reg');
        if (!is_null($member->dob)) {
            $attributes['dob'] = date_create($request->dob);
        }

        if (is_null($request->social)) {
            $attributes['social'] = 'TBA';
        } else {
            $attributes['social'] = implode(',', request('social'));
        }

        if (is_null($request->address)) {
            $attributes['address'] = 'TBA';
        } else {
            $attributes['address'] = request('address');
        }

        if (is_null($request->postcode)) {
            $attributes['postcode'] = 'TBA';
        } else {
            $attributes['postcode'] = request('postcode');
        }
        if (is_null($request->emergency_contact)) {
            $attributes['emergency_contact'] = 'TBA';
        } else {
            $attributes['emergency_contact'] = request('emergency_contact');
        }

        if (is_null($request->emergency_number)) {
            $attributes['emergency_number'] = 'TBA';
        } else {
            $attributes['emergency_number'] = request('emergency_number');
        }

//        if ($attributes['membership_category'] == 'life' || $attributes['membership_category'] == 'observer') {
//            $attributes['confirmed'] = true;
//        } elseif ($attributes['membership_category'] == 'associate') {
//            $attributes['confirmed'] = false;
//
//        } else {
//            $attributes['membership_category'] = 'competition';
//            $attributes['confirmed'] = false;
//        }
//
        if (!is_null($request->confirmed)) {
            $attributes['confirmed'] = true;
        } else {
            $attributes['confirmed'] = false;
        }


        $member->updated_at = now();
        $member->update($attributes);
        return redirect('/club/member/list');
    }

    public function userEdit(Request $request)
    {
        $token = $request->token;
        $id = $request->id;

        $member = ClubMember::where('token', $token)
            ->where('id', $id)
            ->first();

        if (is_null($member)) {
            $ip = $request->ip();
            info("Club member $id not found - IP: $ip");
            abort(403);
        }

        $club = Club::find($member->club_id);

        return view('clubs.user.edit', ['token' => $token, 'id' => $id, 'club' => $club, 'member' => $member]);
    }

    public function userUpdate(Request $request)
    {
        $id = request('id');
        $member = ClubMember::find($id);
        $user = Auth::user();
        $clubID = Auth::user()->club_id;
        $club = DB::table('clubs')->where('id', $clubID)->first();

        $attributes = $request->validate([
            'firstname' => ['required', 'min:2', 'max:255'],
            'lastname' => ['required', 'min:2', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => 'required',
            'membership_type' => 'required',
            'membership_category' => 'required',
        ]);

        $attributes['firstname'] = $this->nameize($attributes['firstname']);
        $attributes['lastname'] = $this->nameize($attributes['lastname']);
        $attributes['amca_reg'] = $request->input('amca_reg');
        $attributes['acu_reg'] = $request->input('acu_reg');
        $attributes['dob'] = date_create($request->dob);

        if (is_null($request->social)) {
            $attributes['social'] = 'TBA';
        } else {
            $attributes['social'] = implode(',', request('social'));
        }

        if (is_null($request->address)) {
            $attributes['address'] = 'TBA';
        } else {
            $attributes['address'] = request('address');
        }

        if (is_null($request->postcode)) {
            $attributes['postcode'] = 'TBA';
        } else {
            $attributes['postcode'] = request('postcode');
        }
        if (is_null($request->emergency_contact)) {
            $attributes['emergency_contact'] = 'TBA';
        } else {
            $attributes['emergency_contact'] = request('emergency_contact');
        }

        if (is_null($request->emergency_number)) {
            $attributes['emergency_number'] = 'TBA';
        } else {
            $attributes['emergency_number'] = request('emergency_number');
        }

        $member->updated_at = now();
        $member->update($attributes);

        info("Updating memberID: $id");
        return redirect('/');
    }


    public function export_()
    {

        Storage::disk('local')->put('example.txt', 'Contents');
        return Storage::download('example.txt');
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
//
//        $members = ClubMember::where('club_id', Auth::user()->club_id)
//            ->orderBy('lastname')
//            ->orderBy('firstname')
//            ->select(['firstname', 'lastname', 'email', 'phone', 'membership_type', 'membership_category', 'dob', 'address', 'postcode', 'emergency_contact', 'emergency_number', 'amca_reg', 'acu_reg', 'heritage_number', 'social'])
//            ->get()
//            ->toArray();
//
//        $spreadsheet->getActiveSheet()->setCellValue([1, 1], 'heritage_number');
//        $spreadsheet->getActiveSheet()->setCellValue([2, 1], 'firstname');
//        $spreadsheet->getActiveSheet()->setCellValue([3, 1], 'lastname');
//        $spreadsheet->getActiveSheet()->setCellValue([4, 1], 'email');
//        $spreadsheet->getActiveSheet()->setCellValue([5, 1], 'phone');
//        $spreadsheet->getActiveSheet()->setCellValue([7, 1], 'membership_type');
//        $spreadsheet->getActiveSheet()->setCellValue([8, 1], 'membership_category');
//        $spreadsheet->getActiveSheet()->setCellValue([9, 1], 'dob');
//        $spreadsheet->getActiveSheet()->setCellValue([10, 1], 'address');
//        $spreadsheet->getActiveSheet()->setCellValue([11, 1], 'postcode');
//        $spreadsheet->getActiveSheet()->setCellValue([12, 1], 'emergency_contact');
//        $spreadsheet->getActiveSheet()->setCellValue([13, 1], 'emergency_number');
//        $spreadsheet->getActiveSheet()->setCellValue([14, 1], 'amca_reg');
//        $spreadsheet->getActiveSheet()->setCellValue([15, 1], 'acu_reg');
//        $spreadsheet->getActiveSheet()->setCellValue([16, 1], 'social');
//
//
//        for ($i = 0; $i < count($members); $i++) {
//            $spreadsheet->getActiveSheet()->setCellValue([1, $i + 2], $members[$i]['heritage_number']);
//            $spreadsheet->getActiveSheet()->setCellValue([2, $i + 2], $members[$i]['firstname']);
//            $spreadsheet->getActiveSheet()->setCellValue([3, $i + 2], $members[$i]['lastname']);
//            $spreadsheet->getActiveSheet()->setCellValue([4, $i + 2], $members[$i]['email']);
//            $spreadsheet->getActiveSheet()->setCellValue([5, $i + 2], $members[$i]['phone']);
//            $spreadsheet->getActiveSheet()->setCellValue([7, $i + 2], $members[$i]['membership_type']);
//            $spreadsheet->getActiveSheet()->setCellValue([8, $i + 2], $members[$i]['membership_category']);
//            $spreadsheet->getActiveSheet()->setCellValue([9, $i + 2], $members[$i]['dob']);
//            $spreadsheet->getActiveSheet()->setCellValue([10, $i + 2], $members[$i]['address']);
//            $spreadsheet->getActiveSheet()->setCellValue([11, $i + 2], $members[$i]['postcode']);
//            $spreadsheet->getActiveSheet()->setCellValue([12, $i + 2], $members[$i]['emergency_contact']);
//            $spreadsheet->getActiveSheet()->setCellValue([13, $i + 2], $members[$i]['emergency_number']);
//            $spreadsheet->getActiveSheet()->setCellValue([14, $i + 2], $members[$i]['amca_reg']);
//            $spreadsheet->getActiveSheet()->setCellValue([15, $i + 2], $members[$i]['acu_reg']);
//            $spreadsheet->getActiveSheet()->setCellValue([16, $i + 2], $members[$i]['social']);
//        }
        $writer = new Xlsx($spreadsheet);
//
//        $writer->save('storage/app/private/new12345.xlsx');
////        echo asset('storage/files/new1.xlsx');
//        $url = Storage::url('files/new1.xlsx');
//
        Storage::disk('local')->put('example.txt', 'Contents');
//        echo $url;
//        return Storage::download('files/new.xlsx');
        return Storage::download('example.txt');
//        return Storage::download('new12345.xlsx'); // -> storage/private
    }
}
