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
Route::put('usuarios/update_perfil/{user}', 'User\UserController@update_perfil');

//regresa los vendedores
Route::get('usuarios/vendedores', 'User\UserController@vendedores');
Route::resource('usuarios', 'User\UserController',['only'=>['index','show','store','update','destroy']]);


//rutas de roles
//reporte de todos los roles
Route::get('roles_reporte', 'User\RolesController@get_reporte_roles');
//obtengo la lista de roles
Route::get('roles/get_roles', 'User\RolesController@get_roles');
Route::resource('roles', 'User\RolesController',['only'=>['index','show','store','update','destroy']]);



//rutas de permisos
Route::resource('permisos', 'User\PermisosController',['only'=>['index','show','store']]);


//rutas de empresas
Route::resource('empresas', 'EmpresasController',['only'=>['show','update']]);

//rutas de modulos
Route::resource('modulos', 'User\ModulosController',['only'=>['index','show','destroy']]);




//rutas de servicios
Route::get('servicios/servicios_reporte', 'ServiciosController@get_reporte_servicios');
Route::resource('servicios', 'ServiciosController',['only'=>['index','store','show','update','destroy']]);

//rutas de tipos de servicios
Route::get('tipos_servicios/get_tipos', 'TipoServiciosController@get_tipos');
Route::resource('tipos_servicios', 'TipoServiciosController',['only'=>['index','show','store','destroy','update']]);


//rutas de tipos de precio
Route::resource('tipo_precios', 'TipoPreciosController',['only'=>['index']]);


//grupos de vendedores
Route::get('grupos_vendedores/get_agregar_modificar_lista', 'GruposVendedoresController@get_agregar_modificar_lista');
Route::resource('grupos_vendedores', 'GruposVendedoresController',['only'=>['index','show','store','update','destroy']]);

//rutas
Route::get('rutas/get_cobradores', 'RutasController@get_cobradores');
Route::get('rutas/get_rutas_disponibles', 'RutasController@get_rutas_disponibles');
//filtrar localidades
Route::get('rutas/localidad', 'RutasController@localidad');
Route::resource('rutas', 'RutasController',['only'=>['index','show','store','update','destroy']]);

//tipos de poliza
Route::resource('tipos_polizas', 'TiposPolizasController',['only'=>['index','show','store','update','destroy']]);

//polizas
Route::get('polizas/beneficiario', 'PolizasController@beneficiario');
Route::post('polizas/renovar_poliza', 'PolizasController@renovar_poliza');
Route::put('polizas/cancelar_poliza/{id}', 'PolizasController@cancelar_poliza');
Route::get('polizas/nota_venta', 'PolizasController@nota_venta');
Route::get('polizas/tarjeta_cobranza', 'PolizasController@tarjeta_cobranza');

Route::resource('polizas', 'PolizasController',['only'=>['index','show','store','update','destroy']]);

//ventas
Route::get('ventas/estado_cuenta/{id_venta}', 'VentasController@estado_cuenta');
Route::put('ventas/cancelar_pago/{pago_id}', 'VentasController@cancelar_pago');

//reporte de pagos
Route::get('ventas/reporte_especifico_pagos', 'VentasController@reporte_especifico_pagos');


Route::resource('ventas', 'VentasController',['only'=>['index','show','store','update','destroy']]);




//nuevas rutas del proyecto
