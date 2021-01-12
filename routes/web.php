<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/inicio', 'HomeController@index');
Route::get('/index', 'HomeController@index');
Route::get('/home', 'HomeController@index');

Route::get('/proveedores', 'ProveedorController@index')->name('proveedores')->middleware('auth');
Route::get('/tiposproductos', 'ProductoTipoController@index')->name('tiposproductos')->middleware('auth');
Route::get('/tiposservicios', 'TipoServicioController@index')->name('tiposservicios')->middleware('auth')->middleware('onlyadmin');
Route::get('/servicios', 'ServicioController@index')->name('servicios')->middleware('auth')->middleware('onlyadmin');
Route::get('/productos', 'ProductoController@index')->name('productos')->middleware('auth');
Route::get('/clientes', 'ClienteController@index')->name('clientes')->middleware('auth');
Route::get('/empleados', 'EmpleadoController@index')->name('empleados')->middleware('auth')->middleware('onlyadmin');
Route::get('/nominas', 'NominaController@index')->name('nominas')->middleware('auth')->middleware('onlyadmin');
Route::get('/retiros', 'RetiroController@index')->name('retiros')->middleware('auth')->middleware('onlyadmin');
Route::get('/ingresos', 'IngresoController@index')->name('ingresos')->middleware('auth')->middleware('onlyadmin');
Route::get('/entradas', 'EntradaController@index')->name('entradas')->middleware('auth');
Route::get('/ventas', 'VentaController@index')->name('ventas')->middleware('auth');
Route::get('/movimientos', 'MovimientoController@index')->name('movimientos')->middleware('auth')->middleware('onlyadmin');

Route::patch('/notificacionesmarcarleida/{id}', 'NotificacionController@marcarNotificacionComoLeida')->name('marcarNotificacionComoLeida')->middleware('auth');
Route::get('/notificaciones', 'NotificacionController@index')->name('notificaciones')->middleware('auth');

/*
 * Impresiones
 */

Route::post('/imprimirventa', 'VentaController@imprimirfactura')->name('imprimirfactura')->middleware('auth');
Route::post('/imprimirservicio', 'ServicioController@imprimircomprobante')->name('imprimircomprobanteservicio')->middleware('auth');
Route::post('/imprimirentrada', 'EntradaController@imprimircomprobante')->name('imprimircomprobanteentrada')->middleware('auth');
Route::post('/imprimirnomina', 'NominaController@imprimircomprobante')->name('imprimircomprobantenomina')->middleware('auth')->middleware('onlyadmin');
Route::post('/imprimirpos', 'MovimientoController@imprimir')->name('imprimirpos')->middleware('auth');

/*
 * OPERACIONES CRUD Y CORE DEL NEGOCIO
 */

Route::prefix('api')->group(function () {

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
        Route::post('modificar', 'VentaController@update')->middleware('auth');
        Route::post('anular', 'VentaController@anular')->middleware('auth');
    });

    // CRUD Proveedores
    Route::prefix('proveedores')->group(function () {
        Route::get('listar', 'ProveedorController@list')->middleware('auth');
        Route::post('crear', 'ProveedorController@store')->middleware('auth');
        Route::post('modificar', 'ProveedorController@update')->middleware('auth');
        Route::post('borrar', 'ProveedorController@destroy')->middleware('auth');
    });

    // Procesamiento servicios
    Route::prefix('servicios')->group(function () {
        Route::get('listar', 'ServicioController@list')->middleware('auth');
        Route::get('{id}/pagos', 'ServicioController@pagos')->middleware('auth');
        Route::post('crear', 'ServicioController@store')->middleware('auth');
        Route::post('crearypagar', 'ServicioController@storePay')->middleware('auth');
        Route::post('modificar', 'ServicioController@update')->middleware('auth');
        Route::post('anular', 'ServicioController@anular')->middleware('auth');
    });

    // CRUD Tipos de productos
    Route::prefix('tiposproductos')->group(function () {
        Route::get('listar', 'ProductoTipoController@list');
        Route::post('crear', 'ProductoTipoController@store');
        Route::post('modificar', 'ProductoTipoController@update');
        Route::post('borrar', 'ProductoTipoController@destroy');
    });

    // CRUD productos
    Route::prefix('productos')->group(function () {
        Route::get('listar', 'ProductoController@list')->middleware('auth');
        Route::post('crear', 'ProductoController@store')->middleware('auth');
        Route::post('modificar', 'ProductoController@update')->middleware('auth');
        Route::post('borrar', 'ProductoController@destroy')->middleware('auth');
    });

    // CRUD empleados
    Route::prefix('empleados')->group(function () {
        Route::get('listar', 'EmpleadoController@list')->middleware('auth')->middleware('onlyadmin');
        Route::post('crear', 'EmpleadoController@store')->middleware('auth')->middleware('onlyadmin');
        Route::post('modificar', 'EmpleadoController@update')->middleware('auth')->middleware('onlyadmin');
        Route::post('borrar', 'EmpleadoController@destroy')->middleware('auth')->middleware('onlyadmin');
    });

    // CRUD Tipos de servicios
    Route::prefix('tiposervicios')->group(function () {
        Route::get('listar', 'TipoServicioController@list')->middleware('auth');
        Route::post('crear', 'TipoServicioController@store')->middleware('auth')->middleware('onlyadmin');
        Route::post('modificar', 'TipoServicioController@update')->middleware('auth')->middleware('onlyadmin');
        Route::post('borrar', 'TipoServicioController@destroy')->middleware('auth')->middleware('onlyadmin');
    });

    // CRUD clientes
    Route::prefix('clientes')->group(function () {
        Route::get('listar', 'ClienteController@list')->middleware('auth');
        Route::post('crear', 'ClienteController@store')->middleware('auth');
        Route::post('modificar', 'ClienteController@update')->middleware('auth');
        Route::post('borrar', 'ClienteController@destroy')->middleware('auth');
    });


    // Procesamiento nóminas
    Route::prefix('nominas')->group(function () {
        Route::get('listar', 'NominaController@list')->middleware('auth')->middleware('onlyadmin');
        Route::get('{id}/pagos', 'NominaController@pagos')->middleware('auth')->middleware('onlyadmin');
        Route::post('crear', 'NominaController@store')->middleware('auth')->middleware('onlyadmin');
        Route::post('crearypagar', 'NominaController@storePay')->middleware('auth')->middleware('onlyadmin');
        Route::post('modificar', 'NominaController@update')->middleware('auth')->middleware('onlyadmin');
        Route::post('anular', 'NominaController@anular')->middleware('auth')->middleware('onlyadmin');
    });

    // Procesamiento de retiros
    Route::prefix('retiros')->group(function () {
        Route::get('listar', 'RetiroController@list')->middleware('auth')->middleware('onlyadmin');
        Route::post('crear', 'RetiroController@store')->middleware('auth')->middleware('onlyadmin');
        Route::post('anular', 'RetiroController@anular')->middleware('auth')->middleware('onlyadmin');
    });

    // Procesamiento de ingresos
    Route::prefix('ingresos')->group(function () {
        Route::get('listar', 'IngresoController@list')->middleware('auth')->middleware('onlyadmin');
        Route::post('crear', 'IngresoController@store')->middleware('auth')->middleware('onlyadmin');
        Route::post('anular', 'IngresoController@anular')->middleware('auth')->middleware('onlyadmin');
    });

    // Procesamiento de movimientos
    Route::prefix('movimientos')->group(function () {
        Route::get('listar', 'MovimientoController@list')->middleware('auth')->middleware('onlyadmin');
        Route::post('generarPago', 'MovimientoController@generarPago')->middleware('auth');
        Route::post('anularPago', 'MovimientoController@anularPago')->middleware('auth');
        Route::post('generarCobro', 'MovimientoController@generarCobro')->middleware('auth');
        Route::post('anularCobro', 'MovimientoController@anularCobro')->middleware('auth');
    });

    /*
     * REPORTES
     */

    Route::prefix('reportes')->group(function () {
        Route::get('/listarVentas', 'ReportesController@listarVentas')->name('listarventas')->middleware('auth');
        Route::get('/listarPartesBalance', 'ReportesController@listarPartesBalance')->name('listarpartesbalance')->middleware('auth');
        Route::get('/listarProductos', 'ReportesController@listarProductos')->name('listarproductos')->middleware('auth');
        Route::get('/listarFacturasPorCobrar', 'ReportesController@listarFacturasPorCobrar')->name('listarfacturasporcobrar')->middleware('auth');
        Route::get('/listarCuentasPorPagar', 'ReportesController@listarCuentasPorPagar')->name('listarcuentasporpagar')->middleware('auth');
        Route::get('/listarClientesQueMasCompran', 'ReportesController@listarClientesQueMasCompran')->name('listarclientesquemascompran')->middleware('auth');
        Route::get('/listarProductosMasVendidos', 'ReportesController@listarProductosMasVendidos')->name('listarproductosmasvendidos')->middleware('auth');
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
    Route::get('/productosMasVendidos', 'ReportesController@productosMasVendidos')->name('reporteProductosMasVendidos');
    Route::get('/clientesQueMasCompran', 'ReportesController@clientesQueMasCompran')->name('reporteClientesQueMasCompran');
    Route::get('/balance', 'ReportesController@reporteBalance')->name('reporteBalance')->middleware('auth');
    Route::get('/facturasPorCobrar', 'ReportesController@facturasPorCobrar')->name('reporteFacturasPorCobrar')->middleware('auth');
    Route::get('/cuentasPorPagar', 'ReportesController@cuentasPorPagar')->name('reporteCuentasPorPagar')->middleware('auth');
});
