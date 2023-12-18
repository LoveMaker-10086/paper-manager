<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\DislikeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/paper_deadlines', function () {
    $conferences = DB::table('paper_deadlines')->get();
    $message = request()->cookie('message');
    $username = request()->cookie('username');

    $archived = DB::table('archive')->where('username', $username)->get();
    $archivedId = [];
    foreach ($archived as $item) {
        $id = $item->id;
        $archivedId[] = $id;
    }

    return view('paper_deadlines', ['conferences' => $conferences, 'message' => $message, 'username' => $username, 'archivedId' => $archivedId]);
});

Route::get('/login', function () {
    $message = request()->cookie('message');

    return view('login', ['message' => $message]);
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::controller(RegistrationController::class)->group(function () {
    Route::post('register', 'register'); 
});

Route::controller(LoginController::class)->group(function () {
    Route::post('login', 'login');
});

Route::controller(LogoutController::class)->group(function () {
    Route::post('logout', 'logout');
});

Route::get('/archive', function () {
    $username = request()->cookie('username');
    $conferences = DB::table('archive')->where('username', $username)->get();
    
    $archived = DB::table('archive')->where('username', $username)->get();
    $archivedId = [];
    foreach ($archived as $item) {
        $id = $item->id;
        $archivedId[] = $id;
    }

    return view('archive', ['conferences' => $conferences, 'username' => $username, 'archivedId' => $archivedId]);
})->name('archive');

Route::controller(LikeController::class)->group(function () {
    Route::post('like', 'like');
});

Route::controller(DislikeController::class)->group(function () {
    Route::post('dislike', 'dislike');
});