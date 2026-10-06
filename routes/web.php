<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Firebase Cloud Messaging service worker. Served through Blade so the
// Firebase project config comes from .env instead of being hardcoded.
Route::get('/firebase-messaging-sw.js', function () {
    return response()
        ->view('firebase-messaging-sw')
        ->header('Content-Type', 'application/javascript');
});
