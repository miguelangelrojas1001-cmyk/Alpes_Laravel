@extends('layouts.app')

@section('title', 'Productos y Recetas - Panel de Administración')

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

    /* Layout 2 Columnas para Formulario + Receta */
    .prod-form-layout { display: grid; grid-template-columns: 380px 1fr; gap: 1.5rem; align-items: start; }
    @media(max-width:960px){ .prod-form-layout { grid-template-columns: 1fr; } }

    .card { background: var(--card-bg); border-radius: 16px; box-shadow: var(--card-shadow); padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid var(--card-border); transition: background .25s, border-color .25s; }
    .card-title { font-size: 1.05rem; font-weight: 800; color: var(--heading-color); margin-bottom: 1.25rem; display: flex; align-items: center; gap: .4rem; }
    label { display: block; font-size: .82rem; font-weight: 700; color: var(--label-color); margin-bottom: .35rem; }
    input, select, textarea {
        width: 100%; padding: .65rem .9rem; border: 1.5px solid var(--input-border);
        border-radius: 10px; font-family: 'Nunito', sans-serif; font-size: .9rem;
        background: var(--input-bg); outline: none; margin-bottom: .85rem;
        color: var(--input-color); transition: background .25s, border-color .25s, color .25s;
    }
    select option { background: var(--card-bg); color: var(--heading-color); padding: .4rem; }
    input:focus, select:focus, textarea:focus { border-color: #2563EB; background: var(--input-focus-bg); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
    
    .btn-primary { background: #2563EB; color: #fff; width: 100%; padding: .75rem; border-radius: 10px; border: none; cursor: pointer; font-family: 'Nunito', sans-serif; font-weight: 800; font-size: .95rem; margin-top: .5rem; transition: background .15s; }
    .btn-primary:hover { background: #1D4ED8; }
    .btn-secondary { background: var(--btn-sec-bg); color: var(--btn-sec-txt); width: 100%; padding: .65rem; border-radius: 10px; border: none; cursor: pointer; font-family: 'Nunito', sans-serif; font-weight: 700; font-size: .88rem; margin-top: .4rem; text-decoration: none; display: block; text-align: center; transition: background .15s; }
    .btn-secondary:hover { background: var(--btn-sec-hover); }
    
    .badge { display: inline-block; padding: .25rem .75rem; border-radius: 20px; font-size: .75rem; font-weight: 700; }
    .badge-ok { background: #D1FAE5; color: #065F46; }
    .badge-no { background: #FEE2E2; color: #991B1B; }
    .alert-ok { background: var(--alert-ok-bg); border: 1px solid var(--alert-ok-bd); color: var(--alert-ok-txt); padding: .75rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: .88rem; }
    .alert-err { background: var(--alert-err-bg); border: 1px solid var(--alert-err-bd); color: var(--alert-err-txt); padding: .75rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: .88rem; }
    
    .prod-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem; }
    .prod-card { border: 1.5px solid var(--card-border); border-radius: 14px; overflow: hidden; transition: box-shadow .2s, border-color .25s; background: var(--card-bg); }
    .prod-card:hover { box-shadow: 0 6px 22px rgba(0,0,0,.15); }
    .prod-img { width: 100%; height: 135px; background: var(--prod-img-bg); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; overflow: hidden; transition: background .25s; }
    .prod-img img { width: 100%; height: 100%; object-fit: cover; }
    .prod-body { padding: .95rem; }
    .prod-name { font-weight: 800; color: var(--heading-color); font-size: .95rem; }
    .prod-cat { font-size: .75rem; color: var(--muted-color); font-weight: 600; margin-top: .15rem; }
    .prod-price { font-size: 1.05rem; font-weight: 900; color: #2563EB; margin-top: .4rem; }
    .prod-foot { display: flex; justify-content: space-between; align-items: center; margin-top: .75rem; gap: .4rem; flex-wrap: wrap; }
    .btn-del { background: #FEE2E2; color: #EF4444; border: none; border-radius: 8px; padding: .35rem .75rem; font-size: .78rem; font-weight: 700; cursor: pointer; font-family: 'Nunito', sans-serif; text-decoration: none; }
    .btn-edit { background: #EFF6FF; color: #2563EB; border: none; border-radius: 8px; padding: .35rem .75rem; font-size: .78rem; font-weight: 700; cursor: pointer; font-family: 'Nunito', sans-serif; text-decoration: none; }
    .edit-banner { background: var(--edit-banner-bg); border: 1.5px solid var(--edit-banner-bd); border-radius: 10px; padding: .75rem 1rem; margin-bottom: 1rem; font-size: .88rem; color: var(--edit-banner-txt); font-weight: 700; }
    
    .filter-bar { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; margin-bottom: 1.3rem; }
    .filter-bar span { font-size: .78rem; font-weight: 800; color: var(--muted-color); text-transform: uppercase; letter-spacing: .05em; }
    .filter-btn { padding: .35rem .95rem; border-radius: 20px; border: 1.5px solid var(--filter-btn-bd); background: var(--filter-btn-bg); color: var(--filter-btn-txt); font-family: 'Nunito', sans-serif; font-size: .8rem; font-weight: 700; cursor: pointer; transition: .15s; white-space: nowrap; }
    .filter-btn:hover { border-color: #2563EB; color: #2563EB; background: #EFF6FF; }
    .filter-btn.active { background: #2563EB; color: #fff; border-color: #2563EB; }

    /* Estilos del Recetario Agrupado */
    .recipe-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: .85rem; }
    .recipe-search { font-size: .85rem; padding: .55rem .85rem; margin-bottom: .85rem; border-radius: 9px; }
    .cat-group { border: 1px solid var(--card-border); border-radius: 10px; margin-bottom: .75rem; overflow: hidden; background: var(--card-bg); }
    .cat-group-header { display: flex; align-items: center; justify-content: space-between; padding: .65rem .9rem; background: var(--table-head-bg); cursor: pointer; user-select: none; font-size: .86rem; font-weight: 800; color: var(--heading-color); }
    .cat-group-header:hover { background: rgba(37,99,235,.08); }
    .cat-group-body { padding: .5rem .75rem; display: block; max-height: 280px; overflow-y: auto; }
    .insumo-item-row { display: flex; align-items: center; justify-content: space-between; padding: .4rem .5rem; border-bottom: 1px solid var(--table-row-bd); font-size: .84rem; border-radius: 7px; transition: background .15s; }
    .insumo-item-row:last-child { border-bottom: none; }
    .insumo-item-row.has-qty { background: rgba(37,99,235,.12); border-color: rgba(37,99,235,.25); }
    .insumo-item-row .iname { font-weight: 700; color: var(--heading-color); max-width: 65%; line-height: 1.25; }
    .insumo-item-row .iunit { font-size: .72rem; color: var(--muted-color); font-weight: 600; }
    .insumo-item-row input { width: 90px; margin-bottom: 0; padding: .3rem .5rem; font-size: .85rem; text-align: center; font-weight: 800; }
</style>

<div class="admin-header">
    <h1 style="display:flex;align-items:center;gap:.5rem;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 26px; height: 26px;"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><polygon points="12 22.08 12 12 3 6.92 3 17.08 12 22.08"/><polygon points="12 22.08 12 12 21 6.92 21 17.08 12 22.08"/><polygon points="12 12 3 6.92 12 1.85 21 6.92 12 12"/></svg>
        <span>Productos y Recetas</span>
    </h1>
    <p>Agrega y gestiona los productos de la heladería y su receta de insumos.</p>
</div>

@php $mensaje = session('mensaje'); @endphp
@if (!empty($mensaje))
    <div class="{{ str_contains($mensaje, 'Error') ? 'alert-err' : 'alert-ok' }}" style="display:flex;align-items:center;gap:.35rem;">
        @if (str_contains($mensaje, 'Error'))
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; flex-shrink: 0;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        @else
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; flex-shrink: 0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        @endif
        <span>{{ $mensaje }}</span>
    </div>
@endif

<!-- Pestañas del Módulo -->
<div class="module-tabs">
    <button class="module-tab {{ $productoEditar ? 'active' : '' }}" id="tab-btn-form" onclick="switchTab('tab-form', this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        {{ $productoEditar ? 'Editar Producto' : 'Nuevo Producto' }}
    </button>
    <button class="module-tab {{ !$productoEditar ? 'active' : '' }}" id="tab-btn-lista" onclick="switchTab('tab-lista', this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Productos Registrados ({{ count($productos) }})
    </button>
</div>

<!-- PANEL 1: Formulario Nuevo / Editar Producto (DISTRIBUCIÓN 2 COLUMNAS) -->
<div class="module-tab-panel {{ $productoEditar ? 'active' : '' }}" id="tab-form">
    <form method="POST" action="{{ route('admin.productos.guardar') }}" enctype="multipart/form-data">
        @csrf
        @if ($productoEditar)
            <input type="hidden" name="_editar" value="{{ $productoEditar['idproducto'] }}">
        @endif

        <div class="prod-form-layout">
            <!-- COLUMNA IZQUIERDA: Datos del Producto -->
            <div class="card">
                @if ($productoEditar)
                    <div class="edit-banner" style="display:flex;align-items:center;gap:.35rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        <span>Editando: {{ $productoEditar['nombre'] }}</span>
                    </div>
                    <div class="card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; color:#2563EB;"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        <span>Editar Producto</span>
                    </div>
                @else
                    <div class="card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; color:#2563EB;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span>Información del Producto</span>
                    </div>
                @endif

                <label>Categoría</label>
                <select name="idcatalogo" required>
                    <option value="">Seleccionar categoría</option>
                    @foreach ($catalogos as $cat)
                        <option value="{{ $cat['idcatalogo'] }}" {{ ($productoEditar && $cat['idcatalogo'] == $productoEditar['idcatalogo']) ? 'selected' : '' }}>
                            {{ $cat['nombre'] }}
                        </option>
                    @endforeach
                </select>

                <label>Nombre del Producto</label>
                <input type="text" name="nombre" placeholder="Ej: Waffle Supremo" value="{{ $productoEditar['nombre'] ?? '' }}" required>

                <label>Descripción</label>
                <textarea name="descripcion" rows="3" placeholder="Descripción breve...">{{ $productoEditar['descripcion'] ?? '' }}</textarea>

                <label>Precio (en pesos, ej: 15000)</label>
                <input type="number" name="precio" step="1" min="0" placeholder="15000" value="{{ $productoEditar['precio'] ?? '' }}" required>

                <label>Imagen del Producto {!! $productoEditar ? '<span style="font-weight:400;color:var(--muted-color)">(vacío = mantener actual)</span>' : '' !!}</label>
                <input type="file" name="imagen" accept="image/*">

                <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;margin-bottom:1.25rem">
                    <input type="checkbox" name="disponible" {{ (!$productoEditar || $productoEditar['disponible']) ? 'checked' : '' }} style="width:auto;margin-bottom:0">
                    <span>Producto disponible para venta</span>
                </label>

                <button type="submit" class="btn-primary">
                    {{ $productoEditar ? 'Guardar Cambios' : 'Guardar Producto' }}
                </button>
                @if ($productoEditar)
                    <a href="{{ route('admin.productos.index') }}" class="btn-secondary">Cancelar Edición</a>
                @endif
            </div>

            <!-- COLUMNA DERECHA: Receta (Insumos Requeridos) -->
            <div class="card">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; color:#2563EB;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    <span>Receta (Insumos Requeridos)</span>
                </div>

                <p style="font-size:.82rem; color:var(--muted-color); margin-bottom:.85rem;">
                    Asigna la cantidad de insumos que requiere este producto para ser preparado.
                </p>

                <input type="text" class="recipe-search" id="recipeSearch" placeholder="🔍 Buscar insumo en la receta..." onkeyup="filtrarInsumosReceta()">

                <div id="recipeCategoriesContainer">
                    @foreach ($insumosGrouped as $catName => $items)
                        @php
                            $selectedCount = 0;
                            foreach($items as $it) {
                                if (!empty($insumosProducto[$it['idinsumo']]) && (float)$insumosProducto[$it['idinsumo']] > 0) {
                                    $selectedCount++;
                                }
                            }
                        @endphp
                        <div class="cat-group" data-catname="{{ strtolower($catName) }}">
                            <div class="cat-group-header" onclick="toggleCatGroup(this)">
                                <div style="display:flex;align-items:center;gap:.4rem;">
                                    @if(str_contains(strtolower($catName), 'helado')) 🍦
                                    @elseif(str_contains(strtolower($catName), 'fruta')) 🍓
                                    @elseif(str_contains(strtolower($catName), 'dulce')) 🍬
                                    @elseif(str_contains(strtolower($catName), 'premium')) ⭐
                                    @elseif(str_contains(strtolower($catName), 'salsa') || str_contains(strtolower($catName), 'untable')) 🍯
                                    @elseif(str_contains(strtolower($catName), 'waffle')) 🧇
                                    @elseif(str_contains(strtolower($catName), 'canasta')) 🧺
                                    @else 📦
                                    @endif
                                    <span>{{ $catName }}</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:.4rem;">
                                    <span class="badge {{ $selectedCount > 0 ? 'badge-ok' : '' }}" style="font-size:.7rem;padding:.15rem .5rem;" id="badge-count-{{ Str::slug($catName) }}">{{ $selectedCount }} selec.</span>
                                    <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; transition: transform .2s;"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                            </div>
                            <div class="cat-group-body">
                                @foreach ($items as $ins)
                                    @php
                                        $cant = $insumosProducto[$ins['idinsumo']] ?? '';
                                        $hasVal = (!empty($cant) && (float)$cant > 0);
                                    @endphp
                                    <div class="insumo-item-row {{ $hasVal ? 'has-qty' : '' }}" data-insname="{{ strtolower($ins['nombre']) }}">
                                        <div class="iname">
                                            {{ $ins['nombre'] }}
                                            <div class="iunit">{{ $ins['unidad_medida'] }}</div>
                                        </div>
                                        <div>
                                            <input type="number"
                                                   name="insumos[{{ $ins['idinsumo'] }}]"
                                                   step="0.001" min="0"
                                                   placeholder="0"
                                                   value="{{ $cant }}"
                                                   oninput="onQtyChange(this, '{{ Str::slug($catName) }}')">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
</div>

<!-- PANEL 2: Productos Registrados -->
<div class="module-tab-panel {{ !$productoEditar ? 'active' : '' }}" id="tab-lista">
    <div class="card">
        <div class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; color:#2563EB;"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            <span>Productos Registrados ({{ count($productos) }})</span>
        </div>

        @if (!empty($catalogos))
            <div class="filter-bar">
                <span>Categorías:</span>
                @foreach ($catalogos as $idx => $cat)
                    <button class="filter-btn {{ $idx === 0 ? 'active' : '' }}" onclick="filtrarCategoria('{{ $cat['nombre'] }}', this)">
                        {{ $cat['nombre'] }}
                    </button>
                @endforeach
            </div>
        @endif

        <div class="prod-grid" id="productosGrid">
            @foreach ($productos as $prod)
                <div class="prod-card" data-categoria="{{ $prod['catalogo'] ?? 'Sin categoría' }}">
                    <div class="prod-img">
                        @if (!empty($prod['imagen']))
                            <img src="{{ asset($prod['imagen']) }}" alt="{{ $prod['nombre'] }}">
                        @else
                            🍨
                        @endif
                    </div>
                    <div class="prod-body">
                        <div class="prod-name">{{ $prod['nombre'] }}</div>
                        <div class="prod-cat">{{ $prod['catalogo'] ?? 'Sin categoría' }}</div>
                        <div class="prod-price">${{ number_format($prod['precio'], 0, ',', '.') }}</div>
                        <div class="prod-foot">
                            @if ($prod['disponible'])
                                <span class="badge badge-ok">Disponible</span>
                            @else
                                <span class="badge badge-no">Agotado</span>
                            @endif
                            <div style="display:flex;gap:.3rem;">
                                <a href="{{ route('admin.productos.index', ['editar' => $prod['idproducto']]) }}" class="btn-edit">
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('admin.productos.eliminar', ['id' => $prod['idproducto']]) }}" style="display:inline" onsubmit="return confirm('¿Eliminar este producto y su receta?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-del">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @if (empty($productos))
                <div style="grid-column: 1/-1; text-align:center; color:var(--muted-color); padding:3rem;">
                    Sin productos registrados en la base de datos.
                </div>
            @endif
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

// Filtro de categorías en productos registrados (sin botón 'Todos')
function filtrarCategoria(catNombre, btn) {
    document.querySelectorAll('.filter-bar .filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const cards = document.querySelectorAll('#productosGrid .prod-card');
    cards.forEach(card => {
        const cardCat = card.getAttribute('data-categoria');
        if (cardCat === catNombre) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

// Iniciar filtro en la primera categoría activa al cargar
document.addEventListener('DOMContentLoaded', function() {
    const primerBtnCat = document.querySelector('.filter-bar .filter-btn.active');
    if (primerBtnCat) {
        const cat = primerBtnCat.innerText.trim();
        filtrarCategoria(cat, primerBtnCat);
    }
});

// Colapso/Expansión de Categoría de Insumos
function toggleCatGroup(header) {
    const body = header.nextElementSibling;
    const chevron = header.querySelector('.chevron-icon');
    if (body.style.display === 'none') {
        body.style.display = 'block';
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    } else {
        body.style.display = 'none';
        if (chevron) chevron.style.transform = 'rotate(-90deg)';
    }
}

// Búsqueda en Vivo de Insumos dentro de la Receta
function filtrarInsumosReceta() {
    const query = document.getElementById('recipeSearch').value.toLowerCase().trim();
    const catGroups = document.querySelectorAll('.cat-group');

    catGroups.forEach(group => {
        const rows = group.querySelectorAll('.insumo-item-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const insName = row.getAttribute('data-insname');
            if (!query || insName.includes(query)) {
                row.style.display = 'flex';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const body = group.querySelector('.cat-group-body');
        if (query.length > 0) {
            if (visibleCount > 0) {
                group.style.display = 'block';
                body.style.display = 'block';
            } else {
                group.style.display = 'none';
            }
        } else {
            group.style.display = 'block';
        }
    });
}

// Actualización Visual al escribir Cantidad
function onQtyChange(input, slugCat) {
    const row = input.closest('.insumo-item-row');
    const val = parseFloat(input.value) || 0;
    if (val > 0) {
        row.classList.add('has-qty');
    } else {
        row.classList.remove('has-qty');
    }

    // Actualizar badge contador de la categoría
    const group = input.closest('.cat-group');
    const inputs = group.querySelectorAll('.cat-group-body input');
    let count = 0;
    inputs.forEach(inp => {
        if ((parseFloat(inp.value) || 0) > 0) count++;
    });
    const badge = document.getElementById('badge-count-' + slugCat);
    if (badge) {
        badge.innerText = count + ' selec.';
        if (count > 0) {
            badge.classList.add('badge-ok');
        } else {
            badge.classList.remove('badge-ok');
        }
    }
}
</script>
@endsection
