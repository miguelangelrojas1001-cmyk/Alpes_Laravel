@extends('layouts.app')

@section('title', 'Panel Domiciliario - Los Alpes Heladería')

@section('content')
<style>
.dom-header { margin-bottom: 1.75rem; }
.dom-header h1 { display: flex; align-items: center; gap: .5rem; font-size: 1.6rem; font-weight: 900; color: var(--heading-color); margin-bottom: .3rem; }
.dom-header h1 svg { width: 28px; height: 28px; color: #2563EB; }
.dom-header p  { color: var(--muted-color); font-size: .93rem; }

/* Stats */
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

/* Tabs */
.tabs { display: flex; gap: .5rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--tab-border); transition: border-color .25s; }
.tab-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.25rem; border: none; background: none; cursor: pointer; font-family: 'Nunito',sans-serif; font-size: .9rem; font-weight: 700; color: var(--tab-txt); border-bottom: 3px solid transparent; margin-bottom: -2px; transition: color .15s, border-color .15s; }
.tab-btn svg { width: 16px; height: 16px; }
.tab-btn.active { color: var(--tab-active-txt); border-bottom-color: var(--tab-active-txt); }
.tab-btn:hover  { color: var(--tab-active-txt); }
.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* Tarjeta de pedido */
.pedido-card { background: var(--card-bg); border-radius: 16px; border: 1.5px solid var(--card-border); margin-bottom: 1.25rem; overflow: hidden; transition: box-shadow .2s, background .25s, border-color .25s; }
.pedido-card:hover { box-shadow: 0 6px 22px rgba(0,0,0,.15); }
.pedido-head { display: flex; align-items: center; justify-content: space-between; padding: 1.1rem 1.35rem; cursor: pointer; user-select: none; background: var(--card-bg); }
.pedido-head:hover { background: rgba(37,99,235,.04); }
.pedido-head-left { display: flex; align-items: center; gap: 1rem; }
.pedido-num   { font-size: 1.05rem; font-weight: 900; color: var(--heading-color); }
.pedido-fecha { font-size: .8rem; color: var(--muted-color); font-weight: 600; }
.pedido-total { font-size: 1.05rem; font-weight: 900; color: #2563EB; }
.chevron { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; color: var(--muted-color); transition: transform .2s; }
.chevron svg { width: 16px; height: 16px; }
.chevron.open { transform: rotate(180deg); }

.badge { display: inline-block; padding: .3rem .85rem; border-radius: 20px; font-size: .78rem; font-weight: 800; }
.badge-pendiente  { background: #FEF3C7; color: #92400E; }
.badge-proceso    { background: #DBEAFE; color: #1E40AF; }
.badge-completado { background: #D1FAE5; color: #065F46; }
.badge-cancelado  { background: #FEE2E2; color: #991B1B; }

/* Detalle expandible */
.pedido-body { display: none; border-top: 1.5px solid var(--card-border); background: var(--info-block-bg); }
.pedido-body.open { display: block; }

.pedido-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; padding: 1.25rem; }
@media(max-width:600px){ .pedido-info-grid { grid-template-columns: 1fr; } }

.info-block { background: var(--card-bg); border: 1.5px solid var(--card-border); border-radius: 12px; padding: 1rem; }
.info-block-title { display: flex; align-items: center; gap: .3rem; font-size: .72rem; font-weight: 800; color: var(--muted-color); text-transform: uppercase; letter-spacing: .06em; margin-bottom: .65rem; }
.info-block-title svg { width: 14px; height: 14px; color: #2563EB; }
.info-row { display: flex; justify-content: space-between; font-size: .85rem; padding: .3rem 0; border-bottom: 1px solid var(--table-row-bd); }
.info-row:last-child { border-bottom: none; }
.info-row .lbl { color: var(--muted-color); }
.info-row .val { font-weight: 700; color: var(--heading-color); text-align: right; max-width: 60%; }

.productos-list { padding: 0 1.25rem 1rem; }
.prod-item { display: flex; justify-content: space-between; align-items: flex-start; padding: .5rem 0; border-bottom: 1px solid var(--table-row-bd); font-size: .88rem; }
.prod-item:last-child { border-bottom: none; }
.prod-item .pnombre { font-weight: 700; color: var(--heading-color); }
.prod-item .psabores { font-size: .75rem; color: var(--muted-color); margin-top: .1rem; }
.prod-item .pprecio { font-weight: 800; color: #2563EB; white-space: nowrap; }

/* Botones de acción */
.acciones { display: flex; gap: .75rem; padding: 1rem 1.25rem; border-top: 1px solid var(--pedido-head-bd); background: var(--info-block-bg); transition: background .25s; }
.btn-accion { flex: 1; padding: .7rem; border: none; border-radius: 10px; font-family: 'Nunito',sans-serif; font-size: .9rem; font-weight: 800; cursor: pointer; transition: background .2s; }
.btn-camino    { background: #DBEAFE; color: #1E40AF; }
.btn-camino:hover { background: #BFDBFE; }
.btn-entregado { background: #D1FAE5; color: #065F46; }
.btn-entregado:hover { background: #A7F3D0; }

.dir-box { background: var(--dir-box-bg); border: 1.5px solid var(--dir-box-bd); border-radius: 12px; padding: .85rem 1rem; margin: 0 1.25rem 1rem; display: flex; align-items: center; gap: .75rem; transition: background .25s, border-color .25s; }
.dir-box .dir-text { font-size: .88rem; font-weight: 700; color: var(--dir-text); }

.empty-state { text-align: center; padding: 3rem 1rem; color: var(--muted-color); }
.empty-state h3 { font-size: 1.1rem; font-weight: 800; color: var(--empty-h3); margin-bottom: .5rem; }
</style>

<div class="dom-header">
    <h1>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/><path d="M3 17h12c1.7 0 3-1.3 3-3V8c0-1.7-1.3-3-3-3H9L5 9H1v4h2v4zm15-7h4v4h-4v-4z"/></svg>
        Panel Domiciliario
    </h1>
    <p>Gestiona tus entregas del día</p>
</div>

<!-- Stats -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <span class="stat-label">Pedidos activos</span>
        <span class="stat-value">{{ count($pedidosAsignados) }}</span>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <span class="stat-label">Entregas de hoy</span>
        <span class="stat-value">{{ $totalHoy }}</span>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        </div>
        <span class="stat-label">Historial entregas</span>
        <span class="stat-value">{{ $totalEntregas }}</span>
    </div>
</div>

<!-- Tabs -->
<div class="tabs">
    <button class="tab-btn active" onclick="switchTab('asignados', this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Asignados ({{ count($pedidosAsignados) }})
    </button>
    <button class="tab-btn" onclick="switchTab('historial', this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        Historial ({{ count($pedidosHistorial) }})
    </button>
</div>

<!-- TAB: Asignados Activos -->
<div class="tab-panel active" id="tab-asignados">
    @if (empty($pedidosAsignados))
        <div class="empty-state">
            <h3>No tienes entregas pendientes</h3>
            <p>Los nuevos pedidos asignados aparecerán aquí.</p>
        </div>
    @else
        @foreach ($pedidosAsignados as $p)
            @include('dashboard.domiciliario._card', ['p' => $p, 'detalles' => $detallesPorPedido[$p['idpedido']] ?? [], 'esHistorial' => false])
        @endforeach
    @endif
</div>

<!-- TAB: Historial Completo -->
<div class="tab-panel" id="tab-historial">
    @if (empty($pedidosHistorial))
        <div class="empty-state">
            <h3>Sin entregas registradas</h3>
            <p>Aquí se mostrarán las entregas que completes o canceles.</p>
        </div>
    @else
        @foreach ($pedidosHistorial as $p)
            @include('dashboard.domiciliario._card', ['p' => $p, 'detalles' => $detallesPorPedido[$p['idpedido']] ?? [], 'esHistorial' => true])
        @endforeach
    @endif
</div>

<script>
function switchTab(name, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + name).classList.add('active');
}

function togglePedido(uid) {
    var body = document.getElementById('body_' + uid);
    var chev = document.getElementById('chev_' + uid);
    body.classList.toggle('open');
    chev.classList.toggle('open');
}
</script>
@endsection
