<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Entry;
use App\Models\Trial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function userList() {
        $users = User::orderBy('name', 'asc')
            ->paginate(50);

        return view('admin.userList', ['users' => $users]);

    }
}
