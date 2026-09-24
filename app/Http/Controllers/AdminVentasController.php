<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminVentasController extends Controller
{
    /**
     * Display admin sales & orders dashboard with pagination, filters and KPIs.
     */
    public function index(Request $request)
    {
        $porPagina    = 20;
        $paginaActual = max(1, (int)$request->get('pagina', 1));
        $offset       = ($paginaActual - 1) * $porPagina;

        $filtroEstado = $request->get('estado', '');

        // Query Base de Pedidos
        $queryPedidos = DB::table('pedido as p')
            ->leftJoin('cliente as c', 'p.idcliente', '=', 'c.idcliente')
            ->leftJoin('persona as pe', 'c.idpersona', '=', 'pe.idpersona')
            ->leftJoin('envios as e', 'e.idpedido', '=', 'p.idpedido')
            ->leftJoin('domiciliario as d', 'e.iddomiciliario', '=', 'd.iddomiciliario')
            ->leftJoin('persona as pd', 'd.idpersona', '=', 'pd.idpersona');

        if ($filtroEstado) {
            $queryPedidos->where('p.estado', $filtroEstado);
        }

        $totalPedidos = $queryPedidos->count('p.idpedido');
        $totalPaginas = max(1, (int)ceil($totalPedidos / $porPagina));

        $pedidosList = $queryPedidos
            ->select(
                'p.idpedido', 'p.total', 'p.fecha', 'p.estado',
                'pe.nombre as cliente',
                'pd.nombre as domiciliario'
            )
            ->orderBy('p.fecha', 'desc')
            ->offset($offset)
            ->limit($porPagina)
            ->get();

        $pedidos = $pedidosList->map(fn($item) => (array)$item)->toArray();

        // Cargar detalles, facturas y envíos para cada pedido
        $detallesPorPedido = [];
        $facturasPorPedido = [];
        $enviosPorPedido   = [];

        if (!empty($pedidos)) {
            $ids = array_column($pedidos, 'idpedido');

            $detalles = DB::table('detalle_pedido as dp')
                ->leftJoin('producto as pr', 'dp.idproducto', '=', 'pr.idproducto')
                ->whereIn('dp.idpedido', $ids)
                ->select('dp.*', 'pr.nombre as nombre_producto')
                ->get();

            foreach ($detalles as $det) {
                $detallesPorPedido[$det->idpedido][] = (array)$det;
            }

            $facturas = DB::table('factura')->whereIn('idpedido', $ids)->get();
            foreach ($facturas as $fac) {
                $facturasPorPedido[$fac->idpedido] = (array)$fac;
            }

            $envios = DB::table('envios as e')
                ->leftJoin('direccion as dir', 'e.iddireccion', '=', 'dir.iddireccion')
                ->whereIn('e.idpedido', $ids)
                ->select('e.*', 'dir.descripcion as direccion_completa')
                ->get();

            foreach ($envios as $env) {
                $enviosPorPedido[$env->idpedido] = (array)$env;
            }
        }

        // Totales y KPIs agregados
        $kpiQuery = DB::table('pedido')
            ->select(
                DB::raw('COUNT(*) as total_pedidos'),
                DB::raw('COALESCE(SUM(total), 0) as ingresos_totales'),
                DB::raw("COALESCE(SUM(CASE WHEN estado='completado' THEN total ELSE 0 END), 0) as ingresos_completados")
            )->first();

        $totales = (array)$kpiQuery;

        return view('dashboard.admin.ventas', compact(
            'pedidos',
            'detallesPorPedido',
            'facturasPorPedido',
            'enviosPorPedido',
            'totalPedidos',
            'totalPaginas',
            'paginaActual',
            'filtroEstado',
            'totales'
        ));
    }

    /**
     * Update order state from admin dashboard and handle driver assignment/update.
     */
    public function actualizarEstado(Request $request)
    {
        $idpedido = (int)$request->input('idpedido');
        $estado   = $request->input('estado');

        if ($idpedido <= 0 || !$estado) {
            return redirect()->route('admin.ventas.index');
        }

        // Actualizar estado en tabla pedido
        DB::table('pedido')
            ->where('idpedido', $idpedido)
            ->update(['estado' => $estado]);

        // Asignar o actualizar domiciliario si el estado es activo ('pendiente', 'en proceso')
        if (in_array($estado, ['pendiente', 'en proceso'])) {
            try {
                $this->asignarOActualizarDomiciliario($idpedido);
            } catch (\Exception $e) {
                Log::warning("[AdminVentas] Fallo al asignar domiciliario a pedido {$idpedido}: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.ventas.index');
    }

    /**
     * Replicate Domiciliario::asignarOActualizar from PHP original.
     */
    private function asignarOActualizarDomiciliario(int $idpedido): void
    {
        $pedido = DB::table('pedido')->where('idpedido', $idpedido)->first();
        if (!$pedido) {
            return;
        }

        $idcliente = (int)$pedido->idcliente;

        // Verificar si ya tiene envío asignado
        $envioExistente = DB::table('envios')->where('idpedido', $idpedido)->first();

        if ($envioExistente) {
            // Ya tiene envío — solo actualizar estado en envios a 'pendiente'
            DB::table('envios')
                ->where('idpedido', $idpedido)
                ->update(['estado' => 'pendiente']);
            return;
        }

        // No tiene envío — buscar última dirección del cliente
        $dir = DB::table('direccion as d')
            ->join('pedido as p', 'p.idcliente', '=', DB::raw($idcliente))
            ->orderBy('d.iddireccion', 'desc')
            ->select('d.iddireccion')
            ->first();

        $iddireccion = $dir ? (int)$dir->iddireccion : null;

        // Buscar el domiciliario con menos pedidos activos
        $dom = DB::table('domiciliario as d')
            ->leftJoin('envios as e', function ($join) {
                $join->on('e.iddomiciliario', '=', 'd.iddomiciliario')
                    ->whereIn('e.idpedido', function ($q) {
                        $q->select('idpedido')->from('pedido')->whereIn('estado', ['pendiente', 'en proceso']);
                    });
            })
            ->select('d.iddomiciliario', DB::raw('COUNT(e.idenvio) as pedidos_activos'))
            ->groupBy('d.iddomiciliario')
            ->orderBy('pedidos_activos', 'asc')
            ->first();

        $iddomiciliario = $dom ? (int)$dom->iddomiciliario : null;
        $adm = DB::table('administrador')->first();
        $idadmin = $adm ? (int)$adm->idadministrador : null;

        DB::table('envios')->insert([
            'idpedido'          => $idpedido,
            'idcliente'         => $idcliente,
            'idadministrador'   => $idadmin,
            'iddomiciliario'    => $iddomiciliario,
            'iddireccion'       => $iddireccion,
            'telefono_contacto' => null,
            'estado'            => 'pendiente',
        ]);
    }
}
