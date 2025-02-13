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

// Route::redirect('/','admin')->name('login');
Route::redirect('/', 'welcome');
Route::redirect('login', 'admin/login')->name('login');
// Route::redirect('register', 'admin/register')->name('register');
Route::view('welcome', 'welcome')->name('welcome');
Route::get('/profile', function () {
    // Only verified users may access this route...
})->middleware('verified');
