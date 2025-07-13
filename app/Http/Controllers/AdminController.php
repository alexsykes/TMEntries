<?php

namespace App\Http\Controllers;

use App\Mail\TMLogin;
use App\Models\Trial;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function userList()
    {
        $users = User::orderBy('name', 'asc')
            ->paginate(50);

        return view('admin.userList', ['users' => $users]);

    }

    public function trialList()
    {
        $trials = DB::table('trials')
            ->orderBy('date', 'desc')
        ->get();
        return view('admin.trialList', ['trials' => $trials]);
    }

    public function resultList()
    {
        $results = DB::table('trials')
            ->where('isResultPublished', 1)
            ->orderBy('date', 'desc')
        ->get();
        return view('admin.resultList', ['results' => $results]);
    }

    public function mailList(){
        $mails = DB::table('mails')
            ->orderBy('subject')
            ->get();
        return view('admin.mailList', ['mails' => $mails]);
    }

    public function sendMail()
    {
        $delay = 1;

        $users = User::get();


        foreach ($users as $user) {
            Mail::to($user->email)
                ->later(now()->addSeconds($delay++), new TMLogin($user));
            $delay++;
            info("sendMail - delay: $delay");
        }
        return redirect('/adminaccess');
    }


    public function closeMyAccount()
    {
        $email = request('email');
        $id = request('id');
        $user = User::where('email', $email)
            ->where('id', $id)
            ->first();
        if ($user) {
            $user->delete();
        } else {
            abort(404);
        }
        return redirect('/');
    }

    public function adminRemove()
    {
//        dd(request('id'));
        $id = request('id');
        $user = User::find($id)
            ->where('id', $id)
            ->where('isSuperUser', '!=', 1)
            ->delete();
        return redirect('/adminaccess');
    }

    public function editUser()
    {
        $user = User::find(request('id'));
        return view('admin.adminUserEdit', ['user' => $user]);
    }

    public function updateUser()
    {
        $user = User::find(request('id'));
        $user->name = request('name');
        $user->email = request('email');
        $user->save();
        return redirect('/adminaccess');
    }

    public function toggleResultPublished() {
        $trial = Trial::find(request('id'));
        $trial->isResultPublished = !$trial->isResultPublished;
        $trial->save();
        return redirect('/admin/trials');;
    }
    public function toggleEntry() {
        $trial = Trial::find(request('id'));
        $trial->isEntryLocked = !$trial->isEntryLocked;
        $trial->save();
        return redirect('/admin/trials');;
    }
    public function toggleScoring() {
        $trial = Trial::find(request('id'));
        $trial->isScoringLocked = !$trial->isScoringLocked;
        $trial->save();
        return redirect('/admin/trials');;
    }
    public function toggleLock() {
        $trial = Trial::find(request('id'));
        $trial->isLocked = !$trial->isLocked;
        $trial->save();
        return redirect('/admin/trials');;
    }

    public function about(){
        return view('about.story');
    }
}
