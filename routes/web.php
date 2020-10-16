<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/inicio', 'HomeController@index')->name('home');
Route::get('/index', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');


Route::get('/proveedores', 'ProveedorController@index')->name('proveedores')->middleware('auth');
Route::get('/tiposproductos', 'ProductoTipoController@index')->name('tiposproductos')->middleware('auth');
Route::get('/tiposservicios', 'TipoServicioController@index')->name('tiposservicios')->middleware('auth');
Route::get('/productos', 'ProductoController@index')->name('productos')->middleware('auth');
Route::get('/clientes', 'ClienteController@index')->name('clientes')->middleware('auth');
Route::get('/empleados', 'EmpleadoController@index')->name('empleados')->middleware('auth');
Route::get('/nominas', 'NominaController@index')->name('nominas')->middleware('auth');
Route::get('/retiros', 'RetiroController@index')->name('retiros')->middleware('auth');
Route::get('/ingresos', 'IngresoController@index')->name('ingresos')->middleware('auth');
Route::get('/entradas', 'EntradaController@index')->name('entradas')->middleware('auth');
Route::get('/ventas', 'VentaController@index')->name('ventas')->middleware('auth');
Route::get('/movimientos', 'MovimientoController@index')->name('movimientos')->middleware('auth');
Route::get('/notificaciones', 'NotificacionController@index')->name('notificaciones')->middleware('auth');
Route::post('/imprimirVenta', 'VentaController@imprimir')->name('imprimirVenta')->middleware('auth');

/*
 * OPERACIONES CRUD Y CORE DEL NEGOCIO
 */

Route::prefix('api')->group(function () {

    // CRUD Proveedores
    Route::prefix('proveedores')->group(function () {
        Route::get('listar', 'ProveedorController@list')->middleware('auth');
        Route::post('crear', 'ProveedorController@store')->middleware('auth');
        Route::post('modificar', 'ProveedorController@update')->middleware('auth');
        Route::post('borrar', 'ProveedorController@destroy')->middleware('auth');
    });

    // CRUD Tipos de productos
    Route::prefix('tiposproductos')->group(function () {
        Route::get('listar', 'ProductoTipoController@list')->middleware('auth');
        Route::post('crear', 'ProductoTipoController@store')->middleware('auth');
        Route::post('modificar', 'ProductoTipoController@update')->middleware('auth');
        Route::post('borrar', 'ProductoTipoController@destroy')->middleware('auth');
    });

    // CRUD productos
    Route::prefix('productos')->group(function () {
        Route::get('listar', 'ProductoController@list')->middleware('auth');
        Route::post('crear', 'ProductoController@store')->middleware('auth');
        Route::post('modificar', 'ProductoController@update')->middleware('auth');
        Route::post('borrar', 'ProductoController@destroy')->middleware('auth');
    });

    // CRUD clientes
    Route::prefix('clientes')->group(function () {
        Route::get('listar', 'ClienteController@list')->middleware('auth');
        Route::post('crear', 'ClienteController@store')->middleware('auth');
        Route::post('modificar', 'ClienteController@update')->middleware('auth');
        Route::post('borrar', 'ClienteController@destroy')->middleware('auth');
    });

    // CRUD empleados
    Route::prefix('empleados')->group(function () {
        Route::get('listar', 'EmpleadoController@list')->middleware('auth');
        Route::post('crear', 'EmpleadoController@store')->middleware('auth');
        Route::post('modificar', 'EmpleadoController@update')->middleware('auth');
        Route::post('borrar', 'EmpleadoController@destroy')->middleware('auth');
    });

    // CRUD Tipos de servicios
    Route::prefix('tiposervicios')->group(function () {
        Route::get('listar', 'TipoServicioController@list')->middleware('auth');
        Route::post('crear', 'TipoServicioController@store')->middleware('auth');
        Route::post('modificar', 'TipoServicioController@update')->middleware('auth');
        Route::post('borrar', 'TipoServicioController@destroy')->middleware('auth');
    });

    // Procesamiento nóminas
    Route::prefix('nominas')->group(function () {
        Route::get('listar', 'NominaController@list')->middleware('auth');
        Route::get('{id}/pagos', 'NominaController@pagos')->middleware('auth');
        Route::post('crear', 'NominaController@store')->middleware('auth');
        Route::post('crearypagar', 'NominaController@storePay')->middleware('auth');
        Route::post('modificar', 'NominaController@update')->middleware('auth');
        Route::post('anular', 'NominaController@anular')->middleware('auth');
    });

    // Procesamiento de entradas
    Route::prefix('entradas')->group(function () {
        Route::get('listar', 'EntradaController@list')->middleware('auth');
        Route::get('{id}/pagos', 'EntradaController@pagos')->middleware('auth');
        Route::post('crear', 'EntradaController@store')->middleware('auth');
        Route::post('crearypagar', 'EntradaController@storePay')->middleware('auth');
        Route::post('modificar', 'EntradaController@update')->middleware('auth');
        Route::post('anular', 'EntradaController@anular')->middleware('auth');
    });

    // Procesamiento de ventas
    Route::prefix('ventas')->group(function () {
        Route::get('listar', 'VentaController@list')->middleware('auth');
        Route::get('{id}/cobros', 'VentaController@cobros')->middleware('auth');
        Route::post('crear', 'VentaController@store')->middleware('auth');
        Route::post('crearycobrar', 'VentaController@storeCharge')->middleware('auth');
        Route::post('modificar', 'VentaController@update')->middleware('auth');
        Route::post('anular', 'VentaController@anular')->middleware('auth');
    });

    // Procesamiento de retiros
    Route::prefix('retiros')->group(function () {
        Route::get('listar', 'RetiroController@list')->middleware('auth');
        Route::post('crear', 'RetiroController@store')->middleware('auth');
        Route::post('anular', 'RetiroController@anular')->middleware('auth');
    });

    // Procesamiento de ingresos
    Route::prefix('ingresos')->group(function () {
        Route::get('listar', 'IngresoController@list')->middleware('auth');
        Route::post('crear', 'IngresoController@store')->middleware('auth');
        Route::post('anular', 'IngresoController@anular')->middleware('auth');
    });

    // Procesamiento de movimientos
    Route::prefix('movimientos')->group(function () {
        Route::get('listar', 'MovimientoController@list')->middleware('auth');
        Route::post('generarPago', 'MovimientoController@generarPago')->middleware('auth');
        Route::post('anularPago', 'MovimientoController@anularPago')->middleware('auth');
        Route::post('generarCobro', 'MovimientoController@generarCobro')->middleware('auth');
        Route::post('anularCobro', 'MovimientoController@anularCobro')->middleware('auth');
    });

    /*
     * REPORTES
     */

    Route::prefix('reportes')->group(function () {
        Route::get('/listarVentas', 'ReportesController@listarVentas')->name('listarVentas')->middleware('auth');
    });

    /*
     * CIERRES
     */

    Route::prefix('cierres')->group(function () {
        Route::get('/listar', 'CierresController@list')->middleware('auth');
        Route::get('/generarCierre', 'CierresController@generarCierre')->name('generarCierre')->middleware('auth');
    });
});

/*
 * REPORTES
 */
Route::prefix('reportes')->group(function () {
    Route::get('/', 'ReportesController@index')->name('reportes')->middleware('auth');
    Route::get('/inventario', 'ReportesController@reporteInventario')->name('reporteInventario')->middleware('auth');
    Route::get('/ventas', 'ReportesController@reporteVentas')->name('reporteVentas')->middleware('auth');
    Route::get('/productosMenosVendidos', 'ReportesController@productosMenosVendidos')->name('reporteProductosMenosVendidos')->middleware('auth');
    Route::get('/balance', 'ReportesController@balance')->name('reporteBalance')->middleware('auth');
    Route::get('/facturasPorCobrar', 'ReportesController@facturasPorCobrar')->name('reporteFacturasPorCobrar')->middleware('auth');
    Route::get('/cuentasPorPagar', 'ReportesController@cuentasPorPagar')->name('reporteCuentasPorPagar')->middleware('auth');
});
