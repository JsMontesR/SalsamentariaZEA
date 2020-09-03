<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/inicio', 'HomeController@index')->name('home');
Route::get('/index', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

// CRUD Proveedores
Route::get('/proveedores', 'ProveedorController@index')->name('proveedores')->middleware('auth');
Route::post('/crearproveedor', 'ProveedorController@store')->name('proveedores.crear')->middleware('auth');
Route::post('/modificarproveedor', 'ProveedorController@update')->name('proveedores.actualizar')->middleware('auth');
Route::post('/borrarproveedor', 'ProveedorController@destroy')->name('proveedores.borrar')->middleware('auth');

// CRUD Tipos de productos
Route::get('/tiposproductos', 'ProductoTipoController@index')->name('tiposproductos')->middleware('auth');

Route::get('api/listartiposproductos', 'ProductoTipoController@list')->middleware('auth');
Route::post('/creartipoproducto', 'ProductoTipoController@store')->name('tiposproductos.crear')->middleware('auth');
Route::post('/modificartipoproducto', 'ProductoTipoController@update')->name('tiposproductos.actualizar')->middleware('auth');
Route::post('/borrartipoproducto', 'ProductoTipoController@destroy')->name('tiposproductos.borrar')->middleware('auth');

// CRUD productos unitarios
Route::get('/productos', 'ProductoController@index')->name('productos')->middleware('auth');

Route::get('api/listarproductos', 'ProductoController@list')->middleware('auth');
Route::post('/crearproducto', 'ProductoController@store')->middleware('auth');
Route::post('/modificarproducto', 'ProductoController@update')->middleware('auth');
Route::post('/borrarproducto', 'ProductoController@destroy')->middleware('auth');

// CRUD clientes
Route::get('/clientes', 'ClienteController@index')->name('clientes')->middleware('auth');

Route::get('api/listarclientes', 'ClienteController@list')->middleware('auth');
Route::post('/crearcliente', 'ClienteController@store')->middleware('auth');
Route::post('/modificarcliente', 'ClienteController@update')->middleware('auth');
Route::post('/borrarcliente', 'ClienteController@destroy')->middleware('auth');

// CRUD usuarios
Route::get('/empleados', 'EmpleadoController@index')->name('empleados')->middleware('auth');

Route::get('api/listarempleados', 'EmpleadoController@list')->middleware('auth');
Route::post('/crearempleado', 'EmpleadoController@store')->middleware('auth');
Route::post('/modificarempleado', 'EmpleadoController@update')->middleware('auth');
Route::post('/borrarempleado', 'EmpleadoController@destroy')->middleware('auth');

// CRUD entradas
Route::get('/entradas', 'EntradaController@index')->name('entradas')->middleware('auth');

Route::get('api/listarentradas', 'EntradaController@list')->middleware('auth');
Route::post('api/crearentrada', 'EntradaController@store')->middleware('auth');
Route::post('api/modificarentrada', 'EntradaController@update')->middleware('auth');
Route::post('api/borrarentrada', 'EntradaController@destroy')->middleware('auth');

// CRUD nóminas
Route::get('/nominas', 'NominaController@index')->name('nominas')->middleware('auth');

Route::get('api/listarnominas', 'NominaController@list')->middleware('auth');
Route::post('/pagarnomina', 'NominaController@pay')->middleware('auth');
Route::post('/modificarnomina', 'NominaController@update')->middleware('auth');
Route::post('/borrarnomina', 'NominaController@destroy')->middleware('auth');


