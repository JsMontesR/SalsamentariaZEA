<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();
Route::get('/inicio', 'HomeController@index')->name('home');
Route::get('/index', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

// CRUD Proveedores
Route::get('/proveedores','ProveedorController@index')->name('proveedores')->middleware('auth');
Route::post('/crearproveedor','ProveedorController@store')->name('proveedores.crear')->middleware('auth');
Route::post('/modificarproveedor','ProveedorController@update')->name('proveedores.actualizar')->middleware('auth');
Route::post('/borrarproveedor','ProveedorController@destroy')->name('proveedores.borrar')->middleware('auth');

// CRUD Tipos de productos
Route::get('/tiposproductos','ProductoTipoController@index')->name('tiposproductos')->middleware('auth');
Route::post('/creartipoproducto','ProductoTipoController@store')->name('tiposproductos.crear')->middleware('auth');
Route::post('/modificartipoproducto','ProductoTipoController@update')->name('tiposproductos.actualizar')->middleware('auth');
Route::post('/borrartipoproducto','ProductoTipoController@destroy')->name('tiposproductos.borrar')->middleware('auth');

// CRUD productos unitarios
Route::get('/productosunitarios','ProductoUnitarioController@index')->name('productosunitarios')->middleware('auth');
Route::post('/crearproductounitario','ProductoUnitarioController@store')->name('productosunitarios.crear')->middleware('auth');
Route::post('/modificarproductounitario','ProductoUnitarioController@update')->name('productosunitarios.actualizar')->middleware('auth');
Route::post('/borrarproductounitario','ProductoUnitarioController@destroy')->name('productosunitarios.borrar')->middleware('auth');



