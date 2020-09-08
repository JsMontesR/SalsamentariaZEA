<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/inicio', 'HomeController@index')->name('home');
Route::get('/index', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/proveedores', 'ProveedorController@index')->name('proveedores')->middleware('auth');
Route::get('/tiposproductos', 'ProductoTipoController@index')->name('tiposproductos')->middleware('auth');
Route::get('/productos', 'ProductoController@index')->name('productos')->middleware('auth');
Route::get('/clientes', 'ClienteController@index')->name('clientes')->middleware('auth');
Route::get('/empleados', 'EmpleadoController@index')->name('empleados')->middleware('auth');
Route::get('/nominas', 'NominaController@index')->name('nominas')->middleware('auth');
Route::get('/entradas', 'EntradaController@index')->name('entradas')->middleware('auth');
Route::get('/ventas', 'VentasController@index')->name('ventas')->middleware('auth');
Route::get('/movimientos','MovimientoController@index')->name('movimientos')->middleware('auth');

/*
 * OPERACIONES CRUD Y CORE DEL NEGOCIO
 */

Route::prefix('api')->group(function(){

    // CRUD Proveedores
    Route::prefix('proveedores')->group(function(){
        Route::get('listar', 'ProveedorController@list')->middleware('auth');
        Route::post('crear', 'ProveedorController@store')->middleware('auth');
        Route::post('modificar', 'ProveedorController@update')->middleware('auth');
        Route::post('borrar', 'ProveedorController@destroy')->middleware('auth');
    });

    // CRUD Tipos de productos
    Route::prefix('tiposproductos')->group(function(){
        Route::get('listar', 'ProductoTipoController@list')->middleware('auth');
        Route::post('crear', 'ProductoTipoController@store')->middleware('auth');
        Route::post('modificar', 'ProductoTipoController@update')->middleware('auth');
        Route::post('borrar', 'ProductoTipoController@destroy')->middleware('auth');
    });

    // CRUD productos
    Route::prefix('productos')->group(function(){
        Route::get('listar', 'ProductoController@list')->middleware('auth');
        Route::post('crear', 'ProductoController@store')->middleware('auth');
        Route::post('modificar', 'ProductoController@update')->middleware('auth');
        Route::post('borrar', 'ProductoController@destroy')->middleware('auth');
    });

    // CRUD clientes
    Route::prefix('clientes')->group(function(){
        Route::get('listar', 'ClienteController@list')->middleware('auth');
        Route::post('crear', 'ClienteController@store')->middleware('auth');
        Route::post('modificar', 'ClienteController@update')->middleware('auth');
        Route::post('borrar', 'ClienteController@destroy')->middleware('auth');
    });

    // CRUD empleados
    Route::prefix('empleados')->group(function(){
        Route::get('listar', 'EmpleadoController@list')->middleware('auth');
        Route::post('crear', 'EmpleadoController@store')->middleware('auth');
        Route::post('modificar', 'EmpleadoController@update')->middleware('auth');
        Route::post('borrar', 'EmpleadoController@destroy')->middleware('auth');
    });

    // Procesamiento nóminas
    Route::prefix('nominas')->group(function(){
        Route::get('listar', 'NominaController@list')->middleware('auth');
        Route::post('pagar', 'NominaController@pay')->middleware('auth');
        Route::post('anular', 'NominaController@undoPay')->middleware('auth');
    });

    // Procesamiento de entradas
    Route::prefix('entradas')->group(function(){
        Route::get('listar', 'EntradaController@list')->middleware('auth');
        Route::get('{id}/productos', 'EntradaController@listProductos')->middleware('auth');
        Route::post('crear', 'EntradaController@store')->middleware('auth');
        Route::post('pagar', 'EntradaController@pagar')->middleware('auth');
        Route::post('anular', 'EntradaController@anular')->middleware('auth');
    });

    // Procesamiento de ventas
    Route::prefix('ventas')->group(function(){
        Route::get('listar', 'VentaController@list')->middleware('auth');
        Route::get('{id}/productos', 'VentasController@listProductos')->middleware('auth');
        Route::post('crear', 'VentasController@store')->middleware('auth');
        Route::post('cargar', 'VentasController@cargar')->middleware('auth');
        Route::post('anular', 'VentasController@anular')->middleware('auth');
    });

    // Procesamiento de movimientos
    Route::prefix('movimientos')->group(function(){
        Route::get('listar', 'MovimientoController@list')->middleware('auth');
    });

});
