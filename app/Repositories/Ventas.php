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
            $productoActual = Producto::findOrFail($producto["id"]);
            if ($productoActual->categoria == ProductoTipo::UNITARIO) {
                $productoActual->stock = $productoActual->stock - $producto["cantidad"];
                $venta->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'costo' => $producto["costo"]]);
            } elseif ($productoActual->categoria == ProductoTipo::GRANEL) {
                if ($producto["unidad"] == ProductoTipo::GRAMOS) {
                    $productoActual->stock = $productoActual->stock - $producto["cantidad"];
                } else if ($producto["unidad"] == ProductoTipo::KILOGRAMOS) {
                    $productoActual->stock = $productoActual->stock - ($producto["cantidad"] * 1000);
                }
                $venta->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'unidad' => $producto["unidad"], 'costo' => $producto["costo"]]);
            }
            $productoActual->save();
            $costo += $producto["costo"];
        }
        $venta->valor = $costo;
        $venta->saldo = $costo;
        $venta->abonado = 0;
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
                if ($producto["unidad"] == ProductoTipo::GRAMOS) {
                    $cantidad = $cantidad;
                } else if ($producto["unidad"] == ProductoTipo::KILOGRAMOS) {
                    $cantidad = $cantidad * 1000;
                }
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
            } else if ($producto->categoria == ProductoTipo::GRANEL) {
                if ($producto->pivot->unidad == ProductoTipo::GRAMOS) {
                    $producto->stock = $producto->stock + $producto->pivot->cantidad;
                } else if ($producto->pivot->unidad == ProductoTipo::KILOGRAMOS) {
                    $producto->stock = $producto->stock + ($producto->pivot->cantidad * 1000);
                }
            }
            $producto->save();
        }
        $venta->delete();
    }

}
?>
