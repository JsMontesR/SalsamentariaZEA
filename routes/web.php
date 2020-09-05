<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/inicio', 'HomeController@index')->name('home');
Route::get('/index', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

/*
 * OPERACIONES CRUD DEL NEGOCIO
 */

// CRUD Proveedores
Route::get('/proveedores', 'ProveedorController@index')->name('proveedores')->middleware('auth');

Route::get('api/listarproveedores', 'ProveedorController@list')->middleware('auth');
Route::post('api/crearproveedor', 'ProveedorController@store')->middleware('auth');
Route::post('api/modificarproveedor', 'ProveedorController@update')->middleware('auth');
Route::post('api/borrarproveedor', 'ProveedorController@destroy')->middleware('auth');

// CRUD Tipos de productos
Route::get('/tiposproductos', 'ProductoTipoController@index')->name('tiposproductos')->middleware('auth');

Route::get('api/listartiposproductos', 'ProductoTipoController@list')->middleware('auth');
Route::post('api/creartipoproducto', 'ProductoTipoController@store')->middleware('auth');
Route::post('api/modificartipoproducto', 'ProductoTipoController@update')->middleware('auth');
Route::post('api/borrartipoproducto', 'ProductoTipoController@destroy')->middleware('auth');

// CRUD productos
Route::get('/productos', 'ProductoController@index')->name('productos')->middleware('auth');

Route::get('api/listarproductos', 'ProductoController@list')->middleware('auth');
Route::post('api/crearproducto', 'ProductoController@store')->middleware('auth');
Route::post('api/modificarproducto', 'ProductoController@update')->middleware('auth');
Route::post('api/borrarproducto', 'ProductoController@destroy')->middleware('auth');

// CRUD clientes
Route::get('/clientes', 'ClienteController@index')->name('clientes')->middleware('auth');

Route::get('api/listarclientes', 'ClienteController@list')->middleware('auth');
Route::post('api/crearcliente', 'ClienteController@store')->middleware('auth');
Route::post('api/modificarcliente', 'ClienteController@update')->middleware('auth');
Route::post('api/borrarcliente', 'ClienteController@destroy')->middleware('auth');

// CRUD usuarios
Route::get('/empleados', 'EmpleadoController@index')->name('empleados')->middleware('auth');

Route::get('api/listarempleados', 'EmpleadoController@list')->middleware('auth');
Route::post('api/crearempleado', 'EmpleadoController@store')->middleware('auth');
Route::post('api/modificarempleado', 'EmpleadoController@update')->middleware('auth');
Route::post('api/borrarempleado', 'EmpleadoController@destroy')->middleware('auth');

/*
 * OPERACIONES CORE DEL NEGOCIO
 */

// Procesamiento de entradas
Route::get('/entradas', 'EntradaController@index')->name('entradas')->middleware('auth');

Route::get('api/listarentradas', 'EntradaController@list')->middleware('auth');
Route::post('api/pagarentrada', 'EntradaController@pay')->middleware('auth');
Route::post('api/anularentrada', 'EntradaController@undoPay')->middleware('auth');

// Procesamiento de ventas
Route::get('/entradas', 'EntradaController@index')->name('entradas')->middleware('auth');

Route::get('api/listarventas', 'EntradaController@list')->middleware('auth');
Route::post('api/cobrarventa', 'EntradaController@charge')->middleware('auth');
Route::post('api/anularventa', 'EntradaController@undoCharge')->middleware('auth');

// Procesamiento nóminas
Route::get('/nominas', 'NominaController@index')->name('nominas')->middleware('auth');

Route::get('api/listarnominas', 'NominaController@list')->middleware('auth');
Route::post('api/pagarnomina', 'NominaController@pay')->middleware('auth');
Route::post('api/anularnomina', 'NominaController@undoPay')->middleware('auth');


