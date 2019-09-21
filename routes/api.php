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
//obitiene los datos del usuario por email
Route::get('usuarios/user_email/{email}', 'User\UserController@getUserByEmail');
Route::resource('usuarios', 'User\UserController',['only'=>['index','show','store']]);


//rutas de roles
//reporte de todos los roles
Route::get('roles_reporte', 'User\RolesController@get_reporte_roles');
Route::resource('roles', 'User\RolesController',['only'=>['index','show','store','update','destroy']]);

//rutas de permisos
Route::resource('permisos', 'User\PermisosController',['only'=>['index','show','store']]);

//rutas de modulos
Route::resource('modulos', 'User\ModulosController',['only'=>['index','show','destroy']]);


