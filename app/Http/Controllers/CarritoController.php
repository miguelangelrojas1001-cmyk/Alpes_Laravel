<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index(Request $request)
    {
        $carrito = $request->session()->get('carrito', []);
        $total = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $carrito));

        return view('carrito.index', compact('carrito', 'total'));
    }

    /**
     * Add a simple product directly to cart (from catalog view).
     */
    public function agregar(Request $request)
    {
        $idproducto = $request->input('idproducto');
        $cantidad = max(1, (int)$request->input('cantidad', 1));

        if (!$idproducto) {
            return redirect()->route('catalogo.index');
        }

        $producto = Producto::where('idproducto', $idproducto)->where('disponible', 1)->first();

        if (!$producto) {
            return redirect()->route('catalogo.index');
        }

        $carrito = $request->session()->get('carrito', []);
        $clave = (string)$producto->idproducto;

        if (isset($carrito[$clave])) {
            $carrito[$clave]['cantidad'] += $cantidad;
        } else {
            $carrito[$clave] = [
                'idproducto'     => $producto->idproducto,
                'nombre'         => $producto->nombre,
                'precio'         => (float)$producto->precio,
                'precio_base'    => (float)$producto->precio,
                'costo_toppings' => 0,
                'sabores'        => '',
                'tamano'         => '',
                'observaciones'  => '',
                'cantidad'       => $cantidad,
                'es_yogurt'      => false,
                'es_ensalada'    => false,
                'es_malteada_sencilla' => false,
                'es_malteada_especial' => false,
                'es_canasta'     => false,
            ];
        }

        $request->session()->put('carrito', $carrito);
        $request->session()->flash('carrito_msg', $producto->nombre . ' agregado al carrito');

        $idcatalogo = $producto->idcatalogo;

        return redirect()->route('catalogo.index', array_filter(['idcatalogo' => $idcatalogo, 'agregado' => 1]));
    }

    /**
     * Update product quantity in cart.
     */
    public function actualizar(Request $request)
    {
        $clave = $request->input('idproducto');
        $cantidad = max(1, (int)$request->input('cantidad', 1));

        $carrito = $request->session()->get('carrito', []);

        if ($clave && isset($carrito[$clave])) {
            $carrito[$clave]['cantidad'] = $cantidad;
            $request->session()->put('carrito', $carrito);
        }

        return redirect()->route('carrito.index');
    }

    /**
     * Remove item from cart.
     */
    public function eliminar(Request $request, $id)
    {
        $clave = urldecode($id);
        $carrito = $request->session()->get('carrito', []);

        if ($clave && isset($carrito[$clave])) {
            unset($carrito[$clave]);
            $request->session()->put('carrito', $carrito);
        }

        return redirect()->route('carrito.index');
    }

    /**
     * Clear all items from cart.
     */
    public function vaciar(Request $request)
    {
        $request->session()->forget('carrito');
        return redirect()->route('carrito.index');
    }
}
