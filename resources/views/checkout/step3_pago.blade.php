@extends('checkout.step_layout')

@php
    $step_title = 'Método de pago';
    $step = 3;

    $modo      = session('checkout_modo', 'individual');
    $orden     = session('checkout_orden', []);
    $carrito   = session('carrito', session('checkout_carrito_snapshot', []));
    $domicilio = 2000;

    if ($modo === 'carrito') {
        $subtotal = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $carrito));
    } else {
        $subtotal  = ($orden['precio_unitario'] ?? 0) * ($orden['cantidad'] ?? 1);
        $domicilio = (int)($orden['costo_domicilio'] ?? 2000);
    }
    $total = $subtotal + $domicilio;
    $error = session('checkout_error');
@endphp

@section('step_content')
<style>
.pago-opcion {
    border: 2px solid var(--pago-opcion-bd); border-radius: 14px; padding: 1rem 1.25rem;
    display: flex; align-items: center; gap: 1rem; cursor: pointer; margin-bottom: .75rem;
    transition: border-color .15s, background .15s; background: var(--pago-opcion-bg);
}
.pago-opcion:has(input:checked),
.pago-opcion.selected { border-color: #2563EB; background: rgba(37,99,235,.12); box-shadow: 0 0 0 3px rgba(37,99,235,.2); }
.pago-opcion input { display: none; }
.pago-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; background: var(--pago-icon-bg); flex-shrink: 0; transition: background .25s; }
.pago-name { font-weight: 800; color: var(--heading-color); font-size: .92rem; }
.pago-desc { font-size: .78rem; color: var(--muted-color); }
.resumen-box { background: var(--resumen-bg); border-radius: 14px; padding: 1.1rem 1.25rem; margin-bottom: 1.5rem; border: 1.5px solid var(--resumen-bd); transition: background .25s, border-color .25s; }
.resumen-row { display: flex; justify-content: space-between; font-size: .88rem; color: var(--body-color); margin-bottom: .4rem; }
.resumen-total { display: flex; justify-content: space-between; padding-top: .65rem; border-top: 1.5px solid var(--card-border); font-size: 1.05rem; font-weight: 900; color: var(--heading-color); }
</style>

<!-- Resumen del pedido -->
<div class="resumen-box">
    <div style="font-size:.82rem;font-weight:800;color:var(--muted-color);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.75rem">Resumen del pedido</div>

    @if ($modo === 'carrito')
        @foreach ($carrito as $item)
        <div class="resumen-row">
            <span>{{ $item['nombre'] }}</span>
            <span>{{ $item['cantidad'] }} × ${{ number_format($item['precio'], 0, ',', '.') }}</span>
        </div>
        @endforeach
    @else
        <div class="resumen-row">
            <span>{{ $orden['nombre_producto'] ?? 'Producto' }}</span>
            <span>{{ $orden['cantidad'] ?? 1 }} × ${{ number_format($orden['precio_unitario'] ?? 0, 0, ',', '.') }}</span>
        </div>
        @if (!empty($orden['sabores']))
        <div class="resumen-row">
            <span>{{ !empty($orden['es_yogurt']) ? 'Toppings' : 'Sabores' }}</span>
            <span style="font-size:.8rem;max-width:55%;text-align:right">{{ $orden['sabores'] }}</span>
        </div>
        @endif
        @if (!empty($orden['tamano']))
        <div class="resumen-row">
            <span>Tamaño</span>
            <span style="text-transform:capitalize">{{ $orden['tamano'] }}</span>
        </div>
        @endif
        @if (!empty($orden['es_yogurt']) && !empty($orden['costo_toppings']))
        <div class="resumen-row" style="font-size:.8rem;color:#64748B">
            <span>Base del producto</span>
            <span>${{ number_format($orden['precio_base'] ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="resumen-row" style="font-size:.8rem;color:#64748B">
            <span>Toppings adicionales</span>
            <span>${{ number_format($orden['costo_toppings'], 0, ',', '.') }}</span>
        </div>
        @endif
    @endif

    <div class="resumen-row">
        <span>Entrega a domicilio</span>
        <span style="color:#F59E0B;font-weight:700">${{ number_format($domicilio, 0, ',', '.') }}</span>
    </div>
    <div class="resumen-total">
        <span>Total a pagar</span>
        <span style="color:#2563EB">${{ number_format($total, 0, ',', '.') }}</span>
    </div>
</div>

<!-- Mensajes de Error -->
@if ($error)
<div style="background:#FEF2F2;border:1.5px solid #FECACA;color:#991B1B;border-radius:10px;padding:.75rem 1rem;font-size:.88rem;margin-bottom:1rem;display:flex;align-items:center;gap:.35rem;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; flex-shrink: 0;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> {{ $error }}
</div>
@endif

<div style="margin-bottom:.5rem;font-size:.8rem;font-weight:800;color:var(--label-color);text-transform:uppercase;letter-spacing:.05em">Elige tu método de pago</div>

<form method="POST" action="{{ route('checkout.confirmar') }}" id="pagoForm">
    @csrf

    <label class="pago-opcion" onclick="selectPago(this,'nequi')">
        <input type="radio" name="metodopago" value="nequi">
        <div class="pago-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
        </div>
        <div>
            <div class="pago-name">Nequi</div>
            <div class="pago-desc">Transferencia instantánea</div>
        </div>
    </label>

    <label class="pago-opcion" onclick="selectPago(this,'daviplata')">
        <input type="radio" name="metodopago" value="daviplata">
        <div class="pago-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
        <div>
            <div class="pago-name">Daviplata</div>
            <div class="pago-desc">Billetera virtual Davivienda</div>
        </div>
    </label>

    <label class="pago-opcion" onclick="selectPago(this,'tarjeta')">
        <input type="radio" name="metodopago" value="tarjeta">
        <div class="pago-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div>
            <div class="pago-name">Tarjeta crédito / débito</div>
            <div class="pago-desc">Visa, Mastercard, American Express</div>
        </div>
    </label>

    <button type="submit" class="btn-next" id="btnPagar" disabled>Confirmar y pagar ${{ number_format($total, 0, ',', '.') }} →</button>
</form>

<script>
function selectPago(lbl, val) {
    document.querySelectorAll('.pago-opcion').forEach(l => l.classList.remove('selected'));
    lbl.classList.add('selected');
    lbl.querySelector('input').checked = true;
    document.getElementById('btnPagar').disabled = false;
}
</script>
@endsection
