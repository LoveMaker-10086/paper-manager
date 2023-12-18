<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Request $request)
    {
        $username = $request->input('username');
        $conferenceId = $request->input('conferenceId');
        $conference = DB::table('paper_deadlines')->where('id', $conferenceId)->first();
        
        DB::insert('insert into archive (id, username, name, type, CCF, date, submission_deadline, countdown, place, website) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$conferenceId, $username, $conference->name, $conference->type, $conference->CCF, $conference->date, $conference->submission_deadline, $conference->countdown, $conference->place, $conference->website]);
    }
}
