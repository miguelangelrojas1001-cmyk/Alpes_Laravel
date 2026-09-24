<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClienteDashboardController extends Controller
{
    /**
     * Display client orders dashboard.
     * Security: Always derives client record from Auth::id(). Never accepts idcliente from request.
     */
    public function index(Request $request)
    {
        $idpersona = Auth::id();
        $cliente   = Cliente::where('idpersona', $idpersona)->first();

        $pedidos           = [];
        $detallesPorPedido = [];
        $facturasPorPedido = [];
        $enviosPorPedido   = [];

        if ($cliente) {
            $idcliente = $cliente->idcliente;

            // Consultar todos los pedidos del cliente ordenados por fecha descendente
            $pedidosList = DB::table('pedido')
                ->where('idcliente', $idcliente)
                ->orderBy('fecha', 'desc')
                ->get();

            $pedidos = $pedidosList->map(fn($p) => (array)$p)->toArray();

            if (!empty($pedidos)) {
                $ids = array_column($pedidos, 'idpedido');

                // Consultar detalles de pedido con el nombre del producto
                $detalles = DB::table('detalle_pedido as dp')
                    ->leftJoin('producto as p', 'dp.idproducto', '=', 'p.idproducto')
                    ->whereIn('dp.idpedido', $ids)
                    ->select('dp.*', 'p.nombre as nombre_producto')
                    ->get();

                foreach ($detalles as $d) {
                    $detallesPorPedido[$d->idpedido][] = (array)$d;
                }

                // Consultar facturas asociadas
                $facturas = DB::table('factura')
                    ->whereIn('idpedido', $ids)
                    ->get();

                foreach ($facturas as $f) {
                    $facturasPorPedido[$f->idpedido] = (array)$f;
                }

                // Consultar envíos y direcciones asociadas
                $envios = DB::table('envios as e')
                    ->leftJoin('direccion as d', 'e.iddireccion', '=', 'd.iddireccion')
                    ->whereIn('e.idpedido', $ids)
                    ->select('e.*', 'd.descripcion as direccion_completa')
                    ->get();

                foreach ($envios as $e) {
                    $enviosPorPedido[$e->idpedido] = (array)$e;
                }
            }
        }

        return view('dashboard.cliente.pedidos', compact('pedidos', 'detallesPorPedido', 'facturasPorPedido', 'enviosPorPedido'));
    }
}
