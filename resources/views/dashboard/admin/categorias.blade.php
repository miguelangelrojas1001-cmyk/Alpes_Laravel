@extends('layouts.app')

@section('title', 'Gestionar Categorías - Panel de Administración')

@section('content')
<style>
    .admin-header { margin-bottom: 1.75rem; }
    .admin-header h1 { font-size: 1.6rem; font-weight: 900; color: var(--heading-color); margin-bottom: .3rem; }
    .admin-header p { color: var(--muted-color); font-size: .93rem; }
    .grid-2 { display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; }
    @media(max-width:900px){ .grid-2 { grid-template-columns: 1fr; } }
    .card { background: var(--card-bg); border-radius: 16px; box-shadow: var(--card-shadow); padding: 1.5rem; border: 1px solid var(--card-border); transition: background .25s, border-color .25s; }
    .card-title { font-size: 1.05rem; font-weight: 800; color: var(--heading-color); margin-bottom: 1.25rem; }
    label { display: block; font-size: .8rem; font-weight: 700; color: var(--label-color); margin-bottom: .35rem; }
    input, textarea { width: 100%; padding: .65rem .9rem; border: 1.5px solid var(--input-border); border-radius: 9px; font-family: 'Nunito', sans-serif; font-size: .9rem; background: var(--input-bg); outline: none; margin-bottom: .85rem; color: var(--input-color); transition: background .25s, border-color .25s, color .25s; }
    input:focus, textarea:focus { border-color: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,.1); background: var(--input-focus-bg); }
    .btn-primary { background: #2563EB; color: #fff; width: 100%; padding: .65rem; border-radius: 9px; border: none; cursor: pointer; font-family: 'Nunito', sans-serif; font-weight: 700; }
    .btn-sm { font-size: .75rem; padding: .3rem .7rem; border-radius: 7px; border: none; cursor: pointer; font-family: 'Nunito', sans-serif; font-weight: 700; text-decoration: none; display: inline-block; }
    .btn-danger { background: #EF4444; color: #fff; }
    .btn-toggle-on  { background: #D1FAE5; color: #065F46; }
    .btn-toggle-off { background: #FEF3C7; color: #92400E; }
    table { width: 100%; border-collapse: collapse; }
    th { background: var(--table-head-bg); font-size: .78rem; font-weight: 800; color: var(--table-head-txt); text-transform: uppercase; letter-spacing: .05em; padding: .75rem 1rem; text-align: left; border-bottom: 1px solid var(--card-border); }
    td { padding: .75rem 1rem; font-size: .88rem; color: var(--body-color); border-bottom: 1px solid var(--table-row-bd); vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    .badge { display: inline-block; padding: .25rem .75rem; border-radius: 20px; font-size: .75rem; font-weight: 700; }
    .badge-activo   { background: #D1FAE5; color: #065F46; }
    .badge-inactivo { background: var(--badge-bg); color: var(--badge-txt); }
    .alert-ok { background: var(--alert-ok-bg); border: 1px solid var(--alert-ok-bd); color: var(--alert-ok-txt); padding: .75rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: .88rem; }
    .alert-err { background: var(--alert-err-bg); border: 1px solid var(--alert-err-bd); color: var(--alert-err-txt); padding: .75rem 1rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: .88rem; }
</style>

<div class="admin-header">
    <h1 style="display:flex;align-items:center;gap:.5rem;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
        <span>Gestionar Categorías</span>
    </h1>
    <p>Crea y administra las categorías del catálogo de productos.</p>
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

<div class="grid-2">
    <div class="card">
        <div class="card-title" style="display:flex;align-items:center;gap:.35rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span>Nueva Categoría</span>
        </div>
        <form method="POST" action="{{ route('admin.categorias.guardar') }}">
            @csrf
            <label>Nombre</label>
            <input type="text" name="nombre" placeholder="Ej: Malteadas" required>
            <label>Descripción</label>
            <textarea name="descripcion" rows="3" placeholder="Descripción opcional..."></textarea>
            <button type="submit" class="btn-primary">Guardar categoría</button>
        </form>
    </div>

    <div class="card">
        <div class="card-title" style="display:flex;align-items:center;gap:.35rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
            <span>Categorías Registradas ({{ count($categorias) }})</span>
        </div>
        <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr><th>Nombre</th><th>Productos</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    @foreach ($categorias as $c)
                    <tr>
                        <td>
                            <strong>{{ $c['nombre'] }}</strong>
                            @if (!empty($c['descripcion']))
                                <br><small style="color:var(--muted-color)">{{ $c['descripcion'] }}</small>
                            @endif
                        </td>
                        <td>{{ $c['total_productos'] }}</td>
                        <td><span class="badge badge-{{ $c['estado'] }}">{{ $c['estado'] }}</span></td>
                        <td style="display:flex;gap:.4rem">
                            <form method="POST" action="{{ route('admin.categorias.toggle', ['id' => $c['idcatalogo']]) }}" style="display:inline">
                                @csrf
                                <button type="submit" class="btn-sm {{ $c['estado']==='activo' ? 'btn-toggle-on' : 'btn-toggle-off' }}">
                                    {{ $c['estado']==='activo' ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.categorias.eliminar', ['id' => $c['idcatalogo']]) }}" style="display:inline" onsubmit="return confirm('¿Eliminar categoría?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if (empty($categorias))
                        <tr><td colspan="4" style="text-align:center;color:var(--muted-color);padding:2rem">Sin categorías</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
