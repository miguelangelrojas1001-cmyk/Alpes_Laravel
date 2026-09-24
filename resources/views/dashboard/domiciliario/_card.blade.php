@php
    $est = strtolower($p['estado'] ?? 'pendiente');
    $badgeClass = match($est) {
        'completado' => 'badge-completado',
        'cancelado'  => 'badge-cancelado',
        'en proceso' => 'badge-proceso',
        default      => 'badge-pendiente',
    };
    $estadoLabel = match($est) {
        'en proceso' => 'En proceso',
        'completado' => 'Completado',
        'cancelado'  => 'Cancelado',
        default      => 'Pendiente',
    };
    $uid = 'dom_ped_' . $p['idpedido'];
@endphp

<div class="pedido-card">
    <div class="pedido-head" onclick="togglePedido('{{ $uid }}')">
        <div class="pedido-head-left">
            <div>
                <div class="pedido-num">Pedido #{{ $p['idpedido'] }}</div>
                <div class="pedido-fecha">{{ $p['fecha'] ? date('d/m/Y h:i A', strtotime($p['fecha'])) : '-' }}</div>
            </div>
            <span class="badge {{ $badgeClass }}">{{ $estadoLabel }}</span>
        </div>
        <div style="display:flex;align-items:center;gap:1rem">
            <div class="pedido-total">${{ number_format($p['total'], 0, ',', '.') }}</div>
            <span class="chevron" id="chev_{{ $uid }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </span>
        </div>
    </div>

    <div class="pedido-body" id="body_{{ $uid }}">
        @if (!empty($p['direccion_completa']))
        <div class="dir-box">
            <div class="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:20px;height:20px;color:#2563EB"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
                <div class="dir-text">{{ $p['direccion_completa'] }}</div>
            </div>
        </div>
        @endif

        <div class="pedido-info-grid">
            <div class="info-block">
                <div class="info-block-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Cliente
                </div>
                <div class="info-row"><span class="lbl">Nombre</span><span class="val">{{ $p['cliente'] ?? '-' }}</span></div>
                @if (!empty($p['telefono_contacto']))
                <div class="info-row"><span class="lbl">Teléfono</span><span class="val">{{ $p['telefono_contacto'] }}</span></div>
                @endif
                @if (!empty($p['obs_envio']))
                <div class="info-row"><span class="lbl">Observaciones</span><span class="val">{{ $p['obs_envio'] }}</span></div>
                @endif
            </div>
            <div class="info-block">
                <div class="info-block-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    Pedido
                </div>
                <div class="info-row"><span class="lbl"># Pedido</span><span class="val">#{{ $p['idpedido'] }}</span></div>
                <div class="info-row"><span class="lbl">Total</span><span class="val" style="color:#2563EB">${{ number_format($p['total'], 0, ',', '.') }}</span></div>
                <div class="info-row"><span class="lbl">Estado</span><span class="val">{{ $estadoLabel }}</span></div>
            </div>
        </div>

        @if (!empty($detalles))
        <div class="productos-list">
            <div style="font-size:.72rem;font-weight:800;color:var(--muted-color);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem">Productos del pedido</div>
            @foreach ($detalles as $d)
            <div class="prod-item">
                <div>
                    <div class="pnombre">{{ $d['nombre_producto'] ?? 'Producto' }} &times;{{ $d['cantidad'] }}</div>
                    @if (!empty($d['sabores']))
                        <div class="psabores">Toppings: {{ $d['sabores'] }}</div>
                    @endif
                    @if (!empty($d['tamano']))
                        <div class="psabores">Tamaño: {{ $d['tamano'] }}</div>
                    @endif
                    @if (!empty($d['observaciones']))
                        <div class="psabores" style="color:#F59E0B">Obs: {{ $d['observaciones'] }}</div>
                    @endif
                </div>
                <div class="pprecio">${{ number_format($d['total'], 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
        @endif

        @if (!$esHistorial)
        <div class="acciones">
            @if ($est === 'pendiente')
            <form method="POST" action="{{ route('domiciliario.actualizarEstado') }}" style="flex:1">
                @csrf
                <input type="hidden" name="idpedido" value="{{ $p['idpedido'] }}">
                <button type="submit" name="marcar_en_camino" value="1" class="btn-accion btn-camino" style="width:100%; display:inline-flex; align-items:center; justify-content:center; gap:.4rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/><path d="M3 17h12c1.7 0 3-1.3 3-3V8c0-1.7-1.3-3-3-3H9L5 9H1v4h2v4zm15-7h4v4h-4v-4z"/></svg>
                    Marcar en camino
                </button>
            </form>
            @endif

            <form method="POST" action="{{ route('domiciliario.actualizarEstado') }}" style="flex:1">
                @csrf
                <input type="hidden" name="idpedido" value="{{ $p['idpedido'] }}">
                <button type="submit" name="marcar_entregado" value="1" class="btn-accion btn-entregado" style="width:100%; display:inline-flex; align-items:center; justify-content:center; gap:.4rem;"
                        onclick="return confirm('¿Confirmar entrega del pedido #{{ $p['idpedido'] }}?')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Confirmar entrega
                </button>
            </form>
        </div>
        @endif

    </div>
</div>
