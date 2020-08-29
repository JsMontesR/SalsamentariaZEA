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
Route::post('/creartipoproducto', 'ProductoTipoController@store')->name('tiposproductos.crear')->middleware('auth');
Route::post('/modificartipoproducto', 'ProductoTipoController@update')->name('tiposproductos.actualizar')->middleware('auth');
Route::post('/borrartipoproducto', 'ProductoTipoController@destroy')->name('tiposproductos.borrar')->middleware('auth');

// CRUD productos unitarios
Route::get('/productos', 'ProductoController@index')->name('productos')->middleware('auth');
Route::post('/crearproducto', 'ProductoController@store')->name('productos.crear')->middleware('auth');
Route::post('/modificarproducto', 'ProductoController@update')->name('productos.actualizar')->middleware('auth');
Route::post('/borrarproducto', 'ProductoController@destroy')->name('productos.borrar')->middleware('auth');

// CRUD clientes
Route::get('/clientes', 'ClienteController@index')->name('clientes')->middleware('auth');
Route::post('/crearcliente', 'ClienteController@store')->name('clientes.crear')->middleware('auth');
Route::post('/modificarcliente', 'ClienteController@update')->name('clientes.actualizar')->middleware('auth');
Route::post('/borrarcliente', 'ClienteController@destroy')->name('clientes.borrar')->middleware('auth');

// CRUD usuarios
Route::get('/empleados', 'EmpleadoController@index')->name('empleados')->middleware('auth');
Route::post('/crearempleado', 'EmpleadoController@store')->name('empleados.crear')->middleware('auth');
Route::post('/modificarempleado', 'EmpleadoController@update')->name('empleados.actualizar')->middleware('auth');
Route::post('/borrarempleado', 'EmpleadoController@destroy')->name('empleados.borrar')->middleware('auth');

// CRUD usuarios
Route::get('/empleados', 'EmpleadoController@index')->name('empleados')->middleware('auth');
Route::post('/crearempleado', 'EmpleadoController@store')->name('empleados.crear')->middleware('auth');
Route::post('/modificarempleado', 'EmpleadoController@update')->name('empleados.actualizar')->middleware('auth');
Route::post('/borrarempleado', 'EmpleadoController@destroy')->name('empleados.borrar')->middleware('auth');

// CRUD entradas
Route::get('/entradas', 'EntradaController@index')->name('entradas')->middleware('auth');
Route::post('/crearentrada', 'EntradaController@store')->name('entradas.crear')->middleware('auth');
Route::post('/modificarentrada', 'EntradaController@update')->name('entradas.actualizar')->middleware('auth');
Route::post('/borrarentrada', 'EntradaController@destroy')->name('entradas.borrar')->middleware('auth');

// CRUD nóminas
Route::get('/nominas', 'NominaController@index')->name('nominas')->middleware('auth');
Route::post('/pagarnomina', 'NominaController@pay')->name('nominas.pagar')->middleware('auth');
Route::post('/modificarnomina', 'NominaController@update')->name('nominas.actualizar')->middleware('auth');
Route::post('/borrarnomina', 'NominaController@destroy')->name('nominas.borrar')->middleware('auth');


