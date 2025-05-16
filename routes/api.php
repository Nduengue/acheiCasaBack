<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OpenChatController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CheckPointController;
use App\Http\Controllers\PaymentReceiptController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
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
    Route::post('upload', [AuthController::class, "upload"])->name("upload");
    
    /**
     * Property
     * @see \App\Http\Controllers\PropertyController
     */
    Route::apiResource('property',PropertyController::class)->only(['index', 'store', 'show','destroy']);
    Route::post('property/{property}', [PropertyController::class, 'update'])->name('property.update');
    /** group of agency - property */
    Route::get('property-agency', [PropertyController::class, 'agency'])->name('property.agency');
    
    
    /**
     * Property
     * @see \App\Http\Controllers\PropertyController
     * @see \App\Http\Controllers\PropertyController::nearby()
     */
    Route::get('nearby', [PropertyController::class, 'nearby'])->name('property.nearby');
    Route::resource('agency',AgencyController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::post('agency/{agency}', [AgencyController::class, 'update'])->name('agency.update');

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
    /**
     * Message
     * @see \App\Http\Controllers\MessageController
     * @see \App\Http\Controllers\MessageController::index()
     * @see \App\Http\Controllers\MessageController::show()
     * @see \App\Http\Controllers\MessageController::store()
     * 
     */
    Route::get('message', [OpenChatController::class, 'index'])->name('openChat.index');
    Route::get('message/{chat}', [OpenChatController::class, 'show'])->name('openChat.show');
    Route::post('message/{chat}', [OpenChatController::class, 'store'])->name('message.store');
    /**
     * CheckPoint
     * @see \App\Http\Controllers\CheckPointController
     * @see \App\Http\Controllers\CheckPointController::index() 
     * @see \App\Http\Controllers\CheckPointController::show()
     * @see \App\Http\Controllers\CheckPointController::store()
     * @see \App\Http\Controllers\CheckPointController::update()
     * @see \App\Http\Controllers\CheckPointController::destroy()
     */
    Route::apiResource('checkPoint', CheckPointController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    
    /**
     * PaymentReceipt
     * @see \App\Http\Controllers\PaymentReceiptController
     * @see \App\Http\Controllers\PaymentReceiptController::index()
     * @see \App\Http\Controllers\PaymentReceiptController::store()
     */
    Route::apiResource('paymentReceipt', PaymentReceiptController::class)->only(['index','show', 'store','destroy']);
    /**
     * Business
     * @see \App\Http\Controllers\BusinessController
     * @see \App\Http\Controllers\BusinessController::index()
     * @see \App\Http\Controllers\BusinessController::show()
     * @see \App\Http\Controllers\BusinessController::store()
     */
    Route::apiResource('business', BusinessController::class)->only(['index', 'show', 'update', 'destroy']);
    /**
     * Comparison
     * @see \App\Http\Controllers\ComparisonController
     * @see \App\Http\Controllers\ComparisonController::index()
     * @see \App\Http\Controllers\ComparisonController::store()
     * @see \App\Http\Controllers\ComparisonController::destroy()
     */
    Route::apiResource('comparison', ComparisonController::class)->only(['index', 'store', 'destroy']);
    /**
     * Like
     * @see \App\Http\Controllers\LikeController
     * @see \App\Http\Controllers\LikeController::updated()
     */
    Route::post('like/{property}', [LikeController::class, 'like'])->name('property.like');

    /**
     * Comment
     * @see \App\Http\Controllers\CommentController
     * @see \App\Http\Controllers\CommentController::store()
     * @see \App\Http\Controllers\CommentController::update()
     * @see \App\Http\Controllers\CommentController::destroy()
     */
    Route::apiResource('comment', CommentController::class)->only(['store', 'update', 'destroy']);
});

Route::get('property', [PropertyController::class, 'index'])->name('property.index');
Route::get('property/{property}', [PropertyController::class, 'show'])->name('property.show');
/**
 * Route * Public *
* @see \App\Http\Controllers\PropertyController
* @see \App\Http\Controllers\PropertyController::all()
* @see \App\Http\Controllers\PropertyController::base()
* @see \App\Http\Controllers\PropertyController::show()
*/
Route::get('base', [PropertyController::class, 'base'])->name('property.all');
Route::get('all', [PropertyController::class, 'all'])->name('property.all');
Route::get('detail/{property}', [PropertyController::class, 'detail'])->name('property.show');
Route::match(['get', 'post'], '/webhook', [WebhookController::class, 'handle']);