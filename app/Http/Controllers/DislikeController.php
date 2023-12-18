<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DislikeController extends Controller
{
    public function dislike(Request $request)
    {
        $username = $request->input('username');
        $conferenceId = $request->input('conferenceId');

        DB::table('archive')->where('username', $username)->where('id', $conferenceId)->delete();
    }
}
