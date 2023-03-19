<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/send-passreset-email', [ResetPasswordController::class, 'send_passreset_email']);
Route::post('/reset-password/{token}', [ResetPasswordController::class, 'reset']);

Route::post('/admin-register', [AdminController::class, 'register']);
Route::post('/admin-login', [AdminController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/loggeduser', [UserController::class, 'logged_user']);
    Route::post('/changepassword', [UserController::class, 'change_password']);

    Route::post('/admin-logout', [AdminController::class, 'logout']);
    Route::post('/admin-change-password', [AdminController::class, 'change_password']);
});

Route::resource('products', 'App\Http\Controllers\ProductController'); 
Route::post('/image', [ProductController::class, 'image']);
Route::post('/updateimage', [ProductController::class, 'updateImage']);
Route::get('/getimages', [ProductController::class, 'getImages']);

Route::get('/garments', [ProductController::class, 'getGarments']);
Route::get('/watches', [ProductController::class, 'getWatches']);
Route::get('/footwears', [ProductController::class, 'getFootwears']);
