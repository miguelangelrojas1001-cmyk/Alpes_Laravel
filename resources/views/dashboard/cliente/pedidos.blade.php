@extends('layouts.app')

@section('title', 'Mis Pedidos - Los Alpes Heladería')

@section('content')
<style>
.cli-header { margin-bottom: 1.75rem; }
.cli-header h1 { display: flex; align-items: center; gap: .5rem; font-size: 1.6rem; font-weight: 900; color: var(--heading-color); margin-bottom: .3rem; }
.cli-header h1 svg { width: 28px; height: 28px; color: #2563EB; }
.cli-header p  { color: var(--muted-color); font-size: .93rem; }

/* Tabs */
.tabs { display: flex; gap: .5rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--tab-border); padding-bottom: 0; transition: border-color .25s; }
.tab-btn {
    display: inline-flex; align-items: center; gap: .45rem;
    padding: .65rem 1.3rem; border: none; background: none; cursor: pointer;
    font-family: 'Nunito', sans-serif; font-size: .92rem; font-weight: 700;
    color: var(--tab-txt); border-bottom: 3px solid transparent; margin-bottom: -2px;
    transition: color .15s, border-color .15s;
}
.tab-btn svg { width: 17px; height: 17px; }
.tab-btn.active { color: #2563EB; border-bottom-color: #2563EB; }
.tab-btn:hover  { color: #2563EB; }
.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* Cards de pedido estilo Heladeria_Alpes */
.pedido-card {
    background: var(--card-bg); border-radius: 16px; border: 1.5px solid var(--card-border);
    margin-bottom: 1.25rem; overflow: hidden; transition: box-shadow .2s, background .25s, border-color .25s;
}
.pedido-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.15); }
.pedido-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.1rem 1.35rem; cursor: pointer; user-select: none;
    background: var(--card-bg); transition: background .15s;
}
.pedido-head:hover { background: rgba(37,99,235,.04); }
.pedido-head-left { display: flex; align-items: center; gap: 1rem; }
.pedido-num  { font-size: 1.05rem; font-weight: 900; color: var(--heading-color); }
.pedido-fecha { font-size: .8rem; color: var(--muted-color); font-weight: 600; margin-top: .1rem; }
.pedido-total { font-size: 1.1rem; font-weight: 900; color: #2563EB; }
.badge { display: inline-block; padding: .3rem .85rem; border-radius: 20px; font-size: .78rem; font-weight: 800; }
.badge-pendiente  { background: #FEF3C7; color: #92400E; }
.badge-proceso    { background: #DBEAFE; color: #1E40AF; }
.badge-completado { background: #D1FAE5; color: #065F46; }
.badge-cancelado  { background: #FEE2E2; color: #991B1B; }
.chevron { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; color: var(--muted-color); transition: transform .2s; }
.chevron svg { width: 16px; height: 16px; }
.chevron.open { transform: rotate(180deg); }

/* Detalle expandible */
.pedido-body { display: none; border-top: 1.5px solid var(--card-border); padding: 1.35rem; background: var(--info-block-bg); }
.pedido-body.open { display: block; }

/* Info Boxes ("Cuadritos" de información) */
.info-box-card {
    background: var(--card-bg);
    border: 1.5px solid var(--card-border);
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    margin-bottom: 1.1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.info-box-title {
    font-size: .82rem;
    font-weight: 800;
    color: var(--heading-color);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: .85rem;
    display: flex;
    align-items: center;
    gap: .4rem;
}

/* Factura dentro del detalle */
.factura-mini { background: var(--card-bg); border-radius: 14px; border: 1.5px solid var(--card-border); overflow: hidden; margin-top: 1.1rem; }
.factura-mini-head { background: #1E3A5F; color: #fff; padding: .85rem 1.25rem; display: flex; justify-content: space-between; align-items: center; }
.factura-mini-head-title { font-size: .92rem; font-weight: 800; }
.factura-mini-head-num   { font-size: .78rem; color: rgba(255,255,255,.7); margin-top: .15rem; }
.factura-mini-body { padding: 1.1rem 1.25rem; }
.frow { display: flex; justify-content: space-between; padding: .45rem 0; font-size: .88rem; color: var(--body-color); border-bottom: 1px solid var(--table-row-bd); }
.frow:last-child { border-bottom: none; }
.fsec { font-size: .72rem; font-weight: 800; color: var(--muted-color); text-transform: uppercase; letter-spacing: .06em; padding: .65rem 0 .2rem; }
.ftotal { display: flex; justify-content: space-between; padding: .85rem 1.25rem; background: #EFF6FF; font-size: 1.05rem; font-weight: 900; color: #1D4ED8; }

/* Vacío */
.empty-state { text-align: center; padding: 3.5rem 1rem; color: var(--muted-color); background: var(--card-bg); border-radius: 16px; border: 1.5px solid var(--card-border); }
.empty-state .icon { display: flex; align-items: center; justify-content: center; width: 64px; height: 64px; margin: 0 auto 1rem; color: var(--muted-color); }
.empty-state .icon svg { width: 64px; height: 64px; stroke-width: 1.5; }
.empty-state h3 { font-size: 1.15rem; font-weight: 800; color: var(--heading-color); margin-bottom: .5rem; }

/* Botón imprimir */
.btn-print { display: inline-flex; align-items: center; gap: .45rem; padding: .55rem 1.2rem; background: var(--btn-sec-bg); color: var(--btn-sec-txt); border: 1px solid var(--card-border); border-radius: 9px; font-family: 'Nunito', sans-serif; font-size: .85rem; font-weight: 800; cursor: pointer; margin-top: .85rem; transition: background .15s; }
.btn-print:hover { background: var(--btn-sec-hover); }

/* Productos dentro del detalle */
.det-prod-row { display:flex; justify-content:space-between; align-items:center; padding:.5rem 0; border-bottom:1px solid var(--table-row-bd); font-size:.9rem; }
.det-prod-row:last-child { border-bottom: none; }
.det-prod-name { font-weight:800; color:var(--heading-color); }
.det-prod-extra { color:var(--muted-color); font-size:.8rem; font-weight:600; }
.det-prod-price { font-weight:900; color:#2563EB; }
</style>

<div class="cli-header">
    <h1>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
        <span>Mis Pedidos</span>
    </h1>
    <p>Consulta el estado y el historial de todos tus pedidos.</p>
</div>

<!-- Tabs -->
<div class="tabs">
    <button class="tab-btn active" onclick="switchTab('activos', this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span>Pedidos activos</span>
    </button>
    <button class="tab-btn" onclick="switchTab('historial', this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        <span>Historial</span>
    </button>
</div>

<!-- TAB: Activos -->
<div class="tab-panel active" id="tab-activos">
    @php
        $activos = array_filter($pedidos, fn($p) => in_array(strtolower($p['estado'] ?? ''), ['pendiente','en proceso']));
    @endphp
    @if (empty($activos))
        <div class="empty-state">
            <div class="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/><path d="M3 17h12c1.7 0 3-1.3 3-3V8c0-1.7-1.3-3-3-3H9L5 9H1v4h2v4zm15-7h4v4h-4v-4z"/></svg>
            </div>
            <h3>No tienes pedidos activos</h3>
            <p>Cuando hagas un pedido aparecerá aquí con su estado en tiempo real.</p>
            <a href="{{ route('catalogo.index') }}" style="display:inline-block;margin-top:1rem;padding:.75rem 1.6rem;background:#2563EB;color:#fff;border-radius:10px;text-decoration:none;font-weight:800">Ver catálogo</a>
        </div>
    @else
        @foreach ($activos as $p)
            @include('dashboard.cliente._card', ['p' => $p, 'detallesPorPedido' => $detallesPorPedido, 'facturasPorPedido' => $facturasPorPedido, 'enviosPorPedido' => $enviosPorPedido])
        @endforeach
    @endif
</div>

<!-- TAB: Historial -->
<div class="tab-panel" id="tab-historial">
    @php
        $historial = array_filter($pedidos, fn($p) => in_array(strtolower($p['estado'] ?? ''), ['completado','cancelado']));
    @endphp
    @if (empty($historial))
        <div class="empty-state">
            <div class="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </div>
            <h3>Sin historial aún</h3>
            <p>Aquí aparecerán tus pedidos completados o cancelados.</p>
        </div>
    @else
        @foreach ($historial as $p)
            @include('dashboard.cliente._card', ['p' => $p, 'detallesPorPedido' => $detallesPorPedido, 'facturasPorPedido' => $facturasPorPedido, 'enviosPorPedido' => $enviosPorPedido])
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
    if (body) body.classList.toggle('open');
    if (chev) chev.classList.toggle('open');
}

function imprimirFactura(facId) {
    var el = document.getElementById(facId);
    if (!el) return;
    var html = el.outerHTML;
    var win = window.open('', '_blank', 'width=650,height=850');
    win.document.write(`
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Factura — Los Alpes Heladería</title>
            <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Nunito', sans-serif; padding: 2rem; background: #fff; color: #0F172A; }
                .factura-mini { border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; }
                .factura-mini-head { background: #1E3A5F; color: #fff; padding: 1rem 1.5rem; display: flex; justify-content: space-between; }
                .factura-mini-head-title { font-size: 1.1rem; font-weight: 900; }
                .factura-mini-head-num { font-size: .8rem; color: rgba(255,255,255,.7); margin-top: .2rem; }
                .factura-mini-body { padding: 1.25rem 1.5rem; }
                .fsec { font-size: .72rem; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: .06em; padding: .65rem 0 .2rem; }
                .frow { display: flex; justify-content: space-between; padding: .45rem 0; font-size: .9rem; border-bottom: 1px solid #F1F5F9; }
                .ftotal { display: flex; justify-content: space-between; padding: 1rem 1.5rem; background: #EFF6FF; font-size: 1.1rem; font-weight: 900; color: #1D4ED8; }
                @media print { body { padding: 0; } }
            </style>
        </head>
        <body>
            ${html}
            <script>window.onload=()=>window.print()<\/script>
        </body>
        </html>
    `);
    win.document.close();
}
</script>
@endsection
