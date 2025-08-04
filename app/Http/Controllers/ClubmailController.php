<?php

namespace App\Http\Controllers;

//use App\Mail\TMLogin;
use App\Mail\TestMail;
use App\Models\Club;
use App\Models\Clubmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Mailshot;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClubmailController extends Controller
{
    //

    public function edit($id)
    {
//        $mail = Mail::findOrFail($id);
        $mail= DB::table('clubmails')
            ->where('id', $id)
            ->first();
        return view('mail.edit', compact('mail'));
    }

    public function preview($id)
    {
//        $mail = Mail::findOrFail($id);
        $mail= DB::table('clubmails')
            ->where('id', $id)
            ->first();
//        dd($mail);
        return view('mail.preview', ['mail' => $mail]);
    }

    public function add()
    {
        return view('clubmails.add');
    }

    public function store(Request $request)
    {

        $attributes = $request->validate([
            'category' => 'required',
            'subject' => ['required', 'min:5', 'max:255'],
            'bodyText' => 'required',
            'summary' => ['required', 'min:5', 'max:255'],
        ]);

        $attributes['isLibrary'] = true;
        $attributes['created_by'] = Auth::user()->id;


        $mail = Clubmail::create($attributes);

        return redirect('/admin/mails');
    }


    public function composeUserEmail()
    {
//        Check for ownership
        $user = auth()->user();
        if ($user->isClubUser != 1) {
            abort(403);
        }
        $clubID = $user->club_id;
        return view('user.email');
    }

    public function editUserEmail($id)
    {
//        $mail = Mail::findOrFail($id);
        $mail= DB::table('clubmails')
            ->where('id', $id)
            ->first();
        return view('user.edit_mail', ['mail' => $mail]);
    }

    public function storeUsermail(Request $request)
    {
        $user = Auth::user();
        $clubID = $user->club_id;

//        dd($request->all());
        $attributes = $request->validate([
            'category' => 'required',
            'subject' => ['required', 'min:5', 'max:63'],
            'bodyText' => 'required',
            'summary' => ['required', 'min:5', 'max:255'],
        ]);

        $attributes['trial_id'] = $request->trial_id;
        $attributes['isLibrary'] = false;
        $attributes['club_id'] = $clubID;
        $attributes['created_by'] = Auth::user()->id;
        $attributes['created_at']  = date("Y-m-d H:i:s");
        $attributes['updated_at']  = date("Y-m-d H:i:s");

//        $mail = Mail::create($attributes);
        $mail = DB::table('clubmails')->insert(
            $attributes
        );
        return redirect('/club/mails/');
    }

    public function updateUserEmail(Request $request)
    {
        $action = $request->input('action');

//        dd($request->all());
        $attributes = $request->validate([
            'trial_id' => 'required',
            'category' => 'required',
            'subject' => ['required', 'min:5', 'max:63'],
            'bodyText' => 'required',
            'summary' => ['required', 'min:5', 'max:255'],
        ]);

        if ($action == 'update') {
            $mail = DB::table('clubmails')->where('id', $request->trial_id)
                ->update(['updated_at' => now(),
                    'category' => $request->category,
                    'subject' => $request->subject,
                    'bodyText' => $request->bodyText,
                    'summary' => $request->summary,
                ]);
        } elseif ($action == 'saveAsNew') {
            $attributes['trial_id'] = $request->trial_id;
            $attributes['isLibrary'] = false;
            $attributes['created_by'] = Auth::user()->id;
            $attributes['club_id'] = Auth::user()->club_id;

            $mail = Clubmail::create($attributes);
        }
        return redirect('/club/mails');
    }

    public function update(Request $request)
    {
        $attributes = $request->validate([
            'id' => 'required',
            'category' => 'required',
            'subject' => ['required', 'min:5', 'max:255'],
            'bodyText' => 'required',
            'summary' => ['required', 'min:5', 'max:255'],
        ]);


        $mail = DB::table('clubmails')->where('id', $request->id)
            ->update(['updated_at' => now(),
                'category' => $request->category,
                'subject' => $request->subject,
                'bodyText' => $request->bodyText,
                'summary' => $request->summary,
            ]);

        return redirect('/admin/mails');
    }

    public function addressUsermail($id)
    {
        $user = Auth::user();

        return view('user.address_mail', compact('user'));
    }

    public function storeAddressList(Request $request)
    {


    }

    public function previewUsermail($id)
    {
        $user = Auth::user();
//        $mail = Mail::findOrFail($id);
        $mail= DB::table('clubmails')
            ->where('id', $id)
            ->first();
        return view('mail.preview', compact('user', 'mail'));
    }

    public function sendTestmail(Request $request)
    {
//        dd($request->all());

        $user = Auth::user();
        $success = Mail::to('alex@alexsykes.net')
            ->send(new TMLogin($user));

        info("Email sent to {$user->email}");

        return redirect('/admin/mails');
    }

    public function send(Request $request)
    {
        $mailID = $request->input('mail_id');
        $mailshot = Mailshot::findOrFail($mailID);

        $subject = $mailshot->subject;
        $bodyText = $mailshot->bodyText;
        $mail = new TestMail();

        $addresses = explode(',',$mailshot->distribution);
        foreach ($addresses as $address) {
           $this->actualMail($address);
        }

        $mailshot->sent_at  = now();
        $mailshot->sent = true;
        $mailshot->save();
        return redirect('/club/mails');
    }

    public function actualMail($address){
        $mail = new TestMail();
        Mail::to($address)->send($mail);
    }

    public function sendMail($id)
    {
//        $mail = Mail::findOrFail($id);
        $mail= DB::table('clubmails')
            ->where('id', $id)
            ->first();

        $clubID = Auth::user()->club_id;
        $club = Club::findOrFail($clubID);
        $clubName = $club->name;
        $clubTrials = DB::table('trials')->where('club', $clubName)
            ->select(['name', 'id'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        return view('clubs.sendmail', compact('mail', 'clubTrials'));
    }

    public function prepare(Request $request)
    {
        $userID = Auth::user()->id;
        $clubID = Auth::user()->club_id;
        $mail_id = $request->mail_id;
        $distribution = $request->distribution;
        $distributionList = array();

        $mail = DB::table('clubmails')->where('id', $mail_id)->first();

        $attributes = $request->validate([
            'mail_id' => 'required',
        ]);

        switch ($distribution) {
            case "Test":
                array_push($distributionList, $request->testAddress);
                break;
            case "Trial Entrants":
                $trialID = $request->trial_id;
                $pastEntrants = DB::table('entries')
                    ->where('trial_id', $trialID)
                    ->whereNotNull('email')
                    ->select('email')
                    ->distinct()
                    ->get();

//              Get email addresses from user table
                foreach ($pastEntrants as $pastEntrant) {
                    array_push($distributionList, $pastEntrant->email);
                }

                $pastUsers = DB::table('entries')
                    ->select('users.email')
                    ->distinct()
                    ->join('users', 'entries.created_by', '=', 'users.id')
                    ->where('trial_id', $trialID)
                    ->get();

                foreach ($pastUsers as $pastUser) {
                    array_push($distributionList, $pastUser->email);
                }

                break;

            case "Past Entrants":
                $clubID = Auth::user()->club_id;
                $club = Club::findOrFail($clubID);
                $clubName = $club->name;
                $clubTrials = DB::table('trials')->where('club', $clubName)
                    ->select('id')
                    ->get();

                $clubTrialIDs = array();
                foreach ($clubTrials as $clubTrial) {
                    array_push($clubTrialIDs, $clubTrial->id);
                }
//  Get email addresses from Stripe users
                $pastEntrants = DB::table('entries')
                    ->whereIn('trial_id', $clubTrialIDs)
                    ->whereNotNull('email')
                    ->select('email')
                    ->distinct()
                    ->get();

//              Get email addresses from user table
                foreach ($pastEntrants as $pastEntrant) {
                    array_push($distributionList, $pastEntrant->email);
                }

                $pastUsers = DB::table('entries')
                    ->select('users.email')
                    ->distinct()
                    ->join('users', 'entries.created_by', '=', 'users.id')
                    ->whereIn('trial_id', $clubTrialIDs)
                    ->get();

                foreach ($pastUsers as $pastUser) {
                    array_push($distributionList, $pastUser->email);
                }
                break;
            case "All Users":
                $allUsers = DB::table('users')->select('email')->distinct()->get();
                foreach ($allUsers as $user) {
                    array_push($distributionList, $user->email);
                }
                break;
            default:

        }
        $distributionList = array_unique($distributionList);

        $attributes['distribution'] = implode(', ', $distributionList);
        $attributes['subject'] = $mail->subject;
        $attributes['bodyText'] = $mail->bodyText;
        $attributes['club_id'] = $mail->club_id;
        $attributes['mail_id'] = $mail->id;
        $attributes['sent_by'] = $userID;

        $mailshot = Mailshot::create($attributes);
        return view('clubs.prepare', compact('mailshot'));
    }
}
