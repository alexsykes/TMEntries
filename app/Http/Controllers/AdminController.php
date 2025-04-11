<?php

namespace App\Http\Controllers;

use App\Mail\TMLogin;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function userList() {
        $users = User::orderBy('name', 'asc')
            ->paginate(50);

        return view('admin.userList', ['users' => $users]);

    }

    public function sendMail() {
        info('sendMail');
        $user = User::find(Auth::id());
        Mail::to($user->email)->send(new TMLogin($user));
    }

    public function closeMyAccount() {
        $email = request('email');
        $id = request('id');
        $user = User::where('email', $email)
            ->where('id', $id)
            ->first();
        if($user) {
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
}
