<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//google login
Route::get('/login/google',[LoginController::class,'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback',[LoginController::class,'handleGoogleCallback']);

//github login
Route::get('/login/github',[LoginController::class,'redirectToGithub'])->name('login.github');
Route::get('/login/github/callback',[LoginController::class,'handleGithubCallback']);

//facebook Login
Route::get('/login/facebook',[LoginController::class,'redirectToFacebook'])->name('login.facebook');
Route::get('/login/facebook/callback',[LoginController::class,'handleFacebookCallback']);

//twitter Login
// Route::get('/login/twitter',[LoginController::class,'redirectToTwitter'])->name('login.twitter');
// Route::get('/login/twitter/callback',[LoginController::class,'handleTwitterCallback']);