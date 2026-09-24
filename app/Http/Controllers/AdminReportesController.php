<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminReportesController extends Controller
{
    /**
     * Display sales, accounting, and inventory reports dashboard.
     */
    public function index(Request $request)
    {
        $fechaDesde = $request->input('desde', date('Y-m-01'));
        $fechaHasta = $request->input('hasta', date('Y-m-d'));

        // ── KPIs de ventas ────────────────────────────────────────────────
        $kpiRaw = DB::table('pedido')
            ->whereRaw('DATE(fecha) BETWEEN ? AND ?', [$fechaDesde, $fechaHasta])
            ->selectRaw("
                COUNT(*) AS total_pedidos,
                COALESCE(SUM(total), 0) AS ingresos_brutos,
                COALESCE(SUM(CASE WHEN estado='completado' THEN total END), 0) AS ingresos_completados,
                COALESCE(SUM(CASE WHEN estado='cancelado' THEN total END), 0) AS ingresos_cancelados,
                COALESCE(AVG(CASE WHEN estado='completado' THEN total END), 0) AS ticket_promedio,
                SUM(CASE WHEN estado='completado' THEN 1 ELSE 0 END) AS pedidos_completados,
                SUM(CASE WHEN estado='cancelado' THEN 1 ELSE 0 END) AS pedidos_cancelados,
                SUM(CASE WHEN estado='pendiente' THEN 1 ELSE 0 END) AS pedidos_pendientes,
                SUM(CASE WHEN estado='en proceso' THEN 1 ELSE 0 END) AS pedidos_en_proceso
            ")
            ->first();

        $kpi = [
            'total_pedidos'       => (int)($kpiRaw->total_pedidos ?? 0),
            'ingresos_brutos'      => (float)($kpiRaw->ingresos_brutos ?? 0),
            'ingresos_completados' => (float)($kpiRaw->ingresos_completados ?? 0),
            'ingresos_cancelados'  => (float)($kpiRaw->ingresos_cancelados ?? 0),
            'ticket_promedio'      => (float)($kpiRaw->ticket_promedio ?? 0),
            'pedidos_completados'  => (int)($kpiRaw->pedidos_completados ?? 0),
            'pedidos_cancelados'   => (int)($kpiRaw->pedidos_cancelados ?? 0),
            'pedidos_pendientes'   => (int)($kpiRaw->pedidos_pendientes ?? 0),
            'pedidos_en_proceso'   => (int)($kpiRaw->pedidos_en_proceso ?? 0),
        ];

        // ── Ingresos por día ──────────────────────────────────────────────
        $ingresosDiaList = DB::table('pedido')
            ->select(DB::raw('DATE(fecha) as dia'), DB::raw('COALESCE(SUM(total), 0) as total'))
            ->where('estado', 'completado')
            ->whereRaw('DATE(fecha) BETWEEN ? AND ?', [$fechaDesde, $fechaHasta])
            ->groupBy(DB::raw('DATE(fecha)'))
            ->orderBy('dia', 'asc')
            ->get();
        $ingresosDia = $ingresosDiaList->map(fn($i) => (array)$i)->toArray();

        // ── Métodos de pago ───────────────────────────────────────────────
        $metodosPagoList = DB::table('factura as f')
            ->join('pedido as p', 'f.idpedido', '=', 'p.idpedido')
            ->select('f.metodopago', DB::raw('COUNT(*) as cantidad'), DB::raw('COALESCE(SUM(f.totalfactura), 0) as total'))
            ->whereRaw('DATE(f.fecha) BETWEEN ? AND ?', [$fechaDesde, $fechaHasta])
            ->groupBy('f.metodopago')
            ->orderBy('total', 'desc')
            ->get();
        $metodosPago = $metodosPagoList->map(fn($i) => (array)$i)->toArray();

        // ── Productos más vendidos ────────────────────────────────────────
        $topProductosList = DB::table('detalle_pedido as dp')
            ->join('producto as pr', 'dp.idproducto', '=', 'pr.idproducto')
            ->join('pedido as p', 'dp.idpedido', '=', 'p.idpedido')
            ->select('pr.nombre', DB::raw('SUM(dp.cantidad) as unidades'), DB::raw('COALESCE(SUM(dp.total), 0) as ingresos'))
            ->where('p.estado', 'completado')
            ->whereRaw('DATE(p.fecha) BETWEEN ? AND ?', [$fechaDesde, $fechaHasta])
            ->groupBy('dp.idproducto', 'pr.nombre')
            ->orderBy('unidades', 'desc')
            ->limit(10)
            ->get();
        $topProductos = $topProductosList->map(fn($i) => (array)$i)->toArray();

        // ── Ventas por categoría ──────────────────────────────────────────
        $ventasCatList = DB::table('detalle_pedido as dp')
            ->join('producto as pr', 'dp.idproducto', '=', 'pr.idproducto')
            ->join('catalogo as c', 'pr.idcatalogo', '=', 'c.idcatalogo')
            ->join('pedido as p', 'dp.idpedido', '=', 'p.idpedido')
            ->select('c.nombre as categoria', DB::raw('SUM(dp.cantidad) as unidades'), DB::raw('COALESCE(SUM(dp.total), 0) as ingresos'))
            ->where('p.estado', 'completado')
            ->whereRaw('DATE(p.fecha) BETWEEN ? AND ?', [$fechaDesde, $fechaHasta])
            ->groupBy('c.idcatalogo', 'c.nombre')
            ->orderBy('ingresos', 'desc')
            ->get();
        $ventasCat = $ventasCatList->map(fn($i) => (array)$i)->toArray();

        // ── Rendimiento domiciliarios ─────────────────────────────────────
        $domiciliariosList = DB::table('envios as e')
            ->join('domiciliario as d', 'e.iddomiciliario', '=', 'd.iddomiciliario')
            ->join('persona as pe', 'd.idpersona', '=', 'pe.idpersona')
            ->join('pedido as p', 'e.idpedido', '=', 'p.idpedido')
            ->select('pe.nombre', DB::raw('COUNT(e.idenvio) as entregas'), DB::raw("SUM(CASE WHEN p.estado='completado' THEN 1 ELSE 0 END) as completadas"))
            ->whereRaw('DATE(p.fecha) BETWEEN ? AND ?', [$fechaDesde, $fechaHasta])
            ->groupBy('d.iddomiciliario', 'pe.nombre')
            ->orderBy('entregas', 'desc')
            ->get();
        $domiciliarios = $domiciliariosList->map(fn($i) => (array)$i)->toArray();

        // ── Insumos en stock crítico ──────────────────────────────────────
        $insumosAlertaList = DB::table('insumo')
            ->select('nombre', 'stock_actual', 'stock_minimo', 'unidad_medida', 'categoria')
            ->whereRaw('stock_actual <= stock_minimo')
            ->orderByRaw('(stock_actual / NULLIF(stock_minimo, 0)) ASC')
            ->get();
        $insumosAlerta = $insumosAlertaList->map(fn($i) => (array)$i)->toArray();

        // ── Movimientos de inventario recientes ───────────────────────────
        $movInventarioList = DB::table('inventario as inv')
            ->join('insumo as ins', 'inv.idinsumo', '=', 'ins.idinsumo')
            ->leftJoin('proveedor as pr', 'inv.idproveedor', '=', 'pr.idproveedor')
            ->select('inv.fecha', 'ins.nombre as insumo', 'ins.categoria', 'inv.cantidad', 'inv.tipo_entrada', 'inv.tipo_salida', 'pr.nombre as proveedor')
            ->whereRaw('DATE(inv.fecha) BETWEEN ? AND ?', [$fechaDesde, $fechaHasta])
            ->orderBy('inv.fecha', 'desc')
            ->limit(50)
            ->get();
        $movInventario = $movInventarioList->map(fn($i) => (array)$i)->toArray();

        // ── CONTABILIDAD ──────────────────────────────────────────────────
        $contIngresosRaw = DB::table('pedido')
            ->select(DB::raw('COALESCE(SUM(total), 0) as total_ingresos'), DB::raw('COUNT(*) as num_pedidos'))
            ->where('estado', 'completado')
            ->whereRaw('DATE(fecha) BETWEEN ? AND ?', [$fechaDesde, $fechaHasta])
            ->first();
        $contIngresos = [
            'total_ingresos' => (float)($contIngresosRaw->total_ingresos ?? 0),
            'num_pedidos'     => (int)($contIngresosRaw->num_pedidos ?? 0),
        ];

        $contGastosRaw = DB::table('inventario as inv')
            ->select(DB::raw('COALESCE(SUM(inv.cantidad), 0) as unidades_compradas'), DB::raw('COUNT(*) as num_movimientos'))
            ->where('inv.tipo_entrada', 1)
            ->whereRaw('DATE(inv.fecha) BETWEEN ? AND ?', [$fechaDesde, $fechaHasta])
            ->first();
        $contGastos = [
            'unidades_compradas' => (float)($contGastosRaw->unidades_compradas ?? 0),
            'num_movimientos'    => (int)($contGastosRaw->num_movimientos ?? 0),
        ];

        $tendenciaMensualList = DB::table('pedido')
            ->select(
                DB::raw("DATE_FORMAT(MIN(fecha), '%Y-%m') as mes"),
                DB::raw("DATE_FORMAT(MIN(fecha), '%b %Y') as mes_label"),
                DB::raw("COALESCE(SUM(total), 0) as ingresos"),
                DB::raw("COUNT(*) as pedidos")
            )
            ->where('estado', 'completado')
            ->whereRaw('fecha >= DATE_SUB(NOW(), INTERVAL 6 MONTH)')
            ->groupBy(DB::raw("DATE_FORMAT(fecha, '%Y-%m')"))
            ->orderBy('mes', 'asc')
            ->get();
        $tendenciaMensual = $tendenciaMensualList->map(fn($i) => (array)$i)->toArray();

        $contMetodosList = DB::table('factura as f')
            ->join('pedido as p', 'f.idpedido', '=', 'p.idpedido')
            ->select('f.metodopago', DB::raw('COUNT(*) as transacciones'), DB::raw('COALESCE(SUM(f.totalfactura), 0) as monto'))
            ->whereRaw('DATE(f.fecha) BETWEEN ? AND ?', [$fechaDesde, $fechaHasta])
            ->groupBy('f.metodopago')
            ->orderBy('monto', 'desc')
            ->get();
        $contMetodos = $contMetodosList->map(fn($i) => (array)$i)->toArray();

        $costoEnvios = DB::table('envios as e')
            ->join('pedido as p', 'e.idpedido', '=', 'p.idpedido')
            ->where('p.estado', 'completado')
            ->whereRaw('DATE(p.fecha) BETWEEN ? AND ?', [$fechaDesde, $fechaHasta])
            ->count();

        $gastoEnvios   = $costoEnvios * 2000;
        $gananciaEstim = $contIngresos['total_ingresos'] - $gastoEnvios;

        $idpersonaAdmin = Auth::id() ?? 0;
        $notificacionesList = DB::table('notificacion')
            ->select('idnotificacion', 'mensaje', 'fecha', 'idinsumo')
            ->where('idpersona', $idpersonaAdmin)
            ->where('tipo_stock_minimo', 1)
            ->orderBy('fecha', 'desc')
            ->limit(20)
            ->get();
        $notificaciones = $notificacionesList->map(fn($i) => (array)$i)->toArray();

        return view('dashboard.admin.reportes', compact(
            'kpi',
            'ingresosDia',
            'metodosPago',
            'topProductos',
            'ventasCat',
            'domiciliarios',
            'insumosAlerta',
            'movInventario',
            'contIngresos',
            'contGastos',
            'tendenciaMensual',
            'contMetodos',
            'gastoEnvios',
            'costoEnvios',
            'gananciaEstim',
            'notificaciones',
            'fechaDesde',
            'fechaHasta'
        ));
    }
}
