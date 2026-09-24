@extends('checkout.step_layout')

@php
    $step_title = 'Datos de entrega';
    $step = 2;
    $nombreUser = Auth::check() ? Auth::user()->nombre : '';
@endphp

@section('step_content')
<form method="POST" action="{{ route('checkout.guardarEntrega') }}">
    @csrf

    <label>Nombre completo del destinatario</label>
    <input type="text" name="destinatario" placeholder="Ej: María García" required value="{{ old('destinatario', $nombreUser) }}">

    <label>Dirección de entrega</label>
    <input type="text" name="direccion" placeholder="Ej: Cra 15 #45-32, Apto 201" required value="{{ old('direccion') }}">

    <label>Barrio / Sector</label>
    <input type="text" name="barrio" placeholder="Ej: El Poblado" required value="{{ old('barrio') }}">

    <label>Ciudad</label>
    <div style="background:var(--dir-box-bg);border:1.5px solid var(--dir-box-bd);border-radius:9px;padding:.65rem .9rem;margin-bottom:.85rem;font-size:.9rem;font-weight:700;color:var(--dir-text);display:flex;align-items:center;gap:.35rem;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> Campoalegre
    </div>
    <input type="hidden" name="ciudad" value="Campoalegre">

    <label>Teléfono de contacto</label>
    <input type="tel" name="telefono" placeholder="Ej: 3001234567" required value="{{ old('telefono') }}">

    <label>Instrucciones adicionales <span style="font-weight:400;color:var(--muted-color)">(opcional)</span></label>
    <textarea name="instrucciones" rows="2" placeholder="Timbre roto, llamar al llegar...">{{ old('instrucciones') }}</textarea>

    <div style="background:var(--dir-box-bg);border:1px solid var(--dir-box-bd);border-radius:12px;padding:1rem 1.25rem;margin-top:1.5rem;display:flex;align-items:center;gap:.75rem">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px; color: var(--dir-text); flex-shrink: 0;"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        <div>
            <div style="font-size:.85rem;font-weight:800;color:var(--dir-text)">Entrega a domicilio</div>
            <div style="font-size:.78rem;color:var(--dir-sub)">Tiempo estimado: 30–45 minutos</div>
        </div>
    </div>

    <button type="submit" class="btn-next">Continuar → Método de pago</button>
</form>
@endsection
