<?php

namespace App\Repositories;

use App\Entrada;
use App\Producto;
use App\ProductoTipo;
use App\Proveedor;
use Illuminate\Http\Request;

class Entradas
{

    /**
     * Registra una entrada.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entrada = new Entrada();
        $entrada->fechapago = $request->fechapago;
        $entrada->proveedor()->associate(Proveedor::findOrFail($request->proveedor_id));
        $entrada->empleado()->associate(auth()->user());
        $entrada->save();
        $costo = 0;
        foreach ($request->productos_entrada as $producto) {
            $productoActual = Producto::findOrFail($producto["id"]);
            if ($productoActual->categoria == ProductoTipo::UNITARIO) {
                $productoActual->stock = $productoActual->stock + $producto["cantidad"];
                $entrada->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'costo' => $producto["costo"]]);
            } elseif ($productoActual->categoria == ProductoTipo::GRANEL) {
                if ($producto["unidad"] == ProductoTipo::GRAMOS) {
                    $productoActual->stock = $productoActual->stock + $producto["cantidad"];
                } else if ($producto["unidad"] == ProductoTipo::KILOGRAMOS) {
                    $productoActual->stock = $productoActual->stock + ($producto["cantidad"] * 1000);
                }
                $entrada->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'unidad' => $producto["unidad"], 'costo' => $producto["costo"]]);
            }
            $productoActual->save();
            $costo += $producto["costo"];
        }
        $entrada->valor = $costo;
        $entrada->saldo = $costo;
        $entrada->save();
        $entrada->refresh();
        return $entrada;
    }


    /**
     * Verifica cual producto de una entrada no es descontable del inventario
     * @param Entrada $entrada
     * @return bool|string
     */
    public function getNoDescontable(Entrada $entrada)
    {
        foreach ($entrada->productos as $producto) {
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
     * Descuenta del inventario los productos de la entrada correspondiente
     * @param Entrada $entrada
     */
    public function anular(Entrada $entrada)
    {
        foreach ($entrada->productos as $producto) {
            if ($producto->categoria == ProductoTipo::UNITARIO) {
                $producto->stock = $producto->stock - $producto->pivot->cantidad;
            } else if ($producto->categoria == ProductoTipo::GRANEL) {
                if ($producto->pivot->unidad == ProductoTipo::GRAMOS) {
                    $producto->stock = $producto->stock - $producto->pivot->cantidad;
                } else if ($producto->pivot->unidad == ProductoTipo::KILOGRAMOS) {
                    $producto->stock = $producto->stock - ($producto->pivot->cantidad * 1000);
                }
            }
            $producto->save();
        }
        $entrada->delete();
    }

}
?>
