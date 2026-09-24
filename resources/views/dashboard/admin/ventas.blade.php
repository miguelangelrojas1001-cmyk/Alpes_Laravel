@extends('layouts.app')

@section('title', 'Ventas y Pedidos - Panel de Administración')

@section('content')
<style>
    .admin-header { margin-bottom: 1.75rem; }
    .admin-header h1 { display: flex; align-items: center; gap: .5rem; font-size: 1.6rem; font-weight: 900; color: var(--heading-color); margin-bottom: .3rem; }
    .admin-header h1 svg { width: 28px; height: 28px; color: #2563EB; }
    .admin-header p { color: var(--muted-color); font-size: .93rem; }
    .stats-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.25rem; margin-bottom: 1.75rem; }
    @media(max-width:768px){ .stats-row { grid-template-columns: 1fr; } }
    .stat-card { display: flex; flex-direction: column; background: var(--stat-card-bg); border-radius: 16px; padding: 1.25rem 1.5rem; box-shadow: var(--card-shadow); border: 1px solid var(--card-border); transition: background .25s, border-color .25s; }
    .stat-icon { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 10px; margin-bottom: .75rem; }
    .stat-icon.blue { background: rgba(37,99,235,.1); color: #2563EB; }
    .stat-icon.purple { background: rgba(139,92,246,.1); color: #8B5CF6; }
    .stat-icon.green { background: rgba(16,185,129,.1); color: #10B981; }
    .stat-icon svg { width: 20px; height: 20px; }
    .stat-label { font-size: .78rem; font-weight: 700; color: var(--muted-color); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .4rem; }
    .stat-value { font-size: 1.9rem; font-weight: 900; color: var(--heading-color); }
    .card { background: var(--card-bg); border-radius: 16px; box-shadow: var(--card-shadow); padding: 1.5rem; border: 1px solid var(--card-border); transition: background .25s, border-color .25s; }
    .card-title { display: flex; align-items: center; gap: .4rem; font-size: 1.05rem; font-weight: 800; color: var(--heading-color); margin-bottom: 1.25rem; }
    .card-title svg { width: 18px; height: 18px; color: #2563EB; }
    table { width: 100%; border-collapse: collapse; }
    th { background: var(--table-head-bg); font-size: .78rem; font-weight: 800; color: var(--table-head-txt); text-transform: uppercase; letter-spacing: .05em; padding: .75rem 1rem; text-align: left; border-bottom: 1px solid var(--card-border); }
    td { padding: .75rem 1rem; font-size: .88rem; color: var(--body-color); border-bottom: 1px solid var(--table-row-bd); vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    .badge { display: inline-block; padding: .25rem .75rem; border-radius: 20px; font-size: .75rem; font-weight: 700; }
    .badge-pendiente  { background: #FEF3C7; color: #92400E; }
    .badge-completado { background: #D1FAE5; color: #065F46; }
    .badge-cancelado  { background: #FEE2E2; color: #991B1B; }
    .badge-proceso    { background: #DBEAFE; color: #1E40AF; }
    select.estado-sel { padding: .35rem .65rem; border-radius: 8px; border: 1.5px solid var(--input-border); font-family: 'Nunito', sans-serif; font-size: .82rem; cursor: pointer; outline: none; background: var(--select-bg); color: var(--select-color); transition: background .25s, border-color .25s, color .25s; }
    .ventas-toolbar { display:flex; align-items:center; gap:.75rem; margin-bottom:1.25rem; flex-wrap:wrap; }
    .filtro-btn { padding:.38rem .9rem; border-radius:8px; border:1.5px solid var(--filter-btn-bd); background:var(--filter-btn-bg); color:var(--filter-btn-txt); font-family:'Nunito',sans-serif; font-size:.82rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background .15s,border-color .15s,color .15s; }
    .filtro-btn:hover, .filtro-btn.active { background:#2563EB; border-color:#2563EB; color:#fff; }
    .paginacion { display:flex; align-items:center; gap:.35rem; margin-top:1.25rem; justify-content:center; }
    .pag-btn { padding:.38rem .75rem; border-radius:8px; border:1.5px solid var(--filter-btn-bd); background:var(--filter-btn-bg); color:var(--filter-btn-txt); font-family:'Nunito',sans-serif; font-size:.82rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background .15s; }
    .pag-btn:hover { background:var(--sb-hover); }
    .pag-btn.active { background:#2563EB; border-color:#2563EB; color:#fff; cursor:default; }
    .pag-btn.disabled { opacity:.4; pointer-events:none; }
</style>

<div class="admin-header">
    <h1>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        Ventas y Pedidos
    </h1>
    <p>Historial completo de pedidos y resumen de ingresos.</p>
</div>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
        </div>
        <div class="stat-label">Total Pedidos</div>
        <div class="stat-value">{{ $totales['total_pedidos'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><line x1="6" y1="12" x2="6.01" y2="12"/><line x1="18" y1="12" x2="18.01" y2="12"/></svg>
        </div>
        <div class="stat-label">Ingresos Totales</div>
        <div class="stat-value">${{ number_format($totales['ingresos_totales'] ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="stat-label">Completados</div>
        <div class="stat-value">${{ number_format($totales['ingresos_completados'] ?? 0, 0, ',', '.') }}</div>
    </div>
</div>

<div class="card">
    <div class="card-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
        Listado de Pedidos
        <span style="margin-left:auto;font-size:.78rem;font-weight:600;color:var(--muted-color)">{{ $totalPedidos }} pedidos en total</span>
    </div>

    <!-- Filtros por estado -->
    <div class="ventas-toolbar">
        <span style="font-size:.8rem;font-weight:700;color:var(--muted-color)">Filtrar:</span>
        <a href="{{ route('admin.ventas.index') }}" class="filtro-btn {{ $filtroEstado === '' ? 'active' : '' }}">Todos</a>
        <a href="{{ route('admin.ventas.index', ['estado' => 'pendiente']) }}" class="filtro-btn {{ $filtroEstado === 'pendiente' ? 'active' : '' }}">Pendientes</a>
        <a href="{{ route('admin.ventas.index', ['estado' => 'en proceso']) }}" class="filtro-btn {{ $filtroEstado === 'en proceso' ? 'active' : '' }}">En proceso</a>
        <a href="{{ route('admin.ventas.index', ['estado' => 'completado']) }}" class="filtro-btn {{ $filtroEstado === 'completado' ? 'active' : '' }}">Completados</a>
        <a href="{{ route('admin.ventas.index', ['estado' => 'cancelado']) }}" class="filtro-btn {{ $filtroEstado === 'cancelado' ? 'active' : '' }}">Cancelados</a>
    </div>

    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr><th>#</th><th>Cliente</th><th>Total</th><th>Domiciliario</th><th>Estado</th><th>Fecha</th><th>Cambiar estado</th></tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $p)
                @php
                    $est = strtolower($p['estado'] ?? 'pendiente');
                    $cls = match($est) {
                        'completado' => 'badge-completado',
                        'cancelado'  => 'badge-cancelado',
                        'en proceso' => 'badge-proceso',
                        default      => 'badge-pendiente'
                    };
                @endphp
                <tr>
                    <td><strong>#{{ $p['idpedido'] }}</strong></td>
                    <td>{{ $p['cliente'] ?? 'N/A' }}</td>
                    <td><strong style="color:#2563EB">${{ number_format($p['total'], 0, ',', '.') }}</strong></td>
                    <td>
                        @if (!empty($p['domiciliario']))
                            <span style="display:inline-flex;align-items:center;gap:.35rem;font-size:.82rem;font-weight:700;color:var(--heading-color)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;color:#2563EB;flex-shrink:0;"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                {{ $p['domiciliario'] }}
                            </span>
                        @else
                            <span style="font-size:.78rem;color:var(--muted-color)">Sin asignar</span>
                        @endif
                    </td>
                    <td><span class="badge {{ $cls }}">{{ $p['estado'] ?? 'pendiente' }}</span></td>
                    <td>{{ $p['fecha'] ? date('d/m/Y h:i A', strtotime($p['fecha'])) : '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.ventas.estado') }}" style="display:inline">
                            @csrf
                            <input type="hidden" name="idpedido" value="{{ $p['idpedido'] }}">
                            <select name="estado" class="estado-sel" onchange="this.form.submit()">
                                <option value="pendiente"  {{ $p['estado']==='pendiente'  ? 'selected':'' }}>Pendiente</option>
                                <option value="en proceso" {{ $p['estado']==='en proceso' ? 'selected':'' }}>En proceso</option>
                                <option value="completado" {{ $p['estado']==='completado' ? 'selected':'' }}>Completado</option>
                                <option value="cancelado"  {{ $p['estado']==='cancelado'  ? 'selected':'' }}>Cancelado</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if (empty($pedidos))
                    <tr><td colspan="7" style="text-align:center;color:var(--muted-color);padding:2rem">Sin pedidos registrados</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if ($totalPaginas > 1)
    <div class="paginacion">
        @php
            $baseUrl = route('admin.ventas.index') . ($filtroEstado ? '?estado=' . urlencode($filtroEstado) : '?');
            $sep = $filtroEstado ? '&' : '';
        @endphp
        <a href="{{ $baseUrl . $sep }}pagina={{ max(1, $paginaActual - 1) }}" class="pag-btn {{ $paginaActual <= 1 ? 'disabled' : '' }}">← Anterior</a>
        @php
            $inicio = max(1, $paginaActual - 2);
            $fin    = min($totalPaginas, $paginaActual + 2);
        @endphp
        @if ($inicio > 1)
            <a href="{{ $baseUrl . $sep }}pagina=1" class="pag-btn">1</a>
            @if ($inicio > 2)<span style="color:var(--muted-color);padding:0 .25rem">…</span>@endif
        @endif
        @for ($i = $inicio; $i <= $fin; $i++)
            <a href="{{ $baseUrl . $sep }}pagina={{ $i }}" class="pag-btn {{ $i === $paginaActual ? 'active' : '' }}">{{ $i }}</a>
        @endfor
        @if ($fin < $totalPaginas)
            @if ($fin < $totalPaginas - 1)<span style="color:var(--muted-color);padding:0 .25rem">…</span>@endif
            <a href="{{ $baseUrl . $sep }}pagina={{ $totalPaginas }}" class="pag-btn">{{ $totalPaginas }}</a>
        @endif
        <a href="{{ $baseUrl . $sep }}pagina={{ min($totalPaginas, $paginaActual + 1) }}" class="pag-btn {{ $paginaActual >= $totalPaginas ? 'disabled' : '' }}">Siguiente →</a>
    </div>
    @endif
</div>
@endsection
