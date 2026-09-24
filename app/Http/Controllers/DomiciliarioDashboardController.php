<?php

namespace App\Http\Controllers;

use App\Models\Domiciliario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DomiciliarioDashboardController extends Controller
{
    /**
     * Display driver orders dashboard.
     * Security: Always derives driver record from Auth::id().
     */
    public function index(Request $request)
    {
        $idpersona = Auth::id();
        $domiciliario = Domiciliario::where('idpersona', $idpersona)->first();

        $pedidosAsignados  = [];
        $pedidosHistorial  = [];
        $detallesPorPedido = [];
        $totalEntregas     = 0;
        $totalHoy          = 0;

        if ($domiciliario) {
            $iddomiciliario = (int)$domiciliario->iddomiciliario;

            // 1. Pedidos asignados activos ('pendiente', 'en proceso')
            $activosList = DB::table('envios as e')
                ->join('pedido as p', 'e.idpedido', '=', 'p.idpedido')
                ->join('cliente as c', 'e.idcliente', '=', 'c.idcliente')
                ->join('persona as pe', 'c.idpersona', '=', 'pe.idpersona')
                ->leftJoin('direccion as d', 'e.iddireccion', '=', 'd.iddireccion')
                ->where('e.iddomiciliario', $iddomiciliario)
                ->whereIn('p.estado', ['pendiente', 'en proceso'])
                ->select(
                    'p.idpedido', 'p.total', 'p.fecha', 'p.estado',
                    'pe.nombre as cliente', 'pe.correo as cliente_correo',
                    'e.telefono_contacto', 'e.observaciones as obs_envio',
                    'd.descripcion as direccion_completa'
                )
                ->orderBy('p.fecha', 'asc')
                ->get();

            $pedidosAsignados = $activosList->map(fn($p) => (array)$p)->toArray();

            // 2. Pedidos en historial ('completado', 'cancelado')
            $histList = DB::table('envios as e')
                ->join('pedido as p', 'e.idpedido', '=', 'p.idpedido')
                ->join('cliente as c', 'e.idcliente', '=', 'c.idcliente')
                ->join('persona as pe', 'c.idpersona', '=', 'pe.idpersona')
                ->leftJoin('direccion as d', 'e.iddireccion', '=', 'd.iddireccion')
                ->where('e.iddomiciliario', $iddomiciliario)
                ->whereIn('p.estado', ['completado', 'cancelado'])
                ->select(
                    'p.idpedido', 'p.total', 'p.fecha', 'p.estado',
                    'pe.nombre as cliente',
                    'd.descripcion as direccion_completa'
                )
                ->orderBy('p.fecha', 'desc')
                ->take(50)
                ->get();

            $pedidosHistorial = $histList->map(fn($p) => (array)$p)->toArray();

            $totalEntregas = count($pedidosHistorial);
            $hoy = date('Y-m-d');

            foreach ($pedidosHistorial as $ph) {
                if ($ph['estado'] === 'completado' && str_starts_with($ph['fecha'] ?? '', $hoy)) {
                    $totalHoy++;
                }
            }

            // 3. Obtener detalles de productos para todos los pedidos (activos + historial)
            $todosIds = array_merge(
                array_column($pedidosAsignados, 'idpedido'),
                array_column($pedidosHistorial, 'idpedido')
            );

            if (!empty($todosIds)) {
                $detallesList = DB::table('detalle_pedido as dp')
                    ->leftJoin('producto as pr', 'dp.idproducto', '=', 'pr.idproducto')
                    ->whereIn('dp.idpedido', $todosIds)
                    ->select('dp.*', 'pr.nombre as nombre_producto')
                    ->get();

                foreach ($detallesList as $d) {
                    $detallesPorPedido[$d->idpedido][] = (array)$d;
                }
            }
        }

        return view('dashboard.domiciliario.pedidos', compact(
            'pedidosAsignados',
            'pedidosHistorial',
            'detallesPorPedido',
            'totalEntregas',
            'totalHoy'
        ));
    }

    /**
     * Update order state (Driver actions: "marcar_en_camino" -> 'en proceso', "marcar_entregado" -> 'completado').
     * Security: Verifies order assignment to authenticated driver before updating. Updates ONLY pedido.estado.
     */
    public function actualizarEstado(Request $request)
    {
        $idpersona = Auth::id();
        $domiciliario = Domiciliario::where('idpersona', $idpersona)->first();

        if (!$domiciliario) {
            abort(403, 'No tienes perfil de domiciliario asociado.');
        }

        $idpedido = (int)$request->input('idpedido');
        if ($idpedido <= 0) {
            return redirect()->route('domiciliario.dashboard');
        }

        // SEGURIDAD: Verificar que el pedido esté asignado al domiciliario autenticado
        $envioValido = DB::table('envios')
            ->where('idpedido', $idpedido)
            ->where('iddomiciliario', $domiciliario->iddomiciliario)
            ->exists();

        if (!$envioValido) {
            abort(403, 'No tienes permiso para actualizar este pedido.');
        }

        // Determinar la acción enviada
        $nuevoEstado = null;
        if ($request->has('marcar_entregado')) {
            $nuevoEstado = 'completado';
        } elseif ($request->has('marcar_en_camino')) {
            $nuevoEstado = 'en proceso';
        }

        // SEGURIDAD: Transiciones de estado permitidas
        if ($nuevoEstado && in_array($nuevoEstado, ['en proceso', 'completado'])) {
            // Modifica ÚNICAMENTE pedido.estado (fidelidad 1:1 con Heladeria_Alpes)
            DB::table('pedido')
                ->where('idpedido', $idpedido)
                ->update(['estado' => $nuevoEstado]);
        }

        return redirect()->route('domiciliario.dashboard');
    }
}
