@php
    $est = strtolower($p['estado'] ?? 'pendiente');
    $badgeClass = match($est) {
        'completado' => 'badge-completado',
        'cancelado'  => 'badge-cancelado',
        'en proceso' => 'badge-proceso',
        default      => 'badge-pendiente',
    };
    $detalles = $detallesPorPedido[$p['idpedido']] ?? [];
    $factura  = $facturasPorPedido[$p['idpedido']] ?? null;
    $envio    = $enviosPorPedido[$p['idpedido']] ?? null;
    $uid      = 'ped_' . $p['idpedido'];
@endphp

<div class="pedido-card">
    <div class="pedido-head" onclick="togglePedido('{{ $uid }}')">
        <div class="pedido-head-left">
            <div>
                <div class="pedido-num">Pedido #{{ $p['idpedido'] }}</div>
                <div class="pedido-fecha">{{ $p['fecha'] ? date('d/m/Y h:i A', strtotime($p['fecha'])) : '-' }}</div>
            </div>
            <span class="badge {{ $badgeClass }}">
                @if ($est === 'en proceso')
                    <span style="display:inline-flex;align-items:center;gap:.3rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                        En proceso
                    </span>
                @elseif ($est === 'completado')
                    <span style="display:inline-flex;align-items:center;gap:.3rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Completado
                    </span>
                @elseif ($est === 'cancelado')
                    <span style="display:inline-flex;align-items:center;gap:.3rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        Cancelado
                    </span>
                @else
                    <span style="display:inline-flex;align-items:center;gap:.3rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Pendiente
                    </span>
                @endif
            </span>
        </div>
        <div style="display:flex;align-items:center;gap:1rem">
            <div class="pedido-total">${{ number_format($p['total'], 0, ',', '.') }}</div>
            <span class="chevron" id="chev_{{ $uid }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </span>
        </div>
    </div>

    <div class="pedido-body" id="body_{{ $uid }}">

        <!-- Cuadrito 1: Productos del Pedido -->
        @if (!empty($detalles))
        <div class="info-box-card">
            <div class="info-box-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;color:#2563EB;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                <span>Productos del Pedido</span>
            </div>
            @foreach ($detalles as $d)
            <div class="det-prod-row">
                <div>
                    <span class="det-prod-name">{{ $d['nombre_producto'] ?? 'Producto #'.$d['idproducto'] }}</span>
                    @if (!empty($d['sabores']))
                        <span class="det-prod-extra"> · {{ $d['sabores'] }}</span>
                    @endif
                    @if (!empty($d['tamano']))
                        <span class="det-prod-extra"> · {{ $d['tamano'] }}</span>
                    @endif
                    @if (!empty($d['observaciones']))
                        <span class="det-prod-extra" style="color:#F59E0B"> · Obs: {{ $d['observaciones'] }}</span>
                    @endif
                    <span class="det-prod-extra"> &times;{{ $d['cantidad'] }}</span>
                </div>
                <span class="det-prod-price">${{ number_format($d['total'], 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Cuadrito 2: Datos de Entrega -->
        @if (!empty($envio))
        <div class="info-box-card">
            <div class="info-box-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;color:#2563EB;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Información de Entrega</span>
            </div>
            <div style="font-size:.88rem;color:var(--body-color);display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:.75rem;">
                <div>
                    <span style="font-size:.75rem;color:var(--muted-color);display:block;font-weight:700;">DIRECCIÓN</span>
                    <strong>{{ $envio['direccion_completa'] ?? 'Dirección registrada' }}</strong>
                </div>
                @if (!empty($envio['telefono_contacto']))
                <div>
                    <span style="font-size:.75rem;color:var(--muted-color);display:block;font-weight:700;">TELÉFONO</span>
                    <strong>{{ $envio['telefono_contacto'] }}</strong>
                </div>
                @endif
                @if (!empty($envio['estado_envio']))
                <div>
                    <span style="font-size:.75rem;color:var(--muted-color);display:block;font-weight:700;">ESTADO ENVÍO</span>
                    <strong style="color:#2563EB;text-transform:capitalize;">{{ $envio['estado_envio'] }}</strong>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Cuadrito 3: Factura Digital Mini -->
        @if ($factura)
        <div class="factura-mini" id="fac_{{ $uid }}">
            <div class="factura-mini-head">
                <div>
                    <div class="factura-mini-head-title" style="display:flex;align-items:center;gap:.4rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        Los Alpes — Heladería
                    </div>
                    <div class="factura-mini-head-num">Pedido #{{ $p['idpedido'] }} · Factura #{{ $factura['idfactura'] }}</div>
                </div>
                <div style="font-size:.78rem;color:rgba(255,255,255,.6)">{{ $factura['fecha'] ? date('d/m/Y H:i', strtotime($factura['fecha'])) : '' }}</div>
            </div>
            <div class="factura-mini-body">
                <div class="fsec">Detalle de la Compra</div>
                @foreach ($detalles as $d)
                <div class="frow">
                    <span>{{ $d['nombre_producto'] ?? 'Producto' }} &times;{{ $d['cantidad'] }}</span>
                    <span>${{ number_format($d['total'], 0, ',', '.') }}</span>
                </div>
                @endforeach
                <div class="fsec">Pago y Estado</div>
                <div class="frow">
                    <span>Método de Pago</span>
                    <span style="text-transform:capitalize;font-weight:700">{{ $factura['metodopago'] ?? 'Efectivo' }}</span>
                </div>
                <div class="frow">
                    <span>Estado del Pago</span>
                    <span style="color:#2563EB;font-weight:700">
                        {{ $est === 'completado' ? 'Pagado' : 'Pendiente de pago' }}
                    </span>
                </div>
            </div>
            <div class="ftotal">
                <span>Total Facturado</span>
                <span>${{ number_format($factura['totalfactura'], 0, ',', '.') }}</span>
            </div>
        </div>
        <button class="btn-print" onclick="imprimirFactura('fac_{{ $uid }}')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Imprimir factura
        </button>
        @endif

    </div>
</div>
