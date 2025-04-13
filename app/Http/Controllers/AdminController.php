<?php

namespace App\Http\Controllers;

use App\Mail\TMLogin;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function userList()
    {
        $users = User::orderBy('name', 'asc')
            ->paginate(50);

        return view('admin.userList', ['users' => $users]);

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
}
