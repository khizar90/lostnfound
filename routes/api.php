<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('splash' ,[ UserController::class, 'splash']);
Route::post('add-post' ,[ UserController::class, 'addPost']);
Route::post('home' ,[ UserController::class, 'home']);
Route::post('report' ,[ UserController::class, 'report']);
Route::post('my-item' ,[ UserController::class, 'myItem']);
Route::get('delete-item/{id}' ,[ UserController::class, 'deleteItem']);

Route::post('search' ,[ UserController::class, 'search']);

Route::post('send-message' ,[ UserController::class, 'sendMessage']);
Route::get('read-message/{login_id}/{user_id}' ,[ UserController::class, 'readMessage']);
Route::get('conversation/{login_id}/{user_id}' ,[ UserController::class, 'conversation']);
Route::get('unread/{login_id}' ,[ UserController::class, 'unreadCount']);

Route::get('inbox/{login_id}' ,[ UserController::class, 'inbox']);




