<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/mock', 'MockApiController@index');

Route::group(['prefix' => 'visitor'], function() {
	Route::post('/set', 'VisitorController@set');
});

Route::group(['prefix' => 'connection'], function() {
	Route::patch('changed', 'ConnectionController@connectionChanged');
});

Route::group(['prefix' => 'carrier'], function() {
	Route::patch('update', 'CarrierController@updateCarrier');
});

Route::group(['prefix'=> 'offers'], function() {
	Route::post('fetch', 'OfferController@fetch');
});