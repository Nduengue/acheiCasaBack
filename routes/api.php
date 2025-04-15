<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OpenChatController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


 /**
 * Auth
 * @see \App\Http\Controllers\AuthController
 * @see \App\Http\Controllers\AuthController::auth()
 * @see \App\Http\Controllers\AuthController::register()
 * @see \App\Http\Controllers\AuthController::recurve()
 * @see \App\Http\Controllers\AuthController::reset()
 * @see \App\Http\Controllers\AuthController::code()
 * @see \App\Http\Controllers\AuthController::logout()
 */

 Route::post('auth', [AuthController::class, "auth"])->name("auth");
 Route::post('register', [AuthController::class, "register"]);
 Route::post('recurve', [AuthController::class, "recurve"])->name("recurve");
 Route::post('reset', [AuthController::class, "reset"])->name("reset");
 Route::post('code', [AuthController::class, "code"])->name("code");
 Route::post('logout', [AuthController::class, "logout"])->name("logout");
 
 Route::get("login", function (Request $request) {
    return response()->json([
        "success" => false,
        "message" => "You need to be logged in to register interest",
    ], 401);
})->name("login");
 
  /**
  * * Auth * Facebook *
  * @see \App\Http\Controllers\AuthController
  * @see \App\Http\Controllers\AuthController::facebookRedirect()
  * @see \App\Http\Controllers\AuthController::facebookCallback()
  */
 Route::get('/auth/facebook/redirect',[AuthController::class,"facebookRedirect"])->name("facebook.redirect");
 Route::get('/auth/facebook/callback',[AuthController::class,"facebookCallback"])->name("facebook.callback");
 
 /**
 * * Auth * Google *
 * @see \App\Http\Controllers\AuthController
 * @see \App\Http\Controllers\AuthController::googleRedirect()
 * @see \App\Http\Controllers\AuthController::googleCallback()
 */
 Route::get('/auth/google/redirect', [AuthController::class,"googleRedirect"])->name("google.redirect");
 Route::get('/auth/google/callback',[AuthController::class,"googleCallback"])->name("google.callback");
 


// Routa Auth
Route::middleware('auth:sanctum')->group(function () {
    /**
     * Auth
     * @see \App\Http\Controllers\AuthController
     * @see \App\Http\Controllers\AuthController::me()
     * @see \App\Http\Controllers\AuthController::profile()
     */
    Route::get('me', [AuthController::class, "me"])->name("me");
    Route::put('profile', [AuthController::class, "profile"])->name("profile");
    Route::put('address', [AuthController::class, "address"])->name("profile");
    Route::post('upload', [AuthController::class, "upload"])->name("profile");
    
    /**
     * Property
     * @see \App\Http\Controllers\PropertyController
     */
    Route::apiResource('property',PropertyController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    
    
    /**
     * Property
     * @see \App\Http\Controllers\PropertyController
     * @see \App\Http\Controllers\PropertyController::nearby()
     */
    Route::get('nearby', [PropertyController::class, 'nearby'])->name('property.nearby');
    Route::resource('agency',AgencyController::class)->only(['index', 'store', 'show', 'update', 'destroy']);


    /**
     * Agency
     * @see \App\Http\Controllers\AgencyController
     * @see \App\Http\Controllers\AgencyController::searchUser()
     * @see \App\Http\Controllers\AgencyController::addUserToAgency()
     * @see \App\Http\Controllers\AgencyController::listUserToAgency()
     */
    Route::get('search-user', [AgencyController::class, 'searchUser'])->name('agency.search');
    Route::post('addUserToAgency/{agency}', [AgencyController::class, 'addUserToAgency'])->name('agency.addUserToAgency');
    Route::get('listUserToAgency', [AgencyController::class, 'listUserToAgency'])->name('agency.listUserToAgency');
    
    
    /**
     * Notification
     * @see \App\Http\Controllers\NotificationController
     * @see \App\Http\Controllers\NotificationController::index()
     * @see \App\Http\Controllers\NotificationController::marcarComoLida()
     * @see \App\Http\Controllers\NotificationController::store()
     */
    Route::get('notification',[NotificationController::class,'index'])->name('notification.index');
    Route::get('notification/{id}',[NotificationController::class,'marcarComoLida'])->name('notification.lida');
    
    Route::post('interest/{property}', [OpenChatController::class, 'interest'])->name('openChat.interest');
    Route::get('message', [OpenChatController::class, 'index'])->name('openChat.index');
    Route::get('message/{chat}', [OpenChatController::class, 'show'])->name('openChat.show');
    Route::post('message/{chat}', [OpenChatController::class, 'store'])->name('message.store');

});

 /**
  * Route * Public *
  * @see \App\Http\Controllers\PropertyController
  * @see \App\Http\Controllers\PropertyController::all()
  * @see \App\Http\Controllers\PropertyController::base()
  */
 Route::get('base', [PropertyController::class, 'base'])->name('property.all');
 Route::get('all', [PropertyController::class, 'all'])->name('property.all');
 Route::match(['get', 'post'], '/webhook', [WebhookController::class, 'handle']);