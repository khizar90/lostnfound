<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/insert', function () {
    $user = new Admin();
    $user->name = 'Kevin Anderson';
    $user->email = 'admin@admin.com';
    $user->password = Hash::make('qweqwe');
    $user->save();
});


Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        return view('auth-login');
    })->name('loginPage');

    Route::post('login', [AdminLoginController::class, 'login'])->name('login');
});


Route::prefix('dashboard')->middleware(['auth'])->name('dashboard-')->group(function () {
    Route::get('/', [AdminController::class, 'users'])->name('home');
    Route::get('users', [AdminController::class, 'users'])->name('users');

    Route::get('posts/{type}', [AdminController::class, 'posts'])->name('posts');
    Route::get('delete-post/{id}', [AdminController::class, 'deletePost'])->name('delete-post');

    Route::get('repoted-posts', [AdminController::class, 'repotedPost'])->name('repoted-posts');

    Route::get('send-notification', [AdminController::class, 'createSendNotification'])->name('send-notification');
    Route::post('send-notification', [AdminController::class, 'sendNotification']);

  
    Route::get('user-posts/{id}', [AdminController::class, 'userPost'])->name('user-posts');


    Route::get('logout', [AdminLoginController::class, 'logout'])->name('logout');


});