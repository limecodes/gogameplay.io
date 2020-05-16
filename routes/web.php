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

Route::get('/', 'IndexController@index');

Route::middleware('mobile')->group(function () {
    Route::get('/game/{game}', 'GameController@index')->name('game');
});

Route::get('/nonmobile', 'NonmobileController@index');
