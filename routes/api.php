<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

//obtiene las rutas de recurso del controlados User\UserController
Route::post('usuarios/loadPerfil/{id}', 'User\UserController@loadPerfil');
Route::get('usuarios/user_token/{token}', 'User\UserController@get_user_by_token');
Route::resource('usuarios', 'User\UserController',['only'=>['index','show','store']]);



