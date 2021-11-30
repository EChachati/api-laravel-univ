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

Route::middleware('auth:jwt')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('update', 'App\Http\Controllers\AuthController@update');
    Route::post('update/{id}', 'App\Http\Controllers\AuthController@update');
    Route::delete('destroy/{id}', 'App\Http\Controllers\AuthController@destroy');
    Route::delete('destroy', 'App\Http\Controllers\AuthController@destroy');
    Route::get('list', 'App\Http\Controllers\AuthController@list');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'program'
], function ($router) {
    Route::get('detail/{id}', 'App\Http\Controllers\ProgramController@detail');
    Route::get('list', 'App\Http\Controllers\ProgramController@list');
    Route::post('create', 'App\Http\Controllers\ProgramController@create');
    Route::post('update/{id}', 'App\Http\Controllers\ProgramController@update');
    Route::delete('destroy/{id}', 'App\Http\Controllers\ProgramController@destroy');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'tag'
], function ($router) {
    Route::get('list/{name}', 'App\Http\Controllers\TagController@list');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'message'
], function ($router) {
    Route::get('list', 'App\Http\Controllers\MessageController@list');
    Route::get('detail/{id}', 'App\Http\Controllers\MessageController@detail');
    Route::post('create', 'App\Http\Controllers\MessageController@create');
    Route::post('update/{id}', 'App\Http\Controllers\MessageController@update');
    Route::delete('destroy/{id}', 'App\Http\Controllers\MessageController@destroy');
});