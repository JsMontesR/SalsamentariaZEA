<?php

namespace App\Repositories;

use App\Producto;
use App\ProductoTipo;
use App\Retiro;
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
        $costo = 0;
        if ($request->productos_retiro != null) {
            foreach ($request->productos_retiro as $producto) {
                $productoActual = Producto::findOrFail($producto["id"]);
                if ($productoActual->categoria == ProductoTipo::UNITARIO) {
                    $productoActual->stock = $productoActual->stock - $producto["cantidad"];
                    $subCosto = $producto["cantidad"] * $productoActual->costo;
                    $retiro->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'costo' => $subCosto]);
                } elseif ($productoActual->categoria == ProductoTipo::GRANEL) {
                    if ($producto["unidad"] == ProductoTipo::GRAMOS) {
                        $productoActual->stock = $productoActual->stock - $producto["cantidad"];
                        $subCosto = $producto["cantidad"] * ($productoActual->costo / 1000);
                    } else if ($producto["unidad"] == ProductoTipo::KILOGRAMOS) {
                        $productoActual->stock = $productoActual->stock - ($producto["cantidad"] * 1000);
                        $subCosto = $producto["cantidad"] * $productoActual->costo;
                    }
                    $retiro->productos()->attach($producto["id"], ['cantidad' => $producto["cantidad"], 'unidad' => $producto["unidad"], 'costo' => $subCosto]);
                }
                $productoActual->save();
                $costo += $subCosto;
            }
        }
        $retiro->costo = $costo;
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
     * @param Retiro $retiro
     */
    public function anular(Retiro $retiro)
    {
        foreach ($retiro->productos as $producto) {
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
        $retiro->delete();
    }


}

?>
