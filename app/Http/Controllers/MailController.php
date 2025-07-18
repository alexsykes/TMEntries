<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function storeUsermail(Request $request){

        $attributes = $request->validate([
            'category' => 'required',
            'subject' => ['required', 'min:5', 'max:255'],
            'bodyText' => 'required',
            'summary' => ['required', 'min:5', 'max:255'],
        ]);

        $attributes['isLibrary'] = false;
        $attributes['created_by'] = Auth::user()->id;

        $mail = Mail::create($attributes);

        return redirect('/clubaccess');
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
}
