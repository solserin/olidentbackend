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

//obtiene las rutas de recurso del controlados User\UserController
Route::post('usuarios/loadPerfil/{id}', 'User\UserController@loadPerfil');
Route::resource('usuarios', 'User\UserController',['only'=>['index','show','store']]);


























//obtiene las rutas de recurso del controlados User\RolesController
Route::resource('roles', 'User\RolesController',['only'=>['index','show']]);



//obtiene las rutas de recurso del controlados User\GruposController
Route::resource('grupos', 'User\GruposController',['only'=>['index','show']]);



//obtiene las rutas de recurso del controlados User\ModulosController
Route::resource('modulos', 'User\ModulosController',['only'=>['index','show']]);



//obtiene las rutas de recurso del controlados User\PermisosController
Route::resource('permisos', 'User\PermisosController',['only'=>['index','show']]);