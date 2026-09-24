<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Los Alpes — Heladería')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    /* ── Tema oscuro (default) ── */
    :root {
        --blue: #2563EB;
        --sidebar-w: 230px;
        --sb-bg:       #1a1a2e;
        --sb-border:   rgba(255,255,255,.07);
        --sb-text:     rgba(255,255,255,.6);
        --sb-text-h:   #fff;
        --sb-hover:    rgba(255,255,255,.07);
        --sb-active:   #2563EB;
        --sb-section:  rgba(255,255,255,.35);
        --sb-logout-h: rgba(239,68,68,.12);
        --sb-logout-c: #FCA5A5;
        --main-bg:     #111827;
        --topbar-bg:   #1a1a2e;
        --topbar-bd:   rgba(255,255,255,.07);
        --search-bg:   rgba(255,255,255,.06);
        --search-bd:   rgba(255,255,255,.1);
        --text-main:   #f1f5f9;
        --text-muted:  rgba(255,255,255,.4);
        --input-color: #f1f5f9;

        /* ── Variables de contenido (dark) ── */
        --card-bg:        #1f2937;
        --card-border:    rgba(255,255,255,.07);
        --card-shadow:    0 2px 16px rgba(0,0,0,.3);
        --heading-color:  #f1f5f9;
        --body-color:     #cbd5e1;
        --muted-color:    rgba(255,255,255,.4);
        --label-color:    rgba(255,255,255,.55);
        --input-bg:       rgba(255,255,255,.06);
        --input-border:   rgba(255,255,255,.12);
        --table-head-bg:  rgba(255,255,255,.05);
        --table-head-txt: rgba(255,255,255,.45);
        --table-row-bd:   rgba(255,255,255,.05);
        --badge-bg:       rgba(255,255,255,.07);
        --badge-txt:      rgba(255,255,255,.5);
        --btn-sec-bg:     rgba(255,255,255,.07);
        --btn-sec-txt:    rgba(255,255,255,.7);
        --filter-btn-bg:  rgba(255,255,255,.05);
        --prod-img-bg:    #111827;
        --prod-card-bg:   #1f2937;
        --prod-card-bd:   rgba(255,255,255,.07);
        --alert-ok-bg:    rgba(16,185,129,.12);
        --alert-ok-bd:    rgba(16,185,129,.25);
        --alert-ok-txt:   #6EE7B7;
        --edit-banner-bg: rgba(37,99,235,.12);
        --edit-banner-bd: rgba(37,99,235,.25);
        --edit-banner-txt:#93C5FD;
        --cat-tab-bg:     rgba(255,255,255,.05);
        --cat-tab-bd:     rgba(255,255,255,.1);
        --cat-tab-txt:    rgba(255,255,255,.55);
        --cat-banner-bg:  rgba(37,99,235,.13);
        --cat-banner-bd:  rgba(37,99,235,.28);
        --cat-banner-name:#93C5FD;
        --cat-banner-desc:rgba(255,255,255,.4);
        --item-icon-bg:   rgba(255,255,255,.06);
        --item-name:      #f1f5f9;
        --item-qty:       rgba(255,255,255,.4);
        --qty-btn-bg:     rgba(255,255,255,.06);
        --qty-btn-bd:     rgba(255,255,255,.1);
        --qty-input-bg:   rgba(255,255,255,.04);
        --qty-input-bd:   rgba(255,255,255,.1);
        --carrito-vacio-h:#f1f5f9;
        --carrito-vacio-p:rgba(255,255,255,.4);
    }
    /* ── Tema claro ── */
    [data-theme="light"] {
        --sb-bg:       #1e293b;
        --sb-border:   rgba(255,255,255,.08);
        --sb-text:     rgba(255,255,255,.65);
        --sb-text-h:   #fff;
        --sb-hover:    rgba(255,255,255,.08);
        --sb-active:   #2563EB;
        --sb-section:  rgba(255,255,255,.35);
        --sb-logout-h: rgba(239,68,68,.12);
        --sb-logout-c: #FCA5A5;
        --main-bg:     #F1F5F9;
        --topbar-bg:   #FFFFFF;
        --topbar-bd:   #E2E8F0;
        --search-bg:   #F8FAFC;
        --search-bd:   #E2E8F0;
        --text-main:   #0F172A;
        --text-muted:  #64748B;
        --input-color: #0F172A;

        --card-bg:        #ffffff;
        --card-border:    #E2E8F0;
        --card-shadow:    0 2px 16px rgba(0,0,0,.06);
        --heading-color:  #0F172A;
        --body-color:     #334155;
        --muted-color:    #64748B;
        --label-color:    #334155;
        --input-bg:       #F8FAFC;
        --input-border:   #E2E8F0;
        --table-head-bg:  #F8FAFC;
        --table-head-txt: #64748B;
        --table-row-bd:   #F1F5F9;
        --badge-bg:       #F1F5F9;
        --badge-txt:      #64748B;
        --btn-sec-bg:     #F1F5F9;
        --btn-sec-txt:    #334155;
        --filter-btn-bg:  #F8FAFC;
        --prod-img-bg:    #F1F5F9;
        --prod-card-bg:   #ffffff;
        --prod-card-bd:   #E2E8F0;
        --alert-ok-bg:    #D1FAE5;
        --alert-ok-bd:    #A7F3D0;
        --alert-ok-txt:   #065F46;
        --edit-banner-bg: #EFF6FF;
        --edit-banner-bd: #BFDBFE;
        --edit-banner-txt:#1D4ED8;
        --cat-tab-bg:     #F1F5F9;
        --cat-tab-bd:     #E2E8F0;
        --cat-tab-txt:    #475569;
        --cat-banner-bg:  rgba(37,99,235,.08);
        --cat-banner-bd:  rgba(37,99,235,.2);
        --cat-banner-name:#1D4ED8;
        --cat-banner-desc:#64748B;
        --item-icon-bg:   #F1F5F9;
        --item-name:      #0F172A;
        --item-qty:       #64748B;
        --qty-btn-bg:     #ffffff;
        --qty-btn-bd:     #E2E8F0;
        --qty-input-bg:   #F8FAFC;
        --qty-input-bd:   #E2E8F0;
        --carrito-vacio-h:#0F172A;
        --carrito-vacio-p:#64748B;
    }

    html, body { height: 100%; font-family: 'Nunito', sans-serif; background: var(--main-bg); color: var(--text-main); transition: background .25s, color .25s; }
    .layout { display: flex; min-height: 100vh; }

    /* ── Sidebar ── */
    .sidebar {
        width: var(--sidebar-w); background: var(--sb-bg);
        display: flex; flex-direction: column; flex-shrink: 0;
        position: fixed; top: 0; left: 0; height: 100vh; overflow-y: auto; z-index: 100;
        border-right: 1px solid var(--sb-border);
        transition: background .25s;
    }
    .sidebar-brand {
        display: flex; align-items: center; gap: .75rem;
        padding: 1.3rem 1.1rem 1rem;
        border-bottom: 1px solid var(--sb-border);
    }
    .sidebar-brand img { width: 36px; height: 36px; border-radius: 9px; object-fit: contain; background: rgba(255,255,255,.1); padding: 4px; }
    .sidebar-brand-name { font-size: .98rem; font-weight: 800; color: #fff; }
    .sidebar-brand-sub  { font-size: .7rem; color: var(--sb-section); }

    .sidebar-user {
        display: flex; align-items: center; gap: .7rem;
        padding: .85rem 1.1rem; border-bottom: 1px solid var(--sb-border);
    }
    .sidebar-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: var(--blue); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; font-weight: 800; flex-shrink: 0; letter-spacing: .02em;
    }
    .sidebar-user-name { font-size: .82rem; font-weight: 700; color: #fff; line-height: 1.2; }
    .sidebar-user-role { font-size: .68rem; color: var(--sb-section); text-transform: capitalize; }

    .sidebar-nav { padding: .8rem .6rem; flex: 1; }
    .sidebar-section {
        font-size: .62rem; font-weight: 800; color: var(--sb-section);
        letter-spacing: .1em; text-transform: uppercase;
        padding: .85rem .6rem .2rem;
    }
    .sidebar-link {
        display: flex; align-items: center; gap: .65rem;
        padding: .62rem .85rem; border-radius: 9px; margin-bottom: .08rem;
        color: var(--sb-text); text-decoration: none; font-size: .86rem; font-weight: 600;
        transition: background .15s, color .15s;
    }
    .sidebar-link:hover  { background: var(--sb-hover); color: var(--sb-text-h); }
    .sidebar-link.active { background: var(--sb-active); color: #fff; }
    .sidebar-link svg    { width: 17px; height: 17px; flex-shrink: 0; }

    .sidebar-footer { padding: .8rem .6rem; border-top: 1px solid var(--sb-border); }
    .sidebar-logout {
        display: flex; align-items: center; gap: .65rem; width: 100%;
        padding: .62rem .85rem; border-radius: 9px; background: transparent; border: none;
        color: var(--sb-text); text-decoration: none; font-size: .86rem; font-weight: 600;
        cursor: pointer; font-family: 'Nunito', sans-serif;
        transition: background .15s, color .15s;
    }
    .sidebar-logout:hover { background: var(--sb-logout-h); color: var(--sb-logout-c); }
    .sidebar-logout svg  { width: 17px; height: 17px; flex-shrink: 0; }

    /* ── Main ── */
    .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
    .topbar {
        background: var(--topbar-bg); border-bottom: 1px solid var(--topbar-bd);
        padding: .8rem 1.75rem; display: flex; align-items: center; justify-content: space-between;
        position: sticky; top: 0; z-index: 50; transition: background .25s, border-color .25s;
    }
    .topbar-search {
        display: flex; align-items: center; gap: .6rem;
        background: var(--search-bg); border: 1px solid var(--search-bd);
        border-radius: 10px; padding: .42rem .9rem; width: 270px;
        transition: background .25s, border-color .25s;
    }
    .topbar-search svg   { width: 15px; height: 15px; color: var(--text-muted); flex-shrink: 0; }
    .topbar-search input { border: none; background: transparent; outline: none; font-family: 'Nunito', sans-serif; font-size: .87rem; width: 100%; color: var(--input-color); }
    .topbar-search input::placeholder { color: var(--text-muted); }
    .topbar-actions { display: flex; align-items: center; gap: .85rem; }

    .topbar-cart {
        display: flex; align-items: center; gap: .45rem; padding: .48rem .95rem;
        background: var(--blue); color: #fff; border-radius: 9px;
        text-decoration: none; font-size: .84rem; font-weight: 700;
        transition: background .15s;
    }
    .topbar-cart:hover { background: #1D4ED8; }

    #theme-toggle {
        width: 38px; height: 38px; border-radius: 9px; border: 1px solid var(--search-bd);
        background: var(--search-bg); cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .15s, border-color .15s; flex-shrink: 0;
    }
    #theme-toggle:hover { background: var(--sb-hover); }
    #theme-toggle svg { width: 18px; height: 18px; color: var(--text-muted); }

    .badge-cart {
        display: inline-flex; align-items: center; justify-content: center;
        background: #EF4444; color: #fff; font-size: .7rem; font-weight: 900;
        min-width: 18px; height: 18px; border-radius: 999px; padding: 0 4px; margin-left: auto;
    }

    .page-content { padding: 1.75rem 2rem; flex: 1; }
    </style>
</head>
<body>
<div class="layout">

@php
    $carritoSesion = session('carrito', []);
    $cantCarrito = array_sum(array_column($carritoSesion, 'cantidad'));
@endphp

<!-- ── Sidebar ── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Los Alpes">
        <div>
            <div class="sidebar-brand-name">Los Alpes</div>
            <div class="sidebar-brand-sub">Heladería</div>
        </div>
    </div>

    @auth
        @php
            $nombreUser = Auth::user()->nombre ?? 'Usuario';
            $partesNombre = explode(' ', trim($nombreUser));
            $iniciales = strtoupper(substr($partesNombre[0], 0, 1) . (isset($partesNombre[1]) ? substr($partesNombre[1], 0, 1) : ''));
            $rolUser = strtolower(Auth::user()->rol_nombre ?? 'cliente');
        @endphp
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ $iniciales }}</div>
            <div>
                <div class="sidebar-user-name">{{ $nombreUser }}</div>
                <div class="sidebar-user-role">{{ $rolUser }}</div>
            </div>
        </div>
    @endauth

    <nav class="sidebar-nav">
        <div class="sidebar-section">Navegación</div>
        <a href="{{ route('catalogo.index') }}" class="sidebar-link {{ request()->routeIs('catalogo.index') || request()->routeIs('home') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Catálogo
        </a>

        @if (!Auth::check() || strtolower(Auth::user()->rol_nombre ?? 'cliente') === 'cliente')
            <a href="{{ route('carrito.index') }}" class="sidebar-link {{ request()->routeIs('carrito.index') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Carrito
                @if ($cantCarrito > 0)
                    <span class="badge-cart">{{ $cantCarrito }}</span>
                @endif
            </a>
        @endif

        @auth
            @if($rolUser === 'administrador')
                <div class="sidebar-section">Administración</div>
                <a href="{{ route('admin.productos.index') }}" class="sidebar-link {{ request()->routeIs('admin.productos.index') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    Productos
                </a>
                <a href="{{ route('admin.inventario.index') }}" class="sidebar-link {{ request()->routeIs('admin.inventario.index') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    Inventario
                </a>
                <a href="{{ route('admin.ventas.index') }}" class="sidebar-link {{ request()->routeIs('admin.ventas.index') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Ventas
                </a>
                <a href="{{ route('admin.proveedores.index') }}" class="sidebar-link {{ request()->routeIs('admin.proveedores.index') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    Proveedores
                </a>
                <a href="{{ route('admin.categorias.index') }}" class="sidebar-link {{ request()->routeIs('admin.categorias.index') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                    Categorías
                </a>
                <a href="{{ route('admin.usuarios.index') }}" class="sidebar-link {{ request()->routeIs('admin.usuarios.index') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Usuarios
                </a>
                <div class="sidebar-section">Análisis</div>
                <a href="{{ route('admin.reportes.index') }}" class="sidebar-link {{ request()->routeIs('admin.reportes.index') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/><line x1="2" y1="20" x2="22" y2="20"/></svg>
                    Reportes
                </a>
            @elseif($rolUser === 'domiciliario')
                <a href="{{ route('domiciliario.dashboard') }}" class="sidebar-link {{ request()->routeIs('domiciliario.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    Envíos
                </a>
            @else
                <a href="{{ route('cliente.dashboard') }}" class="sidebar-link {{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Mis Pedidos
                </a>
            @endif
        @endauth
    </nav>

    <div class="sidebar-footer">
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Cerrar sesión
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="sidebar-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Iniciar sesión
            </a>
        @endauth
    </div>
</aside>

<!-- ── Main Content ── -->
<div class="main">
    <header class="topbar">
        <div class="topbar-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Buscar productos...">
        </div>
        <div class="topbar-actions">
            @if (!Auth::check() || strtolower(Auth::user()->rol_nombre ?? 'cliente') === 'cliente')
                <a href="{{ route('carrito.index') }}" class="topbar-cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Carrito ({{ $cantCarrito }})
                </a>
            @endif
            <button id="theme-toggle" title="Cambiar tema" onclick="toggleTheme()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
            </button>
        </div>
    </header>

    <main class="page-content">
        @yield('content')
    </main>
</div>

</div>

<script>
function toggleTheme() {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme') || 'dark';
    const next = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
}
(function() {
    const saved = localStorage.getItem('theme');
    if (saved) document.documentElement.setAttribute('data-theme', saved);
})();
</script>

@auth
<style>
#chatbot-fab {
    position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999;
    width:54px; height:54px; border-radius:50%;
    background:linear-gradient(135deg,#2563EB,#1E3A5F);
    color:#fff; border:none; cursor:pointer;
    box-shadow:0 4px 20px rgba(37,99,235,.4);
    display:flex; align-items:center; justify-content:center;
    transition:transform .2s,box-shadow .2s;
}
#chatbot-fab:hover { transform:scale(1.1); }
#chatbot-fab .fab-badge {
    position:absolute; top:-2px; right:-2px;
    width:17px; height:17px; background:#EF4444; border-radius:50%;
    font-size:.62rem; font-weight:800; display:none;
    align-items:center; justify-content:center; color:#fff;
}
#chatbot-window {
    position:fixed; bottom:5rem; right:1.5rem; z-index:9998;
    width:350px; max-height:510px;
    background:var(--card-bg); border-radius:18px;
    box-shadow:0 8px 40px rgba(0,0,0,.25);
    border: 1px solid var(--card-border);
    display:none; flex-direction:column; overflow:hidden;
    font-family:'Nunito',sans-serif;
    transition: background .25s, border-color .25s;
}
#chatbot-window.open { display:flex; }
.cb-head { background:linear-gradient(135deg,#1E3A5F,#2563EB); padding:.85rem 1rem; display:flex; align-items:center; gap:.7rem; }
.cb-head-avatar { width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,.2); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.cb-head-info { flex:1; }
.cb-head-name { font-size:.88rem; font-weight:800; color:#fff; }
.cb-head-status { font-size:.7rem; color:rgba(255,255,255,.7); display:flex; align-items:center; gap:.3rem; }
.cb-head-status::before { content:''; width:6px; height:6px; border-radius:50%; background:#4ADE80; display:inline-block; }
.cb-close { background:none; border:none; color:rgba(255,255,255,.7); cursor:pointer; font-size:1rem; }
.cb-close:hover { color:#fff; }
.cb-messages { flex:1; overflow-y:auto; padding:.9rem; display:flex; flex-direction:column; gap:.6rem; background:var(--input-bg); transition:background .25s; }
.cb-messages::-webkit-scrollbar { width:3px; }
.cb-messages::-webkit-scrollbar-thumb { background:var(--card-border); border-radius:3px; }
.cb-msg { display:flex; gap:.45rem; align-items:flex-end; }
.cb-msg.bot { justify-content:flex-start; }
.cb-msg.user { justify-content:flex-end; }
.cb-bubble { max-width:82%; padding:.55rem .85rem; border-radius:15px; font-size:.83rem; line-height:1.5; word-break:break-word; }
.cb-msg.bot .cb-bubble { background:var(--card-bg); color:var(--heading-color); border-bottom-left-radius:3px; box-shadow:0 1px 4px rgba(0,0,0,.12); transition:background .25s,color .25s; border:1px solid var(--card-border); }
.cb-msg.user .cb-bubble { background:#2563EB; color:#fff; border-bottom-right-radius:3px; }
.cb-avatar-bot { width:26px; height:26px; border-radius:50%; background:var(--btn-sec-bg); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.cb-typing { display:flex; gap:4px; padding:.45rem .7rem; }
.cb-typing span { width:6px; height:6px; border-radius:50%; background:var(--muted-color); animation:cbBounce .9s infinite; }
.cb-typing span:nth-child(2) { animation-delay:.15s; }
.cb-typing span:nth-child(3) { animation-delay:.3s; }
@keyframes cbBounce { 0%,60%,100%{transform:translateY(0)} 30%{transform:translateY(-5px)} }
.cb-suggestions { display:flex; flex-wrap:wrap; gap:.35rem; padding:.55rem .9rem; border-top:1px solid var(--card-border); background:var(--card-bg); transition:background .25s,border-color .25s; }
.cb-sug-btn { padding:.28rem .7rem; border-radius:20px; border:1.5px solid var(--card-border); background:var(--input-bg); font-family:'Nunito',sans-serif; font-size:.73rem; font-weight:700; color:var(--heading-color); cursor:pointer; transition:background .15s,border-color .15s,color .15s; }
.cb-sug-btn:hover { background:#EFF6FF; border-color:#2563EB; color:#2563EB; }
.cb-input-row { display:flex; gap:.45rem; padding:.7rem .9rem; border-top:1px solid var(--card-border); background:var(--card-bg); transition:background .25s,border-color .25s; }
.cb-input { flex:1; padding:.5rem .8rem; border:1.5px solid var(--input-border); border-radius:11px; font-family:'Nunito',sans-serif; font-size:.86rem; outline:none; color:var(--input-color); background:var(--input-bg); transition:border-color .15s,background .25s,color .25s; }
.cb-input:focus { border-color:#2563EB; background:var(--input-focus-bg); }
.cb-send { width:36px; height:36px; border-radius:9px; background:#2563EB; color:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:.95rem; transition:background .15s; flex-shrink:0; }
.cb-send:hover { background:#1D4ED8; }
</style>

<button id="chatbot-fab" onclick="cbToggle()" title="Asistente Los Alpes">
    <img src="{{ asset('img/logo.png') }}" alt="Los Alpes" style="width:30px;height:30px;object-fit:contain;border-radius:50%;">
    <span class="fab-badge" id="cbBadge">1</span>
</button>

<div id="chatbot-window">
    <div class="cb-head">
        <div class="cb-head-avatar"><img src="{{ asset('img/logo.png') }}" alt="" style="width:26px;height:26px;object-fit:contain;border-radius:50%;"></div>
        <div class="cb-head-info">
            <div class="cb-head-name">Asistente Los Alpes</div>
            <div class="cb-head-status">En línea</div>
        </div>
        <button class="cb-close" onclick="cbToggle()">✕</button>
    </div>
    <div class="cb-messages" id="cbMessages">
        <div class="cb-msg bot">
            <div class="cb-avatar-bot"><img src="{{ asset('img/logo.png') }}" alt="" style="width:18px;height:18px;object-fit:contain;border-radius:50%;"></div>
            <div class="cb-bubble">¡Hola, <strong>{{ Auth::user()->nombre ?? 'amigo' }}</strong>! 👋<br>Soy el asistente de <strong>Los Alpes</strong>. ¿En qué te puedo ayudar?</div>
        </div>
    </div>
    <div class="cb-suggestions" id="cbSugerencias">
        @php $rol = strtolower(Auth::user()->rol_nombre ?? 'cliente'); @endphp
        @if ($rol === 'cliente')
            <button class="cb-sug-btn" onclick="cbEnviar('¿Cómo hago un pedido?')">¿Cómo pedir?</button>
            <button class="cb-sug-btn" onclick="cbEnviar('¿Cuáles son los métodos de pago?')">Métodos de pago</button>
            <button class="cb-sug-btn" onclick="cbEnviar('¿Cuánto cuesta el domicilio?')">Domicilio</button>
            <button class="cb-sug-btn" onclick="cbEnviar('¿Cómo veo mis pedidos?')">Mis pedidos</button>
        @elseif ($rol === 'administrador')
            <button class="cb-sug-btn" onclick="cbEnviar('¿Cómo agrego un producto?')">Agregar producto</button>
            <button class="cb-sug-btn" onclick="cbEnviar('¿Cómo gestiono el inventario?')">Inventario</button>
            <button class="cb-sug-btn" onclick="cbEnviar('¿Cómo cambio el estado de un pedido?')">Ventas</button>
        @elseif ($rol === 'domiciliario')
            <button class="cb-sug-btn" onclick="cbEnviar('¿Cómo marco un pedido como entregado?')">Marcar entregado</button>
            <button class="cb-sug-btn" onclick="cbEnviar('¿Cómo veo mis pedidos asignados?')">Mis pedidos</button>
        @endif
        <button class="cb-sug-btn" onclick="cbEnviar('¿Qué productos tienen?')">Productos</button>
    </div>
    <div class="cb-input-row">
        <input type="text" class="cb-input" id="cbInput" placeholder="Escribe tu pregunta..." onkeydown="if(event.key==='Enter') cbEnviar()">
        <button class="cb-send" onclick="cbEnviar()">➤</button>
    </div>
</div>

<script>
var cbAbierto = false;
function cbToggle() {
    cbAbierto = !cbAbierto;
    var win = document.getElementById('chatbot-window');
    var badge = document.getElementById('cbBadge');
    if (cbAbierto) { win.classList.add('open'); if(badge) badge.style.display='none'; document.getElementById('cbInput').focus(); }
    else { win.classList.remove('open'); }
}
function cbEnviar(textoFijo) {
    var input = document.getElementById('cbInput');
    var texto = textoFijo || input.value.trim();
    if (!texto) return;
    cbAgregarMensaje(texto, 'user');
    input.value = '';
    var sug = document.getElementById('cbSugerencias');
    if (sug) sug.style.display = 'none';
    var typingId = cbMostrarTyping();
    var token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';

    fetch("{{ route('chatbot.responder') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ mensaje: texto })
    })
    .then(function(r){ return r.json(); })
    .then(function(data) {
        cbQuitarTyping(typingId);
        cbAgregarMensaje(data.respuesta || 'Error al procesar la respuesta.', 'bot');
    })
    .catch(function() {
        cbQuitarTyping(typingId);
        cbAgregarMensaje('Error de conexión con el servidor.', 'bot');
    });
}
function cbAgregarMensaje(texto, tipo) {
    var msgs = document.getElementById('cbMessages');
    var div = document.createElement('div');
    div.className = 'cb-msg ' + tipo;
    var logoUrl = "{{ asset('img/logo.png') }}";
    var html = tipo === 'bot' ? '<div class="cb-avatar-bot"><img src="' + logoUrl + '" alt="" style="width:18px;height:18px;object-fit:contain;border-radius:50%;"></div>' : '';
    html += '<div class="cb-bubble">' + texto.replace(/\n/g,'<br>') + '</div>';
    div.innerHTML = html;
    msgs.appendChild(div);
    msgs.scrollTop = msgs.scrollHeight;
}
function cbMostrarTyping() {
    var msgs = document.getElementById('cbMessages');
    var div = document.createElement('div');
    div.className = 'cb-msg bot';
    div.id = 'cb-t-' + Date.now();
    var logoUrl = "{{ asset('img/logo.png') }}";
    div.innerHTML = '<div class="cb-avatar-bot"><img src="' + logoUrl + '" alt="" style="width:18px;height:18px;object-fit:contain;border-radius:50%;"></div><div class="cb-bubble"><div class="cb-typing"><span></span><span></span><span></span></div></div>';
    msgs.appendChild(div);
    msgs.scrollTop = msgs.scrollHeight;
    return div.id;
}
function cbQuitarTyping(id) { var el = document.getElementById(id); if (el) el.remove(); }
setTimeout(function() { if (!cbAbierto) { var b = document.getElementById('cbBadge'); if(b) b.style.display='flex'; } }, 3000);
</script>
@endauth
</body>
</html>

