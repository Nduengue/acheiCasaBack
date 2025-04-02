<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
})->name("home");


Route::get('/auth/google/redirect', function () {
    return Socialite::driver("google")->redirect();
});

Route::get('/auth/google/callback', function (Request $request) {
    $googleUser = Socialite::driver("google")->user();

    $user = User::updateOrCreate(
        ["google_id"=>$googleUser->id],
        [
            "google_id"=>$googleUser->id,
            "name"=>$googleUser->name,
            "email"=>$googleUser->email,
            "password"=>Hash::make(Str::random(12))
        ]
    );
    // Criar token de acesso
    /* $token = $user->createToken()->plainTextToken; */

    return response()->json([
        'user' => $user,
       /*  'token' => $token, */
    ]);
});