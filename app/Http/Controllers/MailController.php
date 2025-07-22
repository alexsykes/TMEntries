<?php

namespace App\Http\Controllers;

//use App\Mail\TMLogin;
use App\Models\Trial;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mail;

class MailController extends Controller
{
    //

    public function edit($id){
        $mail = Mail::findOrFail($id);
        return view('mail.edit', compact('mail'));
    }
    public function preview($id){
        $mail = Mail::findOrFail($id);
//        dd($mail);
        return view('mail.preview', ['mail' => $mail]);
    }
    public function add(){
        return view('mail.add');
    }

    public function store(Request $request){

        $attributes = $request->validate([
            'category' => 'required',
            'subject' => ['required', 'min:5', 'max:255'],
            'bodyText' => 'required',
            'summary' => ['required', 'min:5', 'max:255'],
        ]);

        $attributes['isLibrary'] = true;
        $attributes['created_by'] = Auth::user()->id;


        $mail = Mail::create($attributes);

        return redirect('/admin/mails');
    }


    public function composeUserEmail($id) {
//        Check for ownership
        $user = auth()->user();
        if($user->isClubUser != 1) {
            abort(403);
        }
        $clubID = $user->club_id;
        $trial = Trial::findorfail($id);

        return view('user.email', ['trial' => $trial]);
    }

    public function storeUsermail(Request $request){
        $user = Auth::user();
        $clubID = $user->club_id;

        $attributes = $request->validate([
            'category' => 'required',
            'subject' => ['required', 'min:5', 'max:63'],
            'bodyText' => 'required',
            'summary' => ['required', 'min:5', 'max:255'],
        ]);

        $attributes['isLibrary'] = false;
        $attributes['club_id'] = $clubID;
        $attributes['created_by'] = Auth::user()->id;

        $mail = Mail::create($attributes);

        return redirect('/usermail/address_mail/' . $mail->id);
    }

    public function addressUsermail($id){
        $user = Auth::user();

    return view('user.address_mail', compact('user'));
    }

    public function storeAddressList(Request $request){


    }
    public function previewUsermail(Request $request){
        $user = Auth::user();



    }

    public function update(Request $request){
        $attributes = $request->validate([
            'id' => 'required',
            'category' => 'required',
            'subject' => ['required', 'min:5', 'max:255'],
            'bodyText' => 'required',
            'summary' => ['required', 'min:5', 'max:255'],
        ]);


        $mail = DB::table('mails')->where('id', $request->id)
        ->update(['updated_at' => now(),
            'category' => $request->category,
            'subject' => $request->subject,
            'bodyText' => $request->bodyText,
            'summary' => $request->summary,
            ]);

        return redirect('/admin/mails');
    }

    public function sendTestmail(Request $request){
//        dd($request->all());

        $user = Auth::user();
        $success = Mail::to('alex@alexsykes.net')
            ->send(new TMLogin($user));

        info("Email sent to {$user->email}");

        return redirect('/admin/mails');
    }
}
