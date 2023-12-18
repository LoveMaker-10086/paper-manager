<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = DB::table('users_info')->where('username', $username)->where('password', $password)->first();

        if ($user) {
            return redirect('paper_deadlines')->withCookie(cookie('message', 'Login successful', 60))->withCookie(cookie('username', $user->username, 60));
        } else {
            return redirect('login')->withCookie(cookie('message', 'Login failed', 60))->withCookie(cookie('username', null));
        }
    }
}

