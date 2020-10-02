<?php

namespace App\Repositories;

use App\Entrada;
use App\Ingreso;
use App\Producto;
use App\ProductoTipo;
use App\Proveedor;
use Illuminate\Http\Request;

class Ingresos
{

    /**
     * Registra un ingreso.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ingreso = new Ingreso();
        $ingreso->empleado()->associate(auth()->user());
        $ingreso->save();
        $costoIngreso = 0;
        foreach ($request->productos_ingreso as $producto) {
            $productoActual = Producto::findOrFail($producto["id"]);
            $ingreso->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'costo' => $productoActual->costo]);
            if ($productoActual->categoria == ProductoTipo::UNITARIO) {
                $productoActual->stock = $productoActual->stock + $producto["cantidad"];
            } elseif ($productoActual->categoria == ProductoTipo::GRANEL) {
                $productoActual->stock = $productoActual->stock + ($producto["cantidad"] * 1000);
            }
            $costoIngreso += $productoActual->costo * $producto["cantidad"];
            $productoActual->save();
        }
        $ingreso->costo = $costoIngreso;
        $ingreso->valor = $request->valor;
        $ingreso->save();
        return $ingreso;
    }


    /**
     * Verifica cual producto de un ingreso no es descontable del inventario
     * @param Entrada $ingreso
     * @return bool|string
     */
    public function getNoDescontable(Ingreso $ingreso)
    {
        foreach ($ingreso->productos as $producto) {
            if ($producto->stock < $producto->pivot->cantidad) {
                return $producto->nombre . " (Con el id: " . $producto->id . ")";
            }
        }
        return null;
    }


    /**
     * Descuenta del inventario los productos del ingreso correspondiente
     * @param Entrada $ingreso
     * @throws \Exception
     */
    public function anular(Ingreso $ingreso)
    {
        foreach ($ingreso->productos as $producto) {
            if ($producto->categoria == ProductoTipo::UNITARIO) {
                $producto->stock = $producto->stock - $producto->pivot->cantidad;
            } elseif ($producto->categoria == ProductoTipo::GRANEL) {
                $producto->stock = $producto->stock - ($producto->pivot->cantidad * 1000);
            }
            $producto->save();
        }
        $ingreso->delete();
    }

}

?>
