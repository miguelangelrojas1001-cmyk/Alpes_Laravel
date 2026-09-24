@extends('layouts.app')

@section('title', 'Reportes y Contabilidad - Panel de Administración')

@section('content')
<style>
.rep-header { margin-bottom: 1.75rem; }
.rep-header h1 { display:flex; align-items:center; gap:.5rem; font-size:1.6rem; font-weight:900; color:var(--heading-color); margin-bottom:.3rem; }
.rep-header h1 svg { width:28px; height:28px; color:#2563EB; }
.rep-header p { color:var(--muted-color); font-size:.93rem; }

/* Filtro fechas */
.rep-filter { display:flex; align-items:center; gap:.75rem; flex-wrap:wrap; margin-bottom:1.75rem; }
.rep-filter label { font-size:.8rem; font-weight:700; color:var(--muted-color); text-transform:uppercase; letter-spacing:.05em; }
.rep-filter input[type=date] {
    padding:.42rem .8rem; border-radius:9px;
    border:1.5px solid var(--input-border); background:var(--input-bg);
    color:var(--input-color); font-family:'Nunito',sans-serif; font-size:.86rem;
    outline:none; transition:border-color .15s;
}
.rep-filter input[type=date]:focus { border-color:#2563EB; }
.rep-filter button {
    padding:.44rem 1.1rem; border-radius:9px; border:none;
    background:#2563EB; color:#fff; font-family:'Nunito',sans-serif;
    font-size:.86rem; font-weight:700; cursor:pointer; transition:opacity .15s;
}
.rep-filter button:hover { opacity:.88; }

/* Tabs */
.rep-tabs { display:flex; gap:.25rem; border-bottom:2px solid var(--tab-border); margin-bottom:1.75rem; }
.rep-tab {
    padding:.6rem 1.2rem; font-size:.88rem; font-weight:700;
    color:var(--tab-txt); background:none; border:none; cursor:pointer;
    border-bottom:2px solid transparent; margin-bottom:-2px;
    font-family:'Nunito',sans-serif; transition:color .15s, border-color .15s;
}
.rep-tab.active { color:var(--tab-active-txt); border-bottom-color:var(--tab-active-txt); }
.rep-tab-panel { display:none; }
.rep-tab-panel.active { display:block; }

/* KPI grid */
.kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1.1rem; margin-bottom:1.75rem; }
@media(max-width:900px){ .kpi-grid { grid-template-columns:repeat(2,1fr); } }
.kpi-card {
    background:var(--stat-card-bg); border-radius:14px; padding:1.1rem 1.25rem;
    border:1px solid var(--card-border); box-shadow:var(--card-shadow);
}
.kpi-icon { width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; margin-bottom:.65rem; }
.kpi-icon svg { width:18px; height:18px; }
.kpi-icon.blue   { background:rgba(37,99,235,.12);  color:#2563EB; }
.kpi-icon.green  { background:rgba(16,185,129,.12); color:#10B981; }
.kpi-icon.purple { background:rgba(139,92,246,.12); color:#8B5CF6; }
.kpi-icon.amber  { background:rgba(245,158,11,.12); color:#F59E0B; }
.kpi-icon.red    { background:rgba(239,68,68,.12);  color:#EF4444; }
.kpi-label { font-size:.72rem; font-weight:800; color:var(--muted-color); text-transform:uppercase; letter-spacing:.05em; margin-bottom:.3rem; }
.kpi-value { font-size:1.7rem; font-weight:900; color:var(--heading-color); line-height:1; }
.kpi-sub   { font-size:.75rem; color:var(--muted-color); margin-top:.3rem; }

/* Cards genéricas */
.rep-card {
    background:var(--card-bg); border-radius:14px; padding:1.35rem 1.5rem;
    border:1px solid var(--card-border); box-shadow:var(--card-shadow); margin-bottom:1.25rem;
}
.rep-card-title { display:flex; align-items:center; gap:.4rem; font-size:1rem; font-weight:800; color:var(--heading-color); margin-bottom:1.1rem; }
.rep-card-title svg { width:17px; height:17px; color:#2563EB; }

/* Tabla */
.rep-table { width:100%; border-collapse:collapse; }
.rep-table th { background:var(--table-head-bg); font-size:.72rem; font-weight:800; color:var(--table-head-txt); text-transform:uppercase; letter-spacing:.05em; padding:.65rem .9rem; text-align:left; border-bottom:1px solid var(--card-border); }
.rep-table td { padding:.65rem .9rem; font-size:.86rem; color:var(--body-color); border-bottom:1px solid var(--table-row-bd); vertical-align:middle; }
.rep-table tr:last-child td { border-bottom:none; }
.rep-table tr:hover td { background:var(--table-hover); }

/* Barras de progreso */
.bar-wrap { background:var(--input-bg); border-radius:99px; height:7px; overflow:hidden; margin-top:.3rem; }
.bar-fill  { height:100%; border-radius:99px; background:#2563EB; transition:width .4s; }
.bar-fill.green  { background:#10B981; }
.bar-fill.purple { background:#8B5CF6; }
.bar-fill.amber  { background:#F59E0B; }

/* Badges */
.badge { display:inline-block; padding:.22rem .65rem; border-radius:20px; font-size:.72rem; font-weight:700; }
.badge-entrada { background:rgba(16,185,129,.12); color:#10B981; }
.badge-salida  { background:rgba(239,68,68,.1);   color:#EF4444; }
.badge-alerta  { background:rgba(245,158,11,.12); color:#F59E0B; }
.badge-critico { background:rgba(239,68,68,.1);   color:#EF4444; }

/* Grid 2 cols */
.rep-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
@media(max-width:800px){ .rep-grid2 { grid-template-columns:1fr; } }

/* Métodos de pago */
.pago-row { display:flex; align-items:center; gap:.85rem; padding:.55rem 0; border-bottom:1px solid var(--table-row-bd); }
.pago-row:last-child { border-bottom:none; }
.pago-icon { width:34px; height:34px; border-radius:9px; background:var(--input-bg); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.pago-icon svg { width:16px; height:16px; color:#2563EB; }
.pago-info { flex:1; }
.pago-name  { font-size:.86rem; font-weight:700; color:var(--heading-color); }
.pago-count { font-size:.74rem; color:var(--muted-color); }
.pago-total { font-size:.92rem; font-weight:800; color:#2563EB; }

/* Alerta stock */
.stock-row { display:flex; align-items:center; gap:.75rem; padding:.55rem 0; border-bottom:1px solid var(--table-row-bd); }
.stock-row:last-child { border-bottom:none; }
.stock-info { flex:1; }
.stock-name { font-size:.86rem; font-weight:700; color:var(--heading-color); }
.stock-cat  { font-size:.72rem; color:var(--muted-color); }
.stock-nums { text-align:right; }
.stock-actual { font-size:.92rem; font-weight:800; color:#EF4444; }
.stock-min    { font-size:.72rem; color:var(--muted-color); }

/* Empty state */
.rep-empty { text-align:center; padding:2.5rem 1rem; color:var(--muted-color); font-size:.9rem; }
.rep-empty svg { width:40px; height:40px; margin-bottom:.75rem; opacity:.35; }
</style>

<div class="rep-header">
    <h1>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
            <line x1="6" y1="20" x2="6" y2="14"/><line x1="2" y1="20" x2="22" y2="20"/>
        </svg>
        Reportes y Contabilidad
    </h1>
    <p>Análisis de ventas, ingresos, inventario y rendimiento operativo.</p>
</div>

<!-- Filtro de fechas -->
<form method="GET" action="{{ route('admin.reportes.index') }}" class="rep-filter">
    <label>Desde</label>
    <input type="date" name="desde" value="{{ $fechaDesde }}">
    <label>Hasta</label>
    <input type="date" name="hasta" value="{{ $fechaHasta }}">
    <button type="submit">Filtrar</button>
</form>

<!-- Tabs -->
<div class="rep-tabs">
    <button class="rep-tab active" onclick="showTab('ventas', this)">
        Ventas
    </button>
    <button class="rep-tab" onclick="showTab('contabilidad', this)">
        Contabilidad
    </button>
    <button class="rep-tab" onclick="showTab('inventario', this)">
        Inventario &amp; Stock
    </button>
</div>

<!-- ══════════════════════════════════════════════════════════════
     TAB 1: VENTAS & CONTABILIDAD
═══════════════════════════════════════════════════════════════ -->
<div id="tab-ventas" class="rep-tab-panel active">

    <!-- KPIs -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div class="kpi-label">Total Pedidos</div>
            <div class="kpi-value">{{ number_format($kpi['total_pedidos']) }}</div>
            <div class="kpi-sub">
                {{ $kpi['pedidos_completados'] }} completados &middot;
                {{ $kpi['pedidos_cancelados'] }} cancelados
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="kpi-label">Ingresos Confirmados</div>
            <div class="kpi-value">${{ number_format($kpi['ingresos_completados'], 0, ',', '.') }}</div>
            <div class="kpi-sub">Solo pedidos completados</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
            </div>
            <div class="kpi-label">Ticket Promedio</div>
            <div class="kpi-value">${{ number_format($kpi['ticket_promedio'], 0, ',', '.') }}</div>
            <div class="kpi-sub">Por pedido completado</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="kpi-label">En Proceso / Pendientes</div>
            <div class="kpi-value">{{ $kpi['pedidos_en_proceso'] + $kpi['pedidos_pendientes'] }}</div>
            <div class="kpi-sub">
                {{ $kpi['pedidos_en_proceso'] }} en proceso &middot;
                {{ $kpi['pedidos_pendientes'] }} pendientes
            </div>
        </div>
    </div>

    <div class="rep-grid2">

        <!-- Ingresos por día -->
        <div class="rep-card">
            <div class="rep-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Ingresos por día
            </div>
            @if (empty($ingresosDia))
                <div class="rep-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3h18v18H3z"/></svg>
                    <div>Sin datos en el período</div>
                </div>
            @else
                @php $maxDia = max(array_column($ingresosDia, 'total')) ?: 1; @endphp
                <div style="display:flex;flex-direction:column;gap:.55rem">
                    @foreach ($ingresosDia as $d)
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:.78rem;margin-bottom:.2rem">
                            <span style="color:var(--muted-color)">{{ date('d M', strtotime($d['dia'])) }}</span>
                            <span style="font-weight:700;color:var(--heading-color)">${{ number_format($d['total'], 0, ',', '.') }}</span>
                        </div>
                        <div class="bar-wrap">
                            <div class="bar-fill green" style="width:{{ round($d['total'] / $maxDia * 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Métodos de pago -->
        <div class="rep-card">
            <div class="rep-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Métodos de pago
            </div>
            @if (empty($metodosPago))
                <div class="rep-empty">Sin facturas en el período</div>
            @else
                @foreach ($metodosPago as $mp)
                <div class="pago-row">
                    <div class="pago-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
                    </div>
                    <div class="pago-info">
                        <div class="pago-name">{{ ucfirst($mp['metodopago'] ?? 'N/A') }}</div>
                        <div class="pago-count">{{ $mp['cantidad'] }} transacciones</div>
                    </div>
                    <div class="pago-total">${{ number_format($mp['total'], 0, ',', '.') }}</div>
                </div>
                @endforeach
            @endif
        </div>

    </div><!-- /.rep-grid2 -->

    <div class="rep-grid2">

        <!-- Top productos -->
        <div class="rep-card">
            <div class="rep-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                Productos más vendidos
            </div>
            @if (empty($topProductos))
                <div class="rep-empty">Sin ventas en el período</div>
            @else
                @php $maxUnid = max(array_column($topProductos, 'unidades')) ?: 1; @endphp
                <table class="rep-table">
                    <thead><tr><th>#</th><th>Producto</th><th>Unidades</th><th>Ingresos</th></tr></thead>
                    <tbody>
                    @foreach ($topProductos as $i => $tp)
                    <tr>
                        <td style="font-weight:800;color:var(--muted-color)">{{ $i + 1 }}</td>
                        <td>{{ $tp['nombre'] }}</td>
                        <td>
                            <span style="font-weight:700">{{ $tp['unidades'] }}</span>
                            <div class="bar-wrap" style="width:80px;display:inline-block;vertical-align:middle;margin-left:.4rem">
                                <div class="bar-fill" style="width:{{ round($tp['unidades'] / $maxUnid * 100) }}%"></div>
                            </div>
                        </td>
                        <td style="font-weight:700;color:#10B981">${{ number_format($tp['ingresos'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Ventas por categoría -->
        <div class="rep-card">
            <div class="rep-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Ventas por categoría
            </div>
            @if (empty($ventasCat))
                <div class="rep-empty">Sin ventas en el período</div>
            @else
                @php
                    $maxCat = max(array_column($ventasCat, 'ingresos')) ?: 1;
                    $colors = ['blue','green','purple','amber'];
                @endphp
                <div style="display:flex;flex-direction:column;gap:.7rem">
                    @foreach ($ventasCat as $ci => $vc)
                    @php $col = $colors[$ci % count($colors)]; @endphp
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:.25rem">
                            <span style="font-weight:700;color:var(--heading-color)">{{ $vc['categoria'] }}</span>
                            <span style="color:var(--muted-color)">{{ $vc['unidades'] }} uds &middot; <strong style="color:var(--heading-color)">${{ number_format($vc['ingresos'], 0, ',', '.') }}</strong></span>
                        </div>
                        <div class="bar-wrap">
                            <div class="bar-fill {{ $col }}" style="width:{{ round($vc['ingresos'] / $maxCat * 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div><!-- /.rep-grid2 -->

    <!-- Rendimiento domiciliarios -->
    <div class="rep-card">
        <div class="rep-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            Rendimiento de domiciliarios
        </div>
        @if (empty($domiciliarios))
            <div class="rep-empty">Sin entregas en el período</div>
        @else
        <table class="rep-table">
            <thead><tr><th>Domiciliario</th><th>Entregas asignadas</th><th>Completadas</th><th>Tasa éxito</th></tr></thead>
            <tbody>
            @foreach ($domiciliarios as $dom)
            @php $tasa = $dom['entregas'] > 0 ? round($dom['completadas'] / $dom['entregas'] * 100) : 0; @endphp
            <tr>
                <td style="font-weight:700">{{ $dom['nombre'] }}</td>
                <td>{{ $dom['entregas'] }}</td>
                <td>{{ $dom['completadas'] }}</td>
                <td>
                    <span style="font-weight:800;color:{{ $tasa >= 80 ? '#10B981' : ($tasa >= 50 ? '#F59E0B' : '#EF4444') }}">
                        {{ $tasa }}%
                    </span>
                    <div class="bar-wrap" style="width:90px;display:inline-block;vertical-align:middle;margin-left:.5rem">
                        <div class="bar-fill {{ $tasa >= 80 ? 'green' : ($tasa >= 50 ? 'amber' : '') }}" style="width:{{ $tasa }}%"></div>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div><!-- /#tab-ventas -->

<!-- ══════════════════════════════════════════════════════════════
     TAB 2: CONTABILIDAD
═══════════════════════════════════════════════════════════════ -->
<div id="tab-contabilidad" class="rep-tab-panel">

    <!-- KPIs contables -->
    @php $margen = $contIngresos['total_ingresos'] > 0 ? round($gananciaEstim / $contIngresos['total_ingresos'] * 100) : 0; @endphp
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="kpi-label">Ingresos confirmados</div>
            <div class="kpi-value">${{ number_format($contIngresos['total_ingresos'], 0, ',', '.') }}</div>
            <div class="kpi-sub">{{ $contIngresos['num_pedidos'] }} pedidos completados</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
            <div class="kpi-label">Gasto en envíos</div>
            <div class="kpi-value">${{ number_format($gastoEnvios, 0, ',', '.') }}</div>
            <div class="kpi-sub">{{ $costoEnvios }} envíos × $2.000</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon {{ $gananciaEstim >= 0 ? 'green' : 'red' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
            </div>
            <div class="kpi-label">Ganancia estimada</div>
            <div class="kpi-value" style="color:{{ $gananciaEstim >= 0 ? '#10B981' : '#EF4444' }}">
                ${{ number_format(abs($gananciaEstim), 0, ',', '.') }}
            </div>
            <div class="kpi-sub">Margen: {{ $margen }}%</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
            </div>
            <div class="kpi-label">Transacciones</div>
            <div class="kpi-value">{{ array_sum(array_column($contMetodos, 'transacciones')) }}</div>
            <div class="kpi-sub">Facturas emitidas en el período</div>
        </div>
    </div>

    <div class="rep-grid2">

        <!-- Tendencia mensual -->
        <div class="rep-card">
            <div class="rep-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Tendencia de ingresos (últimos 6 meses)
            </div>
            @if (empty($tendenciaMensual))
                <div class="rep-empty">Sin datos históricos</div>
            @else
                @php $maxMes = max(array_column($tendenciaMensual, 'ingresos')) ?: 1; @endphp
                <div style="display:flex;flex-direction:column;gap:.65rem">
                    @foreach ($tendenciaMensual as $tm)
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:.2rem">
                            <span style="font-weight:700;color:var(--heading-color)">{{ $tm['mes_label'] }}</span>
                            <span style="color:var(--muted-color)">{{ $tm['pedidos'] }} pedidos &middot; <strong style="color:#10B981">${{ number_format($tm['ingresos'], 0, ',', '.') }}</strong></span>
                        </div>
                        <div class="bar-wrap">
                            <div class="bar-fill green" style="width:{{ round($tm['ingresos'] / $maxMes * 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Desglose por método de pago -->
        <div class="rep-card">
            <div class="rep-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Ingresos por método de pago
            </div>
            @if (empty($contMetodos))
                <div class="rep-empty">Sin facturas en el período</div>
            @else
                @php $totalFacturado = array_sum(array_column($contMetodos, 'monto')); @endphp
                @foreach ($contMetodos as $cm)
                @php $pct = $totalFacturado > 0 ? round($cm['monto'] / $totalFacturado * 100) : 0; @endphp
                <div style="margin-bottom:1rem">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.3rem">
                        <span style="font-weight:700;font-size:.88rem;color:var(--heading-color)">{{ ucfirst($cm['metodopago'] ?? 'N/A') }}</span>
                        <span style="font-size:.8rem;color:var(--muted-color)">{{ $cm['transacciones'] }} transacciones</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:.75rem">
                        <div class="bar-wrap" style="flex:1">
                            <div class="bar-fill purple" style="width:{{ $pct }}%"></div>
                        </div>
                        <span style="font-weight:800;font-size:.9rem;color:#8B5CF6;white-space:nowrap">${{ number_format($cm['monto'], 0, ',', '.') }}</span>
                        <span style="font-size:.75rem;color:var(--muted-color);white-space:nowrap">{{ $pct }}%</span>
                    </div>
                </div>
                @endforeach
                <div style="border-top:1px solid var(--card-border);padding-top:.75rem;display:flex;justify-content:space-between;align-items:center">
                    <span style="font-size:.82rem;font-weight:700;color:var(--muted-color)">TOTAL FACTURADO</span>
                    <span style="font-size:1.1rem;font-weight:900;color:var(--heading-color)">${{ number_format($totalFacturado, 0, ',', '.') }}</span>
                </div>
            @endif
        </div>

    </div><!-- /.rep-grid2 -->

    <!-- Resumen contable del período -->
    <div class="rep-card">
        <div class="rep-card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            Resumen contable del período
        </div>
        <table class="rep-table">
            <thead><tr><th>Concepto</th><th>Detalle</th><th style="text-align:right">Monto</th></tr></thead>
            <tbody>
                <tr>
                    <td style="font-weight:700;color:#10B981">+ Ingresos por ventas</td>
                    <td style="color:var(--muted-color)">{{ $contIngresos['num_pedidos'] }} pedidos completados</td>
                    <td style="text-align:right;font-weight:800;color:#10B981">${{ number_format($contIngresos['total_ingresos'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="font-weight:700;color:#EF4444">− Costo de envíos</td>
                    <td style="color:var(--muted-color)">{{ $costoEnvios }} envíos × $2.000</td>
                    <td style="text-align:right;font-weight:800;color:#EF4444">−${{ number_format($gastoEnvios, 0, ',', '.') }}</td>
                </tr>
                <tr style="background:var(--table-head-bg)">
                    <td style="font-weight:900;color:var(--heading-color)">= Ganancia estimada</td>
                    <td style="color:var(--muted-color)">Margen: {{ $margen }}%</td>
                    <td style="text-align:right;font-weight:900;font-size:1.05rem;color:{{ $gananciaEstim >= 0 ? '#10B981' : '#EF4444' }}">
                        {{ $gananciaEstim >= 0 ? '' : '−' }}${{ number_format(abs($gananciaEstim), 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="font-size:.75rem;color:var(--muted-color);margin-top:.85rem;padding-top:.75rem;border-top:1px solid var(--card-border)">
            * La ganancia es estimada. No incluye costos de insumos, nómina ni gastos operativos adicionales que no estén registrados en el sistema.
        </p>
    </div>

</div><!-- /#tab-contabilidad -->

<!-- ══════════════════════════════════════════════════════════════
     TAB 3: INVENTARIO & STOCK
═══════════════════════════════════════════════════════════════ -->
<div id="tab-inventario" class="rep-tab-panel">

    <!-- KPIs inventario -->
    @php
        $totalInsumos   = count($insumosAlerta);
        $totalEntradas  = array_sum(array_column(array_filter($movInventario, fn($m) => $m['tipo_entrada']), 'cantidad'));
        $totalSalidas   = array_sum(array_column(array_filter($movInventario, fn($m) => $m['tipo_salida']),  'cantidad'));
    @endphp
    <div class="kpi-grid" style="grid-template-columns:repeat(3,1fr)">
        <div class="kpi-card">
            <div class="kpi-icon red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="kpi-label">Insumos en alerta</div>
            <div class="kpi-value">{{ $totalInsumos }}</div>
            <div class="kpi-sub">Stock ≤ mínimo requerido</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
            </div>
            <div class="kpi-label">Entradas (período)</div>
            <div class="kpi-value">{{ number_format($totalEntradas, 2) }}</div>
            <div class="kpi-sub">Unidades ingresadas</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 6 10.5 15.5 15.5 10.5 23 18"/></svg>
            </div>
            <div class="kpi-label">Salidas (período)</div>
            <div class="kpi-value">{{ number_format($totalSalidas, 2) }}</div>
            <div class="kpi-sub">Unidades consumidas</div>
        </div>
    </div>

    <div class="rep-grid2">

        <!-- Insumos en alerta -->
        <div class="rep-card">
            <div class="rep-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Insumos con stock crítico
            </div>
            @if (empty($insumosAlerta))
                <div class="rep-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <div>Todos los insumos tienen stock suficiente</div>
                </div>
            @else
                @foreach ($insumosAlerta as $ins)
                @php
                    $pct = $ins['stock_minimo'] > 0 ? min(100, round($ins['stock_actual'] / $ins['stock_minimo'] * 100)) : 0;
                    $cls = $pct <= 0 ? 'badge-critico' : 'badge-alerta';
                @endphp
                <div class="stock-row">
                    <div class="stock-info">
                        <div class="stock-name">{{ $ins['nombre'] }}</div>
                        <div class="stock-cat">{{ $ins['categoria'] }}</div>
                        <div class="bar-wrap" style="margin-top:.35rem;width:100%">
                            <div class="bar-fill" style="width:{{ $pct }}%;background:#EF4444"></div>
                        </div>
                    </div>
                    <div class="stock-nums">
                        <div class="stock-actual">{{ $ins['stock_actual'] }} {{ $ins['unidad_medida'] }}</div>
                        <div class="stock-min">Mín: {{ $ins['stock_minimo'] }}</div>
                        <span class="badge {{ $cls }}">{{ $pct <= 0 ? 'Agotado' : 'Bajo' }}</span>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <!-- Movimientos recientes -->
        <div class="rep-card">
            <div class="rep-card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Movimientos recientes
            </div>
            @if (empty($movInventario))
                <div class="rep-empty">Sin movimientos en el período</div>
            @else
            <div style="max-height:380px;overflow-y:auto">
                <table class="rep-table">
                    <thead><tr><th>Fecha</th><th>Insumo</th><th>Tipo</th><th>Cantidad</th><th>Proveedor</th></tr></thead>
                    <tbody>
                    @foreach ($movInventario as $mov)
                    <tr>
                        <td style="white-space:nowrap;font-size:.78rem">{{ date('d/m H:i', strtotime($mov['fecha'])) }}</td>
                        <td>
                            <span style="font-weight:600">{{ $mov['insumo'] }}</span>
                            <div style="font-size:.72rem;color:var(--muted-color)">{{ $mov['categoria'] }}</div>
                        </td>
                        <td>
                            @if ($mov['tipo_entrada'])
                                <span class="badge badge-entrada">Entrada</span>
                            @else
                                <span class="badge badge-salida">Salida</span>
                            @endif
                        </td>
                        <td style="font-weight:700">{{ number_format($mov['cantidad'], 2) }}</td>
                        <td style="font-size:.8rem;color:var(--muted-color)">{{ $mov['proveedor'] ?? '—' }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div><!-- /.rep-grid2 -->

</div><!-- /#tab-inventario -->

<script>
function showTab(name, btn) {
    document.querySelectorAll('.rep-tab-panel').forEach(function(p){ p.classList.remove('active'); });
    document.querySelectorAll('.rep-tab').forEach(function(b){ b.classList.remove('active'); });
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}
</script>
@endsection
