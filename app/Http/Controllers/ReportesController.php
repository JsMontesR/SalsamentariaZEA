<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Cierre;
use App\Entrada;
use App\Producto;
use App\ProductoTipo;
use App\Venta;
use Illuminate\Http\Request;
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
        } else {
            if ($request->fechaInicio != null) {
                $fechaInicio = $request->fechaInicio;
            }
            if ($request->fechaFin != null) {
                $fechaFin = $request->fechaFin;
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
    public function productosMenosVendidos()
    {
        return view("reportes.reporteProductosMenosVendidos");
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
