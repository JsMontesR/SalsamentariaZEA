<?php

namespace App\Repositories;

use App\Producto;
use App\ProductoTipo;
use App\Retiro;
use App\User;
use App\Venta;
use Illuminate\Http\Request;

class Retiros
{

    /**
     * Registra una retiro.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $retiro = new Retiro();
        $retiro->empleado()->associate(auth()->user());
        $retiro->save();
        $costoRetiro = 0;
        foreach ($request->productos_retiro as $producto) {
            $productoActual = Producto::findOrFail($producto["id"]);
            $retiro->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'costo' => $productoActual->costo]);
            if ($productoActual->categoria == ProductoTipo::UNITARIO) {
                $productoActual->stock = $productoActual->stock - $producto["cantidad"];
            } elseif ($productoActual->categoria == ProductoTipo::GRANEL) {
                $productoActual->stock = $productoActual->stock - ($producto["cantidad"] * 1000);
            }
            $costoRetiro += $productoActual->costo * $producto["cantidad"];
            $productoActual->save();
        }
        $retiro->costo = $costoRetiro;
        $retiro->valor = $request->valor;
        $retiro->save();
        return $retiro;
    }


    /**
     * Verifica cual producto de un retiro no es descontable del inventario
     * @param Request $request
     * @return bool|string
     */
    public function getNoDescontable(Request $request)
    {
        foreach ($request->productos_retiro as $producto) {
            $productoActual = Producto::findOrFail($producto["id"]);
            if ($producto["cantidad"] > $productoActual->stock) {
                return $productoActual->nombre . " (Con el id: " . $productoActual->id . ")";
            }
        }
        return null;
    }


    /**
     * Agrega al inventario los productos de la venta correspondiente
     * @param Retiro $retiro
     */
    public function anular(Retiro $retiro)
    {
        foreach ($retiro->productos as $producto) {
            if ($producto->categoria == ProductoTipo::UNITARIO) {
                $producto->stock = $producto->stock + $producto->pivot->cantidad;
            } elseif ($producto->categoria == ProductoTipo::GRANEL) {
                $producto->stock = $producto->stock + ($producto->pivot->cantidad * 1000);
            }
            $producto->save();
        }
        $retiro->delete();
    }


}

?>
