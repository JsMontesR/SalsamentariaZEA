<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Cierre;
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

            foreach ($registros as $venta) {
                $totalVendido += $venta->valor;
                $totalSaldo += $venta->saldo;
                $totalAbonado += $venta->abonado;
            }

            $totalVendido = "$ " .number_format($totalVendido, 0);
            $totalSaldo = "$ " .number_format($totalSaldo, 0);
            $totalAbonado = "$ " .number_format($totalAbonado, 0);

            $fechaActual = now();

            $pdf = \PDF::loadView("print.reportes.reporteVentas", compact('registros', 'fechaInicio', 'fechaFin',
                'totalVendido', 'totalAbonado', 'totalSaldo', 'fechaActual'));
            return $pdf->stream('reporteVentas.pdf');;
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
}
