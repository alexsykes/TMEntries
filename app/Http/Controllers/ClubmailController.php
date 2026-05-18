<?php

namespace App\Http\Controllers;

// use App\Mail\TMLogin;
use App\Mail\TestMail;
use App\Models\Club;
use App\Models\Clubmail;
use App\Models\MailDistribution;
use App\Models\Mailshot;
use Auth;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ClubmailController extends Controller
{
    //

    public function edit($id)
    {
        //        $mail = Mail::findOrFail($id);
        $mail = DB::table('clubmails')
            ->where('id', $id)
            ->first();

        return view('mail.edit', compact('mail'));
    }

    public function preview($id)
    {
        //        $mail = Mail::findOrFail($id);
        $mail = DB::table('clubmails')
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

        //        $mail = Clubmail::create($attributes);

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
        $mail = DB::table('clubmails')
            ->where('id', $id)
            ->first();

        return view('user.edit_mail', ['mail' => $mail]);
    }

    public function storeUsermail(Request $request)
    {
        $user = Auth::user();
        $clubID = $user->club_id;

        $attributes = $request->validate([
            'category' => 'required',
            'subject' => ['required', 'min:3', 'max:63'],
            'bodyText' => 'required',
            'summary' => ['required', 'min:3', 'max:255'],
        ]);

        //        File handler
        if ($request->attachment) {
            $names = [];
            $types = [];
            $fileNames = [];
            foreach ($request->attachment as $attachment) {

                $originalName = $attachment->getClientOriginalName();
                $mimeType = $attachment->getClientMimeType();
                $extension = $attachment->getClientOriginalExtension();

                //              Save file under unique id
                $fileName = uniqid() . '.' . $extension;

                $attachment->move(public_path('attachments'), $fileName);

                array_push($types, $mimeType);
                array_push($names, $originalName);
                array_push($fileNames, $fileName);
            }
            //  Add file data to attributes as CSV data
            $attributes['originalName'] = implode('|', $names);
            $attributes['mimeType'] = implode('|', $types);
            $attributes['fileName'] = implode('|', $fileNames);
        }

        $attributes['trial_id'] = $request->trial_id;
        $attributes['isLibrary'] = false;
        $attributes['club_id'] = $clubID;
        $attributes['created_by'] = Auth::user()->id;
        $attributes['created_at'] = date('Y-m-d H:i:s');
        $attributes['updated_at'] = date('Y-m-d H:i:s');

        $attributes['reply_to_address'] = $request->input('reply_to_address');
        $attributes['reply_to_name'] = $request->input('reply_to_name');

        $mail = Clubmail::create($attributes);

        return redirect('/club/mails/');
    }

    public function updateUserEmail(Request $request)
    {
        $action = $request->input('action');

        $originalNames = [];
        $fileNames = [];
        $mimeTypes = [];

        $attributes = $request->validate([
            'mail_id' => 'required',
            'category' => 'required',
            'subject' => ['required', 'min:3', 'max:63'],
            'bodyText' => 'required',
            'summary' => ['required', 'min:3', 'max:255'],
        ]);

        $mail = DB::table('clubmails')
            ->where('id', $request->mail_id)
            ->first();

        //        Get file data in case it doesn't change - may be empty
        $originalName = $mail->originalName;
        $mimeType = $mail->mimeType;
        $fileName = $mail->fileName;

        if ($originalName != '') {
            $originalNames = explode('|', $originalName);
            $fileNames = explode('|', $fileName);
            $mimeTypes = explode('|', $mimeType);
        }

        if ($action == 'update') {
            if ($request->fileToRemove) {

                $fileToRemove = $request->fileToRemove;
                for ($i = 0; $i < count($fileToRemove); $i++) {
                    unset($originalNames[$i]);
                    unset($fileNames[$i]);
                    unset($mimeTypes[$i]);
                }
            }
            if ($request->fileToAdd) {
                //                dd($request->fileToAdd);
                $attachment = $request->file('fileToAdd');

                $originalName = $attachment->getClientOriginalName();
                $mimeType = $attachment->getClientMimeType();

                $extension = $attachment->getClientOriginalExtension();
                $fileName = uniqid() . '.' . $extension;
                $attachment->move(public_path('attachments'), $fileName);

                array_push($originalNames, $originalName);
                array_push($fileNames, $fileName);
                array_push($mimeTypes, $mimeType);

            }
            $originalName = implode('|', $originalNames);
            $fileName = implode('|', $fileNames);
            $mimeType = implode('|', $mimeTypes);

            //            dump($originalName, $fileName, $mimeType);
            $mail = DB::table('clubmails')->where('id', $request->mail_id)
                ->update(['updated_at' => now(),
                    'category' => $request->category,
                    'subject' => $request->subject,
                    'bodyText' => $request->bodyText,
                    'summary' => $request->summary,
                    'originalName' => $originalName,
                    'mimeType' => $mimeType,
                    'fileName' => $fileName,

                    'reply_to_address' => $request->input('reply_to_address'),
                    'reply_to_name' => $request->input('reply_to_name'),
                ]);

        } elseif ($action == 'saveAsNew') {

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
        $mail = DB::table('clubmails')
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

        // Example timestamp
        $send_at = new DateTime;

        if (is_null($mailshot->send_at)) {
            $sendAt = new DateTime;
            $mailshot->sent_at = now();
            $mailshot->sent = true;
        } else {
            $sendAt = new Carbon($mailshot->send_at);
        }

        $addresses = explode(', ', $mailshot->distribution);
        $delay = 10;
//        for($i = 0; $i < 100; $i++) {
        $mailIDs = array();
        foreach ($addresses as $address) {
            $mail = Mail::to($address)->later($sendAt, new TestMail($mailshot));
            array_push($mailIDs, $mail);
        }
        $mail_ids = implode(',', $mailIDs);
        $mailshot->job_ids = $mail_ids;
        $mailshot->save();
        return redirect('/club/mails');
    }

    public function sendMail($id)
    {
        //        $mail = Mail::findOrFail($id);
        $mail = DB::table('clubmails')
            ->where('id', $id)
            ->first();

        $clubID = Auth::user()->club_id;
        $club = Club::findOrFail($clubID);
        $clubName = $club->name;
        $clubTrials = DB::table('trials')->where('club', $clubName)
            ->select(['name', 'id'])
            ->orderBy('date', 'desc')
            ->limit(25)
            ->get();

        $distributions = DB::table('mail_distributions')
            ->where('club_id', $clubID)
            ->orderBy('name')
            ->get();

        //        dd($distributions);
        return view('clubs.sendmail', compact('mail', 'clubTrials', 'distributions'));
    }

    //     Get mailing list of users
    public function prepare(Request $request)
    {
        $userID = Auth::user()->id;
        $clubID = Auth::user()->club_id;
        $mail_id = $request->mail_id;
        $distribution = $request->distribution;
        $distributionList = [];
        $sendAt = $request->send_at;

        $mail = DB::table('clubmails')->where('id', $mail_id)->first();

        $attributes = $request->validate([
            'mail_id' => 'required',
        ]);

        switch ($distribution) {
            case 'Test':
                array_push($distributionList, $request->testAddress);
                break;
            case 'Trial Entrants':
                $trialID = $request->trial_id;
                //              Get email addresses from Stripe payments
                $pastEntrants = DB::table('entries')
                    ->where('trial_id', $trialID)
                    ->whereNotNull('email')
                    ->select('email')
                    ->distinct()
                    ->get();

                //              Add email addresses to distribution list
                foreach ($pastEntrants as $pastEntrant) {
                    array_push($distributionList, strtolower($pastEntrant->email));
                }

                //              Also get entrant (logged-in user) email address
                $pastUsers = DB::table('entries')
                    ->select('users.email')
                    ->distinct()
                    ->join('users', 'entries.created_by', '=', 'users.id')
                    ->where('trial_id', $trialID)
                    ->where('users.receive_emails', true)
                    ->get();

                foreach ($pastUsers as $pastUser) {
                    array_push($distributionList, strtolower($pastUser->email));
                }

                $distributionList = array_unique($distributionList);
                break;

            case 'Past Entrants':
                $clubID = Auth::user()->club_id;
                $club = Club::findOrFail($clubID);
                $clubName = $club->name;
                $clubTrials = DB::table('trials')->where('club', $clubName)
                    ->where('id', '>', 50)
                    ->select('id')
                    ->get();

                $clubTrialIDs = [];

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
                    array_push($distributionList, strtolower($pastEntrant->email));
                }

                $pastUsers = DB::table('entries')
                    ->select('users.email')
                    ->distinct()
                    ->join('users', 'entries.created_by', '=', 'users.id')
                    ->whereIn('trial_id', $clubTrialIDs)
                    ->where('users.receive_emails', true)
                    ->get();

                foreach ($pastUsers as $pastUser) {
                    array_push($distributionList, strtolower($pastUser->email));
                }

                $distributionList = array_unique($distributionList);
                break;
            case 'All Users':
                $allUsers = DB::table('users')
                    ->select('email')
                    ->distinct()
                    ->where('users.receive_emails', true)
                    ->get();
                foreach ($allUsers as $user) {
                    array_push($distributionList, strtolower($user->email));
                }
                break;
            default:

            case 'Distribution List':
                $distribution_id = $request->distribution_id;
                $mail_distribution = MailDistribution::findOrFail($distribution_id);

                $distributionList = explode(',', $mail_distribution->to);

                break;

        }
        $distributionList = array_unique($distributionList);

        $attributes['distribution'] = implode(', ', $distributionList);
        $attributes['subject'] = $mail->subject;
        $attributes['bodyText'] = $mail->bodyText;
        $attributes['club_id'] = $mail->club_id;
        $attributes['mail_id'] = $mail->id;
        $attributes['sent_by'] = $userID;
        $attributes['send_at'] = $sendAt;

        $attributes['reply_to_address'] = $mail->reply_to_address;
        $attributes['reply_to_name'] = $mail->reply_to_name;

        $attributes['originalName'] = $mail->originalName;
        $attributes['mimeType'] = $mail->mimeType;
        $attributes['fileName'] = $mail->fileName;

        $mailshot = Mailshot::create($attributes);

        return view('clubs.prepare', compact('mailshot'));
    }

    public function unpublish($id)
    {
        DB::table('clubmails')
            ->where('id', $id)
            ->update(['published' => false]);

        return redirect('/club/mails');
    }

    public function cancelMailshot(Request $request)
    {
        $id = $request->id;
        $mailshot = Mailshot::findOrFail($id);
        $sendAt = date_create($mailshot->send_at);
        $ts = date_timestamp_get($sendAt);

        $jobIDs = $mailshot->job_ids;
        $jobIDArray = explode(',', $jobIDs);
        $deletedJobs = DB::table('jobs')
            ->whereIn('jobs.id', $jobIDArray)
            ->delete();

        $mailshot->delete();

        return redirect('/club/mails');
    }
}
