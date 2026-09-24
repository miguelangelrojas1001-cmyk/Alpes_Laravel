@extends('layouts.app')

@section('title', 'Gestión de Usuarios - Panel de Administración')

@section('content')
<style>
.usu-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
.usu-title  { display: flex; align-items: center; gap: .5rem; font-size:1.4rem; font-weight:900; color:var(--usu-title); }
.usu-title svg { width: 24px; height: 24px; color: #2563EB; }
.usu-subtitle { font-size:.88rem; color:var(--muted-color); margin-top:.2rem; }

.msg { padding:.85rem 1.25rem; border-radius:10px; font-size:.9rem; font-weight:700; margin-bottom:1.25rem; }
.msg.ok  { background:var(--alert-ok-bg); color:var(--alert-ok-txt); border:1px solid var(--alert-ok-bd); }
.msg.err { background:var(--alert-err-bg); color:var(--alert-err-txt); border:1px solid var(--alert-err-bd); }

.usu-card { background:var(--usu-card-bg); border:1.5px solid var(--usu-card-bd); border-radius:16px; overflow:hidden; box-shadow:var(--card-shadow); margin-bottom:2rem; transition:background .25s, border-color .25s; }
.usu-card-head { padding:1rem 1.5rem; border-bottom:1px solid var(--usu-head-bd); display:flex; align-items:center; gap:.6rem; }
.usu-card-head h3 { font-size:1rem; font-weight:800; color:var(--heading-color); }
.usu-card-head .badge { background:#EFF6FF; color:#1D4ED8; font-size:.75rem; font-weight:700; padding:.2rem .6rem; border-radius:20px; }
.usu-table { width:100%; border-collapse:collapse; }
.usu-table th { background:var(--table-head-bg); font-size:.75rem; font-weight:800; color:var(--table-head-txt); text-transform:uppercase; letter-spacing:.06em; padding:.75rem 1.25rem; text-align:left; border-bottom:1px solid var(--card-border); }
.usu-table td { padding:.75rem 1.25rem; font-size:.88rem; color:var(--body-color); border-bottom:1px solid var(--table-row-bd); vertical-align:middle; }
.usu-table tr:last-child td { border-bottom:none; }
.usu-table tr:hover td { background:var(--table-hover); }
.rol-badge { display:inline-block; padding:.25rem .75rem; border-radius:20px; font-size:.75rem; font-weight:700; }
.rol-administrador { background:#EFF6FF; color:#1D4ED8; }
.rol-domiciliario  { background:#FEF3C7; color:#92400E; }
.rol-cliente       { background:#F0FDF4; color:#166534; }
.rol-select { padding:.4rem .75rem; border:1.5px solid var(--input-border); border-radius:8px; font-family:'Nunito',sans-serif; font-size:.85rem; color:var(--rol-sel-color); background:var(--rol-sel-bg); cursor:pointer; transition:background .25s, border-color .25s, color .25s; }
.rol-select:focus { outline:none; border-color:#2563EB; }
.btn-sm { padding:.4rem .9rem; border-radius:8px; border:none; font-family:'Nunito',sans-serif; font-size:.82rem; font-weight:700; cursor:pointer; transition:background .2s; }
.btn-save   { display:inline-flex; align-items:center; gap:.25rem; background:#2563EB; color:#fff; }
.btn-save:hover { background:#1D4ED8; }
.btn-del    { display:inline-flex; align-items:center; gap:.25rem; background:#FEE2E2; color:#991B1B; }
.btn-del:hover  { background:#FECACA; }
.actions-cell { display:flex; gap:.5rem; align-items:center; flex-wrap:wrap; }

.new-user-card { background:var(--usu-card-bg); border:1.5px solid var(--usu-card-bd); border-radius:16px; overflow:hidden; box-shadow:var(--card-shadow); transition:background .25s, border-color .25s; }
.new-user-head { padding:1rem 1.5rem; border-bottom:1px solid var(--usu-head-bd); }
.new-user-head h3 { display:flex; align-items:center; gap:.4rem; font-size:1rem; font-weight:800; color:var(--heading-color); }
.new-user-head h3 svg { width:16px; height:16px; color:#2563EB; }
.new-user-body { padding:1.5rem; }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
@media(max-width:600px){ .form-grid { grid-template-columns:1fr; } }
.form-group { display:flex; flex-direction:column; gap:.35rem; }
.form-group label { font-size:.8rem; font-weight:700; color:var(--muted-color); text-transform:uppercase; letter-spacing:.05em; }
.form-group input,
.form-group select { padding:.65rem .9rem; border:1.5px solid var(--form-input-bd); border-radius:10px; font-family:'Nunito',sans-serif; font-size:.9rem; color:var(--form-input-clr); background:var(--form-input-bg); transition:border-color .2s, background .25s, color .25s; }
.form-group input:focus,
.form-group select:focus { outline:none; border-color:#2563EB; }
.form-footer { margin-top:1.25rem; display:flex; justify-content:flex-end; }
.btn-primary { padding:.75rem 1.75rem; background:#2563EB; color:#fff; border:none; border-radius:10px; font-family:'Nunito',sans-serif; font-size:.95rem; font-weight:800; cursor:pointer; transition:background .2s; }
.btn-primary:hover { background:#1D4ED8; }
</style>

<div class="usu-header">
    <div>
        <div class="usu-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Gestión de Usuarios
        </div>
        <div class="usu-subtitle">Administra roles y accesos del sistema</div>
    </div>
</div>

@php $mensaje = session('mensaje'); @endphp
@if (!empty($mensaje))
<div class="msg {{ $mensaje['tipo'] === 'ok' ? 'ok' : 'err' }}" style="display:flex; align-items:center; gap:.5rem;">
    @if ($mensaje['tipo'] === 'ok')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;flex-shrink:0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    @else
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    @endif
    <span>{{ $mensaje['texto'] }}</span>
</div>
@endif

<!-- Tabla de usuarios -->
<div class="usu-card">
    <div class="usu-card-head">
        <h3>Usuarios registrados</h3>
        <span class="badge">{{ count($usuarios) }}</span>
    </div>
    <div style="overflow-x:auto">
        <table class="usu-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol actual</th>
                    <th>Cambiar rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($usuarios as $u)
                <tr>
                    <td style="color:var(--muted-color);font-size:.8rem">{{ $u['idpersona'] }}</td>
                    <td style="font-weight:700;color:var(--heading-color)">{{ $u['nombre'] }}</td>
                    <td style="color:var(--muted-color)">{{ $u['correo'] }}</td>
                    <td>
                        <span class="rol-badge rol-{{ strtolower($u['rol']) }}">
                            {{ ucfirst($u['rol']) }}
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.usuarios.cambiarRol') }}" style="display:inline">
                            @csrf
                            <input type="hidden" name="idpersona" value="{{ $u['idpersona'] }}">
                            <div class="actions-cell">
                                <select name="idrol" class="rol-select">
                                    @foreach ($roles as $r)
                                    <option value="{{ $r['idrol'] }}" {{ $r['idrol'] == $u['idrol'] ? 'selected' : '' }}>
                                        {{ ucfirst($r['nombre']) }}
                                    </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn-sm btn-save">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.usuarios.eliminar', ['id' => $u['idpersona']]) }}" style="display:inline" onsubmit="return confirm('¿Eliminar al usuario &quot;{{ addslashes($u['nombre']) }}&quot;?\nEsta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm btn-del">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @if (empty($usuarios))
                <tr><td colspan="6" style="text-align:center;color:var(--muted-color);padding:2rem">No hay usuarios registrados.</td></tr>
            @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Formulario nuevo usuario -->
<div class="new-user-card">
    <div class="new-user-head">
        <h3>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
            Crear nuevo usuario
        </h3>
    </div>
    <div class="new-user-body">
        <form method="POST" action="{{ route('admin.usuarios.crear') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre completo</label>
                    <input type="text" name="nombre" placeholder="Ej: Juan Pérez" required>
                </div>
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="correo" placeholder="correo@ejemplo.com" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="contrasena" placeholder="Mínimo 6 caracteres" required minlength="6">
                </div>
                <div class="form-group">
                    <label>Rol</label>
                    <select name="rol">
                        @foreach ($roles as $r)
                        <option value="{{ strtolower($r['nombre']) }}">{{ ucfirst($r['nombre']) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn-primary">Crear usuario</button>
            </div>
        </form>
    </div>
</div>
@endsection
