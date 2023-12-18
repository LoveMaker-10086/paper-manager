<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        return redirect('paper_deadlines')->withCookie(cookie('message', 'Logout successful', 60))->withCookie(cookie('username', null))->withCookie(cookie('archivedId', null));
    }
}
