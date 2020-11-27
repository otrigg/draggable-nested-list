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

Route::get('list', 'App\Http\Controllers\ListController@getList');
Route::post('list', 'App\Http\Controllers\ListController@saveList');
Route::delete('list', 'App\Http\Controllers\ListController@deleteBranch');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
