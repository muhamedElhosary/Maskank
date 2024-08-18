<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\OwnerController;
use App\Http\Controllers\Data\FavoriteController;
use App\Http\Controllers\Data\RequestController;
use App\Http\Controllers\Data\PostController;
use App\Http\Controllers\Data\SelectPostsController;
use App\Http\Controllers\Data\UpdatePostController;
use App\Http\Controllers\User\OwnersController;
use App\Http\Controllers\Auth\RenterController;
use App\Http\Controllers\User\RentersController;
use App\Http\Controllers\User\AdminsController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/** urls Renter */
Route::post('renter/register', [RenterController::class, 'store']);
Route::post('renter/login', [RenterController::class, 'login']);
Route::post('renter/verification_email', [EmailVerificationController::class, 'verifyEmailRenter']);
Route::post('renter/forget-password', [ForgetPasswordController::class, 'forgetPasswordRenter']);
Route::post('renter/reset password', [ResetPasswordController::class, 'passwordResetRenter']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('renter/logout', [RenterController::class, 'logout'])->middleware('token.can:renter');
    Route::get('renters', [RentersController::class, 'index'])->middleware('token.can:admin');
    Route::put('renter/update/{renter_id}', [RentersController::class, 'update'])->middleware('token.can:renter');
    Route::get('renter/{renter_id}', [RentersController::class, 'show'])->middleware('token.can:renter,admin,owner');
    Route::delete('renter/{renter_id}', [RentersController::class, 'destroy'])->middleware('token.can:renter,admin');
});


/********************************************************************************************88 */

Route::middleware('auth:sanctum')->group(function () {
    Route::post('Change Password', [ResetPasswordController::class, 'ChangePassword'])->middleware('token.can:renter,owner,admin');

});





/** urls Owner */

Route::post('owner/register', [OwnerController::class, 'store']);
Route::post('owner/login', [OwnerController::class, 'login']);
Route::post('owner/forget-password', [ForgetPasswordController::class, 'ForgetPasswordOwner']);
Route::post('owner/reset password', [ResetPasswordController::class, 'passwordResetOwner']);
Route::post('owner/verification_email', [EmailVerificationController::class, 'verifyEmailOwner']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('owner/logout', [OwnerController::class, 'logout'])->middleware('auth:sanctum')->middleware('token.can:owner');
    Route::get('owners', [OwnersController::class, 'index'])->middleware('token.can:admin');
    Route::put('owner/update/{owner_id}', [OwnersController::class, 'update'])->middleware('token.can:owner');

    Route::get('owner/{owner_id}', [OwnersController::class, 'show'])->middleware('token.can:owner,admin,renter');


    Route::delete('owner/{owner_id}', [OwnersController::class, 'destroy'])->middleware('token.can:owner,admin');
});

/************************************************************************************************88 */





/**urls Admin */
Route::post('/adminLogin', [AdminController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/admin', [AdminsController::class, 'showAdmin'])->middleware('token.can:admin');
    Route::post('/adminLogout', [AdminController::class, 'logout'])->middleware('token.can:admin');

});

/****************************************************************************************************** */




/**urls Post**/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('posts', [SelectPostsController::class, 'index'])->middleware('token.can:renter,admin');
    Route::get('post/{id}', [SelectPostsController::class, 'show'])->middleware('token.can:renter,admin,owner');
    Route::get('home/posts', [SelectPostsController::class, 'showRandomPosts'])->middleware('token.can:renter');
    Route::get('posts/waiting', [SelectPostsController::class, 'waiting'])->middleware('token.can:admin');
    Route::get('posts/acceptable', [SelectPostsController::class, 'acceptable'])->middleware('token.can:admin');
    Route::get('WaitingAndDone/{owner_id}', [SelectPostsController::class, 'numberOfWaitingAndDone'])->middleware('token.can:owner,admin');
    Route::get('/search', [SelectPostsController::class, 'searchLocal'])->middleware('token.can:renter');
    Route::get('/postsOwner/{owner_id}', [SelectPostsController::class, 'postsOwner'])->middleware('token.can:owner,admin');
    Route::get('/postsnumber', [SelectPostsController::class, 'postsnumber'])->middleware('token.can:admin');           //->admin





    Route::get('posts/filter', [SelectPostsController::class, 'filterPosts'])->middleware('token.can:renter');

    Route::post('post/create', [PostController::class, 'store'])->middleware('token.can:owner,admin');
    Route::put('post/update/{post_id}', [UpdatePostController::class, 'update'])->middleware('token.can:owner');
    Route::delete('post/delete/{post_id}', [PostController::class, 'destroy'])->middleware('token.can:owner,admin');
    Route::put('acceptPost/{post_id}', [UpdatePostController::class, 'updateStatus'])->middleware('token.can:admin');

});
/****************************************************************************************************** */

/**urls Favorite */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('post/favorite/{post_id}', [FavoriteController::class, 'store'])->middleware('token.can:renter');
    Route::get('favorites', [FavoriteController::class, 'index'])->middleware('token.can:admin');
    Route::get('favorite/{favorite_id}', [FavoriteController::class, 'show'])->middleware('token.can:renter,admin,owner');
    Route::get('renter/favorite/{renter_id}', [FavoriteController::class, 'showFavoritePosts'])->middleware('token.can:renter,admin');

    Route::delete('unfavorite/{post_id}', [FavoriteController::class, 'destroy'])->middleware('token.can:renter');


});

/****************************************************************************************************** */


/**Urls Request */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('post/request/{post_id}', [RequestController::class, 'store'])->middleware('token.can:renter');
    Route::get('requests', [RequestController::class, 'index'])->middleware('token.can:admin');
    Route::get('request/{request_id}', [RequestController::class, 'show'])->middleware('token.can:renter,admin,owner');
    Route::get('renter/request/{renter_id}', [RequestController::class, 'showRequestPosts'])->middleware('token.can:admin');
    Route::get('requests/count', [RequestController::class, 'countRequest']);  //->admin->request
    Route::get('booked/{renter_id}', [RequestController::class, 'showBooked'])->middleware('token.can:renter,admin');  //->admin->request

    Route::delete('delete/request/{post_id}', [RequestController::class, 'destroy'])->middleware('token.can:renter');

    Route::get('owner/request/{owner_id}', [RequestController::class, 'OwnerRequest'])->middleware('token.can:owner');
    Route::put('request/update/{request_id}', [RequestController::class, 'updateStatus'])->middleware('token.can:owner');

});




/****************************************************************************************************** */

Route::middleware('token.can:owner,admin')->group(function () {
    // Routes or controllers requiring 'owner' or 'admin' token
});


