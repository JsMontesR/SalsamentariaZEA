<?php

namespace App\Repositories;

use App\Producto;
use App\ProductoTipo;
use App\User;
use App\Venta;
use Illuminate\Http\Request;

class Ventas
{

    /**
     * Registra una venta.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $venta = new Venta();
        $venta->fechapago = $request->fechapago;
        $venta->lugarentrega = $request->lugarentrega;
        $venta->cliente()->associate(User::findOrFail($request->cliente_id));
        $venta->empleado()->associate(auth()->user());
        $venta->save();
        $costo = 0;
        foreach ($request->productos_venta as $producto) {
            $venta->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'costo' => $producto["costo"]]);
            $productoActual = Producto::findOrFail($producto["id"]);
            if ($productoActual->categoria == ProductoTipo::UNITARIO) {
                $productoActual->stock = $productoActual->stock - $producto["cantidad"];
            } elseif ($productoActual->categoria == ProductoTipo::GRANEL) {
                $productoActual->stock = $productoActual->stock - ($producto["cantidad"] * 1000);
            }
            $productoActual->save();
            $costo += $producto["costo"];
        }
        $venta->valor = $costo;
        $venta->saldo = $costo;
        $venta->save();
        $venta->refresh();
        return $venta;
    }

    /**
     * Verifica cual producto de una venta no es descontable del inventario
     * @param Request $request
     * @return bool|string
     */
    public function getNoDescontable(Request $request)
    {
        foreach ($request->productos_venta as $producto) {
            $productoActual = Producto::findOrFail($producto["id"]);
            $cantidad = $producto["cantidad"];
            if ($productoActual->categoria == ProductoTipo::GRANEL) {
                $cantidad *= 1000;
            }
            if ($cantidad > $productoActual->stock) {
                return $productoActual->nombre . " (Con el id: " . $productoActual->id . ") faltan " . ($cantidad - $productoActual->stock) . " " . ($productoActual->categoria == ProductoTipo::GRANEL ? "gramos " : "unidades ");
            }
        }
        return null;
    }


    /**
     * Agrega al inventario los productos de la venta correspondiente
     * @param Venta $venta
     */
    public function anular(Venta $venta)
    {
        foreach ($venta->productos as $producto) {
            if ($producto->categoria == ProductoTipo::UNITARIO) {
                $producto->stock = $producto->stock + $producto->pivot->cantidad;
            } elseif ($producto->categoria == ProductoTipo::GRANEL) {
                $producto->stock = $producto->stock + ($producto->pivot->cantidad * 1000);
            }
            $producto->save();
        }
        $venta->delete();
    }

}

?>
