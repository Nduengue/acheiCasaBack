<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth
Route::post('auth', [AuthController::class, "auth"])->name("auth");
Route::post('code', [AuthController::class, "code"])->name("code");
Route::post('register', [AuthController::class, "register"]);

// Reset Password
Route::post('recurve', [AuthController::class, "recurve"])->name("recurve");
Route::post('reset', [AuthController::class, "reset"])->name("reset");
// Facebook
Route::get('/auth/facebook/redirect',[AuthController::class,"facebookRedirect"])->name("facebook.redirect");
Route::get('/auth/facebook/callback',[AuthController::class,"facebookCallback"])->name("facebook.callback");
// Google
Route::get('/auth/google/redirect', [AuthController::class,"googleRedirect"])->name("google.redirect");
Route::get('/auth/google/callback',[AuthController::class,"googleCallback"])->name("google.callback");
// Routa Auth
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, "logout"])->name("logout");
    Route::get('me', [AuthController::class, "me"])->name("me");
    Route::post('profile', [AuthController::class, "profile"])->name("profile");
    Route::post("password", [AuthController::class, "password"])->name("password");
});