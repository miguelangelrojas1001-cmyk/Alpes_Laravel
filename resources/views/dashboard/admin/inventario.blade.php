@extends('layouts.app')

@section('title', 'Inventario de Insumos - Panel de Administración')

@section('content')
<style>
    .admin-header { margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.6rem; font-weight: 900; color: var(--heading-color); margin-bottom: .3rem; }
    .admin-header p { color: var(--muted-color); font-size: .93rem; }

    /* Tabs estilo Reportes */
    .module-tabs { display: flex; gap: .35rem; border-bottom: 2px solid var(--tab-border); margin-bottom: 1.5rem; }
    .module-tab {
        padding: .65rem 1.3rem; font-size: .9rem; font-weight: 700;
        color: var(--tab-txt); background: none; border: none; cursor: pointer;
        border-bottom: 2px solid transparent; margin-bottom: -2px;
        font-family: 'Nunito', sans-serif; transition: color .15s, border-color .15s;
        display: inline-flex; align-items: center; gap: .45rem;
    }
    .module-tab:hover { color: #2563EB; }
    .module-tab.active { color: #2563EB; border-bottom-color: #2563EB; }
    .module-tab-panel { display: none; }
    .module-tab-panel.active { display: block; }

    .card { background: var(--card-bg); border-radius: 16px; box-shadow: var(--card-shadow); padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid var(--card-border); transition: background .25s, border-color .25s; }
    .card-title { font-size: 1.05rem; font-weight: 800; color: var(--heading-color); margin-bottom: 1.25rem; }
    .grid-forms { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    @media(max-width:850px){ .grid-forms { grid-template-columns: 1fr; } }
    
    label { display: block; font-size: .8rem; font-weight: 700; color: var(--label-color); margin-bottom: .35rem; }
    input, select {
        width: 100%; padding: .65rem .9rem; border: 1.5px solid var(--input-border);
        border-radius: 9px; font-family: 'Nunito', sans-serif; font-size: .9rem;
        color: var(--input-color); background: var(--input-bg); outline: none;
        margin-bottom: .85rem; transition: background .25s, border-color .25s, color .25s;
    }
    select option {
        background: var(--card-bg);
        color: var(--heading-color);
        padding: .5rem;
    }
    input:focus, select:focus { border-color: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,.1); background: var(--input-focus-bg); }
    
    .btn { padding: .65rem 1.4rem; border-radius: 9px; border: none; cursor: pointer; font-family: 'Nunito', sans-serif; font-weight: 700; font-size: .88rem; transition: .2s; }
    .btn-primary { background: #2563EB; color: #fff; }
    .btn-primary:hover { background: #1D4ED8; }
    .btn-danger  { background: #EF4444; color: #fff; font-size: .78rem; padding: .35rem .8rem; border-radius: 7px; border: none; cursor: pointer; font-family: 'Nunito', sans-serif; font-weight: 700; }
    .btn-success { background: #10B981; color: #fff; }
    .btn-success:hover { background: #059669; }
    .btn-warning { background: #F59E0B; color: #fff; }
    .btn-warning:hover { background: #D97706; }

    table { width: 100%; border-collapse: collapse; }
    th { background: var(--table-head-bg); font-size: .78rem; font-weight: 800; color: var(--table-head-txt); text-transform: uppercase; letter-spacing: .05em; padding: .75rem 1rem; text-align: left; border-bottom: 1px solid var(--card-border); }
    td { padding: .75rem 1rem; font-size: .88rem; color: var(--body-color); border-bottom: 1px solid var(--table-row-bd); vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: var(--table-hover); }

    .badge { display: inline-block; padding: .25rem .75rem; border-radius: 20px; font-size: .75rem; font-weight: 700; }
    .badge-ok      { background: #D1FAE5; color: #065F46; }
    .badge-low     { background: #FEF3C7; color: #92400E; }
    .badge-out     { background: #FEE2E2; color: #991B1B; }
    .badge-entrada { background: #DBEAFE; color: #1E40AF; }
    .badge-salida  { background: #FCE7F3; color: #9D174D; }
    .badge-cat     { background: #EDE9FE; color: #5B21B6; font-size: .72rem; padding: .2rem .6rem; }
    
    .alert-ok  { background: var(--alert-ok-bg); border: 1px solid var(--alert-ok-bd); color: var(--alert-ok-txt); padding: .75rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: .88rem; }
    .alert-err { background: var(--alert-err-bg); border: 1px solid var(--alert-err-bd); color: var(--alert-err-txt); padding: .75rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: .88rem; }
    .msg-row { display: flex; gap: 1rem; margin-bottom: 0; }
    .msg-row > div { flex: 1; }
    .w-full { width: 100%; margin-top: .5rem; }
    
    .filter-bar { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
    .filter-bar span { font-size: .78rem; font-weight: 800; color: var(--muted-color); text-transform: uppercase; letter-spacing: .05em; margin-right: .25rem; }
    .filter-btn { padding: .35rem .95rem; border-radius: 20px; border: 1.5px solid var(--filter-btn-bd); background: var(--filter-btn-bg); color: var(--filter-btn-txt); font-family: 'Nunito', sans-serif; font-size: .8rem; font-weight: 700; cursor: pointer; transition: .15s; white-space: nowrap; }
    .filter-btn:hover { border-color: #2563EB; color: #2563EB; background: #EFF6FF; }
    .filter-btn.active { background: #2563EB; color: #fff; border-color: #2563EB; }
</style>

<div class="admin-header">
    <h1 style="display:flex;align-items:center;gap:.5rem;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 26px; height: 26px;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
        <span>Inventario de Insumos</span>
    </h1>
    <p>Gestiona el stock de insumos, crea nuevos ingredientes y registra movimientos de entradas y salidas.</p>
</div>

@if (session('error'))
    <div class="alert-err" style="display:flex;align-items:center;gap:.35rem;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; flex-shrink: 0;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <span>{{ session('error') }}</span>
    </div>
@endif

<!-- Pestañas del Módulo -->
<div class="module-tabs">
    <button class="module-tab active" id="tab-btn-stock" onclick="switchTab('tab-stock', this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><polygon points="12 22.08 12 12 3 6.92 3 17.08 12 22.08"/><polygon points="12 22.08 12 12 21 6.92 21 17.08 12 22.08"/><polygon points="12 12 3 6.92 12 1.85 21 6.92 12 12"/></svg>
        Insumos en Stock ({{ count($insumos) }})
    </button>
    <button class="module-tab" id="tab-btn-registro" onclick="switchTab('tab-registro', this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nuevo Insumo / Registrar Movimiento
    </button>
    <button class="module-tab" id="tab-btn-movimientos" onclick="switchTab('tab-movimientos', this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        Movimientos ({{ count($movimientos) }})
    </button>
</div>

<!-- PANEL 1: Insumos en Stock -->
<div class="module-tab-panel active" id="tab-stock">
    <div class="card">
        <div class="card-title">Listado de Insumos en Stock ({{ count($insumos) }})</div>

        @php
            $categoriasInsumos = [
                'Sabores de Helado',
                'Toppings Dulces',
                'Toppings Frutas',
                'Toppings Premium',
                'Salsas y Untables',
                'Waffles',
                'Canastas',
                'General'
            ];
        @endphp

        <!-- Filtros por categoría (SIN filtro 'Todas') -->
        <div class="filter-bar">
            <span>Categorías:</span>
            @foreach ($categoriasInsumos as $idx => $catName)
                <button class="filter-btn {{ $idx === 0 ? 'active' : '' }}" onclick="filtrarCategoriaInsumos('{{ $catName }}', this)">
                    {{ $catName }}
                </button>
            @endforeach
        </div>

        <div style="overflow-x:auto">
            <table id="tabla-insumos">
                <thead>
                    <tr>
                        <th>Insumo</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Mínimo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($insumos as $ins)
                    <tr data-categoria="{{ $ins->categoria ?? 'General' }}">
                        <td>
                            <strong>{{ $ins->nombre }}</strong><br>
                            <small style="color:var(--muted-color); font-weight:600;">Unidad: {{ $ins->unidad_medida }}</small>
                        </td>
                        <td>
                            <span class="badge badge-cat">{{ $ins->categoria ?? 'General' }}</span>
                        </td>
                        <td><strong>{{ number_format($ins->stock_actual, 2) }}</strong> {{ $ins->unidad_medida }}</td>
                        <td>{{ number_format($ins->stock_minimo, 2) }} {{ $ins->unidad_medida }}</td>
                        <td>
                            @if ($ins->stock_actual <= 0)
                                <span class="badge badge-out">Sin stock</span>
                            @elseif ($ins->stock_actual <= $ins->stock_minimo)
                                <span class="badge badge-low">Stock bajo</span>
                            @else
                                <span class="badge badge-ok">OK</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.inventario.insumo.eliminar', ['id' => $ins->idinsumo]) }}" style="display:inline" onsubmit="return confirm('¿Eliminar este insumo? Se eliminarán también sus movimientos.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" style="display:inline-flex;align-items:center;gap:.25rem;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 12px; height: 12px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if (empty($insumos))
                        <tr><td colspan="6" style="text-align:center;color:var(--muted-color);padding:2rem">Sin insumos registrados</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- PANEL 2: Nuevo Insumo & Registrar Movimiento -->
<div class="module-tab-panel" id="tab-registro">
    <div class="grid-forms">
        <!-- Formulario 1: Nuevo Insumo -->
        <div class="card">
            <div class="card-title" style="display:flex;align-items:center;gap:.35rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>Crear Nuevo Insumo</span>
            </div>
            <form method="POST" action="{{ route('admin.inventario.insumo.guardar') }}">
                @csrf
                <label>Nombre del Insumo</label>
                <input type="text" name="nombre" placeholder="Ej: Fresa Picada" required>
                
                <label>Categoría del Insumo</label>
                <select name="categoria">
                    <option value="Sabores de Helado">Sabores de Helado</option>
                    <option value="Toppings Dulces">Toppings Dulces</option>
                    <option value="Toppings Frutas">Toppings Frutas</option>
                    <option value="Toppings Premium">Toppings Premium</option>
                    <option value="Salsas y Untables">Salsas y Untables</option>
                    <option value="Waffles">Waffles</option>
                    <option value="Canastas">Canastas</option>
                    <option value="General">General</option>
                </select>

                <div class="msg-row">
                    <div>
                        <label>Stock Inicial</label>
                        <input type="number" name="stock_actual" step="0.01" min="0" placeholder="0">
                    </div>
                    <div>
                        <label>Stock Mínimo (Alerta)</label>
                        <input type="number" name="stock_minimo" step="0.01" min="0" placeholder="0">
                    </div>
                </div>

                <label>Unidad de Medida</label>
                <select name="unidad_medida">
                    <option value="kg">kg (Kilogramos)</option>
                    <option value="g">g (Gramos)</option>
                    <option value="L">L (Litros)</option>
                    <option value="ml">ml (Mililitros)</option>
                    <option value="unidad">unidad (Unidades)</option>
                </select>

                <button type="submit" class="btn btn-primary w-full">+ Registrar Insumo</button>
            </form>
        </div>

        <!-- Formulario 2: Registrar Movimiento -->
        <div class="card">
            <div class="card-title" style="display:flex;align-items:center;gap:.35rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                <span>Registrar Entrada / Salida</span>
            </div>
            <form method="POST" action="{{ route('admin.inventario.movimiento') }}">
                @csrf
                <input type="hidden" name="accion" id="accion_mov" value="entrada">
                
                <label>Seleccionar Insumo</label>
                <select name="idinsumo" required style="font-weight:600;">
                    <option value="" style="color:var(--muted-color)">-- Elegir insumo a modificar --</option>
                    @foreach ($insumos as $ins)
                        <option value="{{ $ins->idinsumo }}">
                            {{ $ins->nombre }} (Stock actual: {{ number_format($ins->stock_actual, 2) }} {{ $ins->unidad_medida }}) [{{ $ins->categoria }}]
                        </option>
                    @endforeach
                </select>

                <label>Cantidad a Ingresar o Descontar</label>
                <input type="number" name="cantidad" step="0.01" min="0.01" placeholder="Ej: 5.0" required>

                <div style="display:flex; gap:.75rem; margin-top:.75rem">
                    <button type="submit" class="btn btn-success" style="flex:1; display:inline-flex; align-items:center; justify-content:center; gap:.35rem;"
                            onclick="document.getElementById('accion_mov').value='entrada'">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><polyline points="18 15 12 9 6 15"/></svg>
                        Registrar Entrada
                    </button>
                    <button type="submit" class="btn btn-warning" style="flex:1; display:inline-flex; align-items:center; justify-content:center; gap:.35rem;"
                            onclick="document.getElementById('accion_mov').value='salida'">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><polyline points="6 9 12 15 18 9"/></svg>
                        Registrar Salida
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PANEL 3: Movimientos -->
<div class="module-tab-panel" id="tab-movimientos">
    <div class="card">
        <div class="card-title" style="display:flex;align-items:center;gap:.35rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            <span>Histórico de Movimientos de Inventario</span>
        </div>
        <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr><th>Insumo</th><th>Tipo</th><th>Cantidad</th><th>Fecha y Hora</th></tr>
                </thead>
                <tbody>
                    @foreach ($movimientos as $mov)
                    <tr>
                        <td><strong>{{ $mov->insumo_nombre ?? '-' }}</strong></td>
                        <td>
                            @if ($mov->tipo_entrada)
                                <span class="badge badge-entrada" style="display:inline-flex;align-items:center;gap:.25rem;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width: 11px; height: 11px;"><polyline points="18 15 12 9 6 15"/></svg>
                                    Entrada
                                </span>
                            @else
                                <span class="badge badge-salida" style="display:inline-flex;align-items:center;gap:.25rem;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width: 11px; height: 11px;"><polyline points="6 9 12 15 18 9"/></svg>
                                    Salida
                                </span>
                            @endif
                        </td>
                        <td><strong>{{ number_format($mov->cantidad, 2) }}</strong></td>
                        <td>{{ $mov->fecha ? date('d/m/Y H:i A', strtotime($mov->fecha)) : '-' }}</td>
                    </tr>
                    @endforeach
                    @if (empty($movimientos))
                        <tr><td colspan="4" style="text-align:center;color:var(--muted-color);padding:2rem">Sin movimientos registrados</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Switch de Tabs
function switchTab(panelId, btn) {
    document.querySelectorAll('.module-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.module-tab-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    const panel = document.getElementById(panelId);
    if (panel) panel.classList.add('active');
}

// Filtro de categorías en tabla de insumos (sin 'Todas')
function filtrarCategoriaInsumos(catNombre, btn) {
    document.querySelectorAll('.filter-bar .filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('#tabla-insumos tbody tr[data-categoria]').forEach(row => {
        const rowCat = row.getAttribute('data-categoria');
        if (rowCat === catNombre) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Iniciar filtro en la primera categoría activa al cargar
document.addEventListener('DOMContentLoaded', function() {
    const primerBtn = document.querySelector('.filter-bar .filter-btn.active');
    if (primerBtn) {
        const cat = primerBtn.innerText.trim();
        filtrarCategoriaInsumos(cat, primerBtn);
    }
});
</script>
@endsection
