<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Cierre;
use App\Entrada;
use App\Ingreso;
use App\Movimiento;
use App\Nomina;
use App\Producto;
use App\ProductoTipo;
use App\Retiro;
use App\Servicio;
use App\Venta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;

class ReportesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dineroEnCaja = "$ " . number_format(Caja::findOrFail(1)->saldo, 0);
        return view("reportes", compact('dineroEnCaja'));
    }

    /**
     * Return list of the resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function listarVentas(Request $request)
    {
        $query = Venta::query()->select('ventas.*')->with(['empleado', 'cliente', 'productos']);
        $fechaInicio = null;
        $fechaFin = null;
        $cierre_id = $request->cierre_id;

        if ($cierre_id != null) {
            $cierre = Cierre::findOrFail($cierre_id);
            $cierreAnterior = $cierre->cierreAnterior;
            $fechaInicio = $cierreAnterior == null ? null : $cierreAnterior->created_at->toDateTimeString();
            $fechaFin = $cierre->created_at->toDateTimeString();
        } else if ($request->fechaInicio != null || $request->fechaFin != null) {
            if ($request->fechaInicio != null) {
                $fechaInicio = $request->fechaInicio;
            }
            if ($request->fechaFin != null) {
                $fechaFin = $request->fechaFin;
            }
        } else {
            if ($request->ajax()) {
                return datatables(collect([]))->toJson();
            } else {
                return back()->with('error', 'Debe seleccionar algún filtro');
            }
        }

        if ($fechaInicio != null) {
            $query = $query->whereDate('ventas.created_at', '>=', $fechaInicio);
        }

        if ($fechaFin != null) {
            $query = $query->whereDate('ventas.created_at', '<=', $fechaFin);
        }

        if ($request->ajax()) {
            return datatables($query)->toJson();
        } else {

            $registros = $query->get();
            $totalVendido = 0;
            $totalSaldo = 0;
            $totalAbonado = 0;
            $totalCosto = 0;
            $totalUtilidades = 0;
            $utilidadesDevengadas = 0;

            foreach ($registros as $venta) {
                $totalVendido += $venta->valor;
                $totalSaldo += $venta->saldo;
                $totalAbonado += $venta->abonado;
                $totalCosto += $venta->costo;
                $totalUtilidades += $venta->utilidad;
                $utilidadesDevengadas += $venta->abonado > $venta->costo ? $venta->abonado - $venta->costo : 0;
            }

            $totalVendido = "$ " . number_format($totalVendido, 0);
            $totalSaldo = "$ " . number_format($totalSaldo, 0);
            $totalAbonado = "$ " . number_format($totalAbonado, 0);
            $totalCosto = "$ " . number_format($totalCosto, 0);
            $totalUtilidades = "$ " . number_format($totalUtilidades, 0);
            $utilidadesDevengadas = "$ " . number_format($utilidadesDevengadas, 0);

            $fechaActual = now();

            $pdf = \PDF::loadView("print.reportes.reporteVentas", compact('registros', 'fechaInicio', 'fechaFin',
                'totalVendido', 'totalAbonado', 'totalSaldo', 'fechaActual', 'totalCosto', 'totalUtilidades', 'utilidadesDevengadas'));
            return $pdf->stream('reporteVentas.pdf');;
        }
    }

    /**
     * Return list of the resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function listarPartesBalance(Request $request)
    {
        $fechaInicio = null;
        $fechaFin = null;
        $cierre_id = $request->cierre_id;

        if ($cierre_id != null) {
            $cierre = Cierre::findOrFail($cierre_id);
            $cierreAnterior = $cierre->cierreAnterior;
            $fechaInicio = $cierreAnterior == null ? null : $cierreAnterior->created_at->toDateTimeString();
            $fechaFin = $cierre->created_at->toDateTimeString();
        } else if ($request->fechaInicio != null || $request->fechaFin != null) {
            if ($request->fechaInicio != null) {
                $fechaInicio = $request->fechaInicio;
            }
            if ($request->fechaFin != null) {
                $fechaFin = $request->fechaFin;
            }
        } else {
            if ($request->ajax()) {
                return datatables(collect([]))->toJson();
            } else {
                return back()->with('error', 'Debe seleccionar algún filtro');
            }
        }

        $ventas = Venta::query()->select('ventas.id', DB::raw('"Venta" as "concepto"'), DB::raw('"Ingreso" as "naturaleza"'), 'ventas.created_at')->with(['movimientos' => function ($query) use ($fechaInicio, $fechaFin) {
            $this->filtrarMovimientos($fechaInicio, $fechaFin, $query, Movimiento::INGRESO);
        }]);

        $servicios = Servicio::query()->select('servicios.id', DB::raw('"Servicio" as "concepto"'), DB::raw('"Egreso" as "naturaleza"'), 'servicios.created_at')->with(['movimientos' => function ($query) use ($fechaInicio, $fechaFin) {
            $this->filtrarMovimientos($fechaInicio, $fechaFin, $query, Movimiento::EGRESO);
        }]);

        $entradas = Entrada::query()->select('entradas.id', DB::raw('"Entrada" as "concepto"'), DB::raw('"Egreso" as "naturaleza"'), 'entradas.created_at')->with(['movimientos' => function ($query) use ($fechaInicio, $fechaFin) {
            $this->filtrarMovimientos($fechaInicio, $fechaFin, $query, Movimiento::EGRESO);
        }]);

        $nominas = Nomina::query()->select('nominas.id', DB::raw('"Nómina" as "concepto"'), DB::raw('"Egreso" as "naturaleza"'), 'nominas.created_at')->with(['movimientos' => function ($query) use ($fechaInicio, $fechaFin) {
            $this->filtrarMovimientos($fechaInicio, $fechaFin, $query, Movimiento::EGRESO);
        }]);

        $retiros = Retiro::query()->select('retiros.id', DB::raw('"Retiro" as "concepto"'), DB::raw('"Egreso" as "naturaleza"'), 'retiros.created_at')->with(['movimientos' => function ($query) use ($fechaInicio, $fechaFin) {
            $this->filtrarMovimientos($fechaInicio, $fechaFin, $query, Movimiento::EGRESO);
        }]);

        $ingresos = Ingreso::query()->select('ingresos.id', DB::raw('"Ingreso" as "concepto"'), DB::raw('"Ingreso" as "naturaleza"'), 'ingresos.created_at')->with(['movimientos' => function ($query) use ($fechaInicio, $fechaFin) {
            $this->filtrarMovimientos($fechaInicio, $fechaFin, $query, Movimiento::INGRESO);
        }]);

        if ($fechaInicio != null) {
            $ventas = $ventas->whereDate('ventas.created_at', '>=', $fechaInicio);
            $servicios = $servicios->whereDate('servicios.created_at', '>=', $fechaInicio);
            $entradas = $entradas->whereDate('entradas.created_at', '>=', $fechaInicio);
            $nominas = $nominas->whereDate('nominas.created_at', '>=', $fechaInicio);
            $retiros = $retiros->whereDate('retiros.created_at', '>=', $fechaInicio);
            $ingresos = $ingresos->whereDate('ingresos.created_at', '>=', $fechaInicio);
        }

        if ($fechaFin != null) {
            $ventas = $ventas->whereDate('ventas.created_at', '<=', $fechaFin);
            $servicios = $servicios->whereDate('servicios.created_at', '<=', $fechaFin);
            $entradas = $entradas->whereDate('entradas.created_at', '<=', $fechaFin);
            $nominas = $nominas->whereDate('nominas.created_at', '<=', $fechaFin);
            $retiros = $retiros->whereDate('retiros.created_at', '<=', $fechaFin);
            $ingresos = $ingresos->whereDate('ingresos.created_at', '<=', $fechaFin);
        }

        $ventas = $ventas->get();
        $servicios = $servicios->get();
        $entradas = $entradas->get();
        $nominas = $nominas->get();
        $retiros = $retiros->get();
        $ingresos = $ingresos->get();

        foreach ($ventas as $venta) {
            $total = 0;
            foreach ($venta->movimientos as $movimiento)
                $total += $movimiento->total;
            $venta->total = $total;
        }

        foreach ($servicios as $servicio) {
            $total = 0;
            foreach ($servicio->movimientos as $movimiento)
                $total += $movimiento->total;
            $servicio->total = (-1) * $total;
        }

        foreach ($entradas as $entrada) {
            $total = 0;
            foreach ($entrada->movimientos as $movimiento)
                $total += $movimiento->total;
            $entrada->total = (-1) * $total;
        }

        foreach ($nominas as $nomina) {
            $total = 0;
            foreach ($nomina->movimientos as $movimiento)
                $total += $movimiento->total;
            $nomina->total = (-1) * $total;
        }

        foreach ($retiros as $retiro) {
            $total = 0;
            foreach ($retiro->movimientos as $movimiento)
                $total += $movimiento->total;
            $retiro->total = (-1) * $total;
        }

        foreach ($ingresos as $ingreso) {
            $total = 0;
            foreach ($ingreso->movimientos as $movimiento)
                $total += $movimiento->total;
            $ingreso->total = $total;
        }

        $registros = collect([]);
        $registros = $registros->merge($ventas)->merge($servicios)->merge($entradas)->merge($nominas)->merge($retiros)->merge($ingresos);
        Log::info($registros);

        if ($request->ajax()) {
            return datatables($registros)->toJson();
        } else {

            $ingresosQ = Movimiento::query()->where('tipo', '=', Movimiento::INGRESO);
            $egresosQ = Movimiento::query()->where('tipo', '=', Movimiento::EGRESO);

            if ($fechaFin != null) {
                $ingresosQ = $ingresosQ->whereDate('movimientos.created_at', '<=', $fechaFin);
                $egresosQ = $egresosQ->whereDate('movimientos.created_at', '<=', $fechaFin);
            }

            $valorEnCaja = $ingresosQ->sum('parteEfectiva') - $egresosQ->sum('parteEfectiva');

            $ingresosOrdinarios = 0;
            $egresosOrdinarios = 0;
            $ingresosExtraordinarios = 0;
            $egresosExtraordinarios = 0;

            foreach ($registros as $item) {
                if ($item->naturaleza == Movimiento::INGRESO) {
                    if ($item->concepto == "Venta") {
                        $ingresosOrdinarios += $item->total;
                    } else if ($item->concepto == "Ingreso") {
                        $ingresosExtraordinarios += $item->total;
                    }
                } else if ($item->naturaleza == Movimiento::EGRESO) {
                    if ($item->concepto == "Entrada" || $item->concepto == "Servicio" || $item->concepto == "Nómina") {
                        $egresosOrdinarios += $item->total;
                    } else if ($item->concepto == "Retiro") {
                        $egresosExtraordinarios += $item->total;
                    }
                }
            }

            $balance = $ingresosOrdinarios + $egresosOrdinarios + $ingresosExtraordinarios + $egresosExtraordinarios;
            $fechaActual = now();

            $pdf = \PDF::loadView("print.reportes.reporteBalance", compact('registros', 'fechaInicio', 'fechaFin',
                'ingresosOrdinarios', 'ingresosExtraordinarios', 'egresosOrdinarios', 'egresosExtraordinarios', 'valorEnCaja','balance', 'fechaActual'));
            return $pdf->stream('balance.pdf');;
        }
    }

    public function filtrarMovimientos($fechaInicio, $fechaFin, $query, $tipo)
    {
        $query->where('movimientos.tipo', $tipo);
        if ($fechaInicio != null) {
            $query->whereDate('movimientos.created_at', '>=', $fechaInicio);
        }
        if ($fechaFin != null) {
            $query->whereDate('movimientos.created_at', '<=', $fechaFin);
        }
    }

    /**
     * Return list of the resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function listarProductos(Request $request)
    {
        $query = Producto::query()->select('productos.*')->with('tipo');

        if ($request->ajax()) {
            return datatables($query)->toJson();
        } else {

            $registros = $query->get();
            $costoTotal = 0;
            $precioTotal = 0;
            $utilidadTotal = 0;

            foreach ($registros as $producto) {
                if ($producto->categoria == ProductoTipo::GRANEL) {
                    $costoTotal += ($producto->costo / 1000) * $producto->stock;
                    $precioTotal += ($producto->precio / 1000) * $producto->stock;
                } else if ($producto->categoria == ProductoTipo::UNITARIO) {
                    $costoTotal += ($producto->costo) * $producto->stock;
                    $precioTotal += ($producto->precio) * $producto->stock;
                }
                $utilidadTotal += $precioTotal - $costoTotal;
            }

            $costoTotal = "$ " . number_format($costoTotal, 0);
            $precioTotal = "$ " . number_format($precioTotal, 0);
            $utilidadTotal = "$ " . number_format($utilidadTotal, 0);

            $fechaActual = now();

            $pdf = \PDF::loadView("print.reportes.reporteInventario", compact('registros', 'fechaActual', 'costoTotal', 'precioTotal', 'utilidadTotal'));
            return $pdf->stream('reporteVentas.pdf');;
        }
    }

    /**
     * Return list of the resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function listarFacturasPorCobrar(Request $request)
    {
        $query = Venta::query()->select('ventas.*')->with('cliente', 'empleado')->where('fechapagado', null);

        if ($request->ajax()) {
            return datatables($query)->toJson();
        }
    }

    /**
     * Return list of the resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function listarCuentasPorPagar(Request $request)
    {
        $query = Entrada::query()->select('entradas.*')->with('proveedor', 'empleado')->where('fechapagado', null);

        if ($request->ajax()) {
            return datatables($query)->toJson();
        }
    }

    /**
     * Return list of the resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function listarClientesQueMasCompran(Request $request)
    {
        $query = DB::table('ventas')->select(DB::raw('users.id'), DB::raw('users.name'), DB::raw('users.di'), DB::raw('COUNT(users.id) AS "count"'))
            ->join('users', 'ventas.cliente_id', '=', 'users.id')->groupBy(DB::raw('users.id'));

        if ($request->ajax()) {
            return datatables($query)->toJson();
        }
    }

    /**
     * Return list of the resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function listarProductosMasVendidos(Request $request)
    {
        $query = DB::select('SELECT w.id, w.nombre,w.categoria,SUM(w.total) as "total" FROM (
         SELECT productos.id, productos.nombre, productos.categoria, SUM(producto_venta.cantidad) AS "total" from productos
         JOIN producto_venta ON productos.id = producto_venta.producto_id WHERE producto_venta.unidad = "gramos" OR producto_venta.unidad IS NULL GROUP BY (productos.id)
          UNION SELECT productos.id, productos.nombre, productos.categoria, SUM(producto_venta.cantidad*1000) AS "total" from productos JOIN
          producto_venta ON productos.id = producto_venta.producto_id WHERE producto_venta.unidad = "kilogramos" GROUP BY
          (productos.id) ) w GROUP BY w.id, w.nombre, w.categoria');

        if ($request->ajax()) {
            return datatables($query)->toJson();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reporteVentas()
    {
        return view("reportes.reporteVentas");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reporteInventario()
    {
        return view("reportes.reporteInventario");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productosMasVendidos()
    {
        return view("reportes.reporteProductosMasVendidos");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clientesQueMasCompran()
    {
        return view("reportes.reporteClientesQueMasCompran");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reporteBalance()
    {
        return view("reportes.reporteBalance");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function facturasPorCobrar()
    {
        return view("reportes.reporteFacturasPorCobrar");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cuentasPorPagar()
    {
        return view("reportes.reporteCuentasPorPagar");
    }

}
