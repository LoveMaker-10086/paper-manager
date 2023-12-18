<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        
        $existingUser = DB::table('users_info')->where('username', $username)->first();

        if ($existingUser) {
            return redirect('login')->withCookie(cookie('message', 'User already exists', 60));
        }

        DB::insert('insert into users_info (username, password, type) values (?, ?, ?)', [$username, $password, 'user']);

        return redirect('login')->withCookie(cookie('message', 'User registered successfully', 60));
    }
}
