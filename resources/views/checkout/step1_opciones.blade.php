@extends('checkout.step_layout')

@php
    $cat = strtolower($producto->catalogo ? $producto->catalogo->nombre : '');
    $np  = strtolower($producto->nombre ?? '');

    $esYogurt          = str_contains($cat, 'yogurt') || str_contains($cat, 'yogur');
    $esEnsalada        = str_contains($cat, 'ensalada');
    $esMalteada        = str_contains($cat, 'malteada');
    $esCanasta         = str_contains($cat, 'canasta');
    $esMalteadaSencilla = $esMalteada && str_contains($np, 'sencilla');
    $esMalteadaEspecial = $esMalteada && !$esMalteadaSencilla;
    $esMoonIce          = str_contains($np, 'moon ice');

    $precioMostrar = (float)$producto->precio;
    if ($esYogurt && $precioMostrar < 1000 && $precioMostrar > 0) {
        $precioMostrar = $precioMostrar * 1000;
    }

    $saboresHelado = [
        'Frutos rojos','Maracuya','Chicle','Tres Leches',
        'Coffe Delight','Crema con Mani','Chocolate','Vainilla Chips',
        'Brownie','Arequipe','Uva Vainilla','Lulo',
        'Nucita','Ron con pasas','Mandarina Limon','Acid Mix',
    ];

    $saboresMalteada = [
        'Oreo','Frutos rojos','Vainilla chips','Chocolate','Napolitano',
        'Nucita','Cafe','Capuchino','Chicle','Maracuya',
        'Tres leches','Arequipe','Mandarina limon','Uva vainilla','Unicornio',
        'Ron pasas','Lulo','Crema mani','Acid mix','Sandia',
        'Nutella','Kiwi',
    ];

    if (!function_exists('canastaCfgBlade')) {
        function canastaCfgBlade(string $np): array {
            if (str_contains($np, 'mini lasana') || str_contains($np, 'mini lasaña')) {
                return ['max'=>2,'precio'=>15000,'selector'=>false,'opciones'=>[]];
            }
            if (str_contains($np, 'lasana') || str_contains($np, 'lasaña')) {
                return ['max'=>4,'precio'=>19000,'selector'=>false,'opciones'=>[]];
            }
            if (str_contains($np, 'chocorramo')) {
                return ['max'=>3,'precio'=>15000,'selector'=>false,'opciones'=>[]];
            }
            if (str_contains($np, 'oreo')) {
                return ['max'=>3,'precio'=>15000,'selector'=>false,'opciones'=>[]];
            }
            if (str_contains($np, 'chocolatisima') || str_contains($np, 'chocolatísima')) {
                return ['max'=>3,'precio'=>19000,'selector'=>false,'opciones'=>[]];
            }
            if (str_contains($np, 'chicle')) {
                return ['max'=>3,'precio'=>15000,'selector'=>false,'opciones'=>[]];
            }
            if (str_contains($np, 'acida') || str_contains($np, 'ácida')) {
                return ['max'=>3,'precio'=>15000,'selector'=>false,'opciones'=>[],'acida'=>true];
            }
            if (str_contains($np, 'fresura')) {
                return ['max'=>3,'precio'=>19000,'selector'=>false,'opciones'=>[]];
            }
            if (str_contains($np, 'chips')) {
                return ['max'=>3,'precio'=>15000,'selector'=>false,'opciones'=>[]];
            }
            if (str_contains($np, 'toppings')) {
                return ['max'=>3,'precio'=>19000,'selector'=>false,'opciones'=>[]];
            }
            if (str_contains($np, 'especial')) {
                return ['max'=>5,'precio'=>14000,'selector'=>true,'opciones'=>[
                    ['sabores'=>4,'precio'=>12000,'label'=>'4 sabores — $12.000'],
                    ['sabores'=>5,'precio'=>14000,'label'=>'5 sabores — $14.000'],
                ]];
            }
            if (str_contains($np, 'sencilla')) {
                return ['max'=>3,'precio'=>8000,'selector'=>true,'opciones'=>[
                    ['sabores'=>2,'precio'=>7000,'label'=>'2 sabores — $7.000'],
                    ['sabores'=>3,'precio'=>8000,'label'=>'3 sabores — $8.000'],
                ]];
            }
            return ['max'=>3,'precio'=>0,'selector'=>false,'opciones'=>[]];
        }
    }

    $canastaCfg = $esCanasta ? canastaCfgBlade($np) : [];
    $saboresAcidos = ['Maracuya','Lulo','Mandarina Limon','Acid Mix','Uva Vainilla','Chicle'];

    $toppingsFrutas = [
        'Kiwi','Fresas','Cerezas','Duraznos','Mango','Pina','Banano','Pulpa de maracuya',
        'Uvas','Melon','Papaya','Manzana',
    ];
    $toppingsPremium = [
        'Arandanos','Nueces','Almendras','Granola','Queso doble crema',
        'Caviar Lyche','Caviar Maracuya','Caviar Fresa','Caviar Arandanos',
        'Caviar Manzana verde','Caviar Chicle','Caviar Tamarindo','Caviar Cereza',
        'Caviar Naranja','Caviar Sandia','Caviar Mango biche',
    ];
    $toppingsDulces = [
        'Chokis','Galletas oreo','Chocorramo','Gol','Barquillos','Chocolatina nucita',
        'Galletas nucita','Brownie','Jet cookies and cream','Jet barras','Jet wafer',
        'Jet gool','Chips de chocolate','M&M','Fideos de chocolate','Pepitas de chocolate',
        'Zucaritas','Aros Cereal','Choco Krispis','Chocmelos','Chocobreak','Sparkies',
        'Golochips','Galletas Minichips','Ositos Trululu','Gusanos gomitas','Masmelos',
        'Milo','Nuggets de Milo','Pepitas de colores','Chocolatina kinder bueno','Chocolatina Hersheys',
    ];
    $toppingsSalsas  = ['Arequipe','Relleno de Mora','Relleno de Fresa','Chocolate','Lechera'];
    $salsasPremium   = ['Syrup de Fresa','Syrup de Caramelo','Syrup de Chocolate'];

    if ($esYogurt)              $step_title = 'Personaliza tu Helado de Yogurt';
    elseif ($esEnsalada)        $step_title = 'Elige tus sabores de helado';
    elseif ($esMalteadaSencilla) $step_title = 'Elige el sabor de tu Malteada';
    elseif ($esMalteadaEspecial) $step_title = 'Malteada Especial';
    elseif ($esCanasta)         $step_title = 'Arma tu Canasta';
    else                        $step_title = 'Personaliza tu pedido';
    $step = 1;
@endphp

@section('step_content')
<div class="prod-summary">
    <div class="prod-summary-img">
        @if (!empty($producto->imagen))
            <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}">
        @else
            🍨
        @endif
    </div>
    <div>
        <div class="prod-summary-name">{{ $producto->nombre }}</div>
        <div class="prod-summary-price">${{ number_format($precioMostrar, 0, ',', '.') }}</div>
        @if (!empty($producto->descripcion))
            <div style="font-size:.78rem;color:var(--muted-color);margin-top:.2rem">{{ $producto->descripcion }}</div>
        @endif
    </div>
</div>

<form method="POST" action="{{ route('checkout.guardarOpciones') }}" id="step1form">
@csrf
<input type="hidden" name="idproducto" value="{{ $producto->idproducto }}">
<input type="hidden" name="accion" id="accion_form" value="carrito">

@if ($esYogurt)
<style>
.topping-section { margin-bottom:1.25rem; }
.topping-section-title { font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.6rem;display:flex;align-items:center;gap:.5rem; }
.topping-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:.5rem; }
.topping-btn { border:1.5px solid var(--card-border);border-radius:10px;background:var(--card-bg);padding:.5rem .65rem;text-align:center;cursor:pointer;font-family:'Nunito',sans-serif;font-size:.82rem;font-weight:700;color:var(--heading-color);transition:all .15s ease;user-select:none; }
.topping-btn:hover { border-color:#2563EB;color:#2563EB;transform:translateY(-1px); }
.topping-btn.sel-frutas  { border-color:#10B981;background:rgba(16,185,129,.15);color:#10B981;font-weight:900; }
.topping-btn.sel-premium { border-color:#8B5CF6;background:rgba(139,92,246,.15);color:#8B5CF6;font-weight:900; }
.topping-btn.sel-dulces  { border-color:#F59E0B;background:rgba(245,158,11,.15);color:#F59E0B;font-weight:900; }
.topping-btn.sel-salsas  { border-color:#EF4444;background:rgba(239,68,68,.15);color:#EF4444;font-weight:900; }
.topping-btn.sel-salsasp { border-color:#EC4899;background:rgba(236,72,153,.15);color:#EC4899;font-weight:900; }
.topping-btn input { display:none; }
.ptag { display:inline-block;font-size:.68rem;font-weight:800;padding:.1rem .4rem;border-radius:20px;margin-left:.3rem; }
.ptag-n { background:rgba(37,99,235,.15);color:#2563EB; }
.ptag-p { background:rgba(139,92,246,.15);color:#8B5CF6; }
.res-box { background:var(--card-bg);border:1.5px solid var(--card-border);border-radius:14px;padding:1rem 1.2rem;margin-bottom:1.25rem;min-height:50px; }
.res-title { font-size:.78rem;font-weight:800;color:var(--muted-color);margin-bottom:.45rem; }
.res-chips { display:flex;flex-wrap:wrap;gap:.4rem; }
.chip { display:inline-flex;align-items:center;background:rgba(37,99,235,.15);color:#2563EB;border-radius:20px;padding:.25rem .75rem;font-size:.78rem;font-weight:800; }
.chip.cp { background:rgba(139,92,246,.15);color:#8B5CF6; }
.chip.cs { background:rgba(239,68,68,.15);color:#EF4444; }
.chip.csp { background:rgba(236,72,153,.15);color:#EC4899; }
.res-total { font-size:.9rem;font-weight:900;color:#2563EB;margin-top:.6rem; }
.qty-btn-step { width:38px;height:38px;border-radius:9px;border:1.5px solid var(--input-border);background:var(--input-bg);font-size:1.2rem;font-weight:800;cursor:pointer;color:var(--heading-color);transition:background .15s,border-color .25s; }
.qty-input-step { width:80px;text-align:center;font-weight:800;font-size:1.1rem; }
</style>

@if ($esMoonIce)
<div style="background:rgba(245,158,11,.12);border:1.5px solid rgba(245,158,11,.3);border-radius:12px;padding:.85rem 1.1rem;margin-bottom:1.25rem;font-size:.85rem;color:#F59E0B;font-weight:800;display:flex;align-items:center;gap:.5rem;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; flex-shrink: 0;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
    <span><strong>Moon Ice</strong> — Incluye la base + <strong>4 acompañamientos</strong>. Toppings Premium +$1.000 c/u.</span>
</div>
@else
<div style="background:rgba(37,99,235,.12);border:1.5px solid rgba(37,99,235,.3);border-radius:12px;padding:.85rem 1.1rem;margin-bottom:1.25rem;font-size:.85rem;color:#2563EB;font-weight:800;display:flex;align-items:center;gap:.5rem;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; flex-shrink: 0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
    <span>Frutas y Dulces: $2.500 c/u &nbsp;·&nbsp; Premium: $3.000 c/u &nbsp;·&nbsp; Salsas: $2.500 c/u &nbsp;·&nbsp; Salsas Premium: $3.000 c/u</span>
</div>
@endif

<div class="res-box">
    <div class="res-title">Toppings seleccionados @if ($esMoonIce)<span style="color:#F59E0B">(max. 4)</span>@endif</div>
    <div class="res-chips" id="chipsContainer"><span style="color:var(--muted-color);font-size:.8rem;font-weight:600">Ninguno aún</span></div>
    <div class="res-total" id="totalToppings" style="display:none"></div>
</div>

<div class="topping-section">
    <div class="topping-section-title" style="color:#10B981">Toppings Frutas <span class="ptag ptag-n">{{ $esMoonIce ? 'Incluido' : '$2.500 c/u' }}</span></div>
    <div class="topping-grid">
        @foreach ($toppingsFrutas as $t)
        <label class="topping-btn" onclick="tt(this,'frutas')"><input type="checkbox" name="toppings_frutas[]" value="{{ $t }}">{{ $t }}</label>
        @endforeach
    </div>
</div>
<div class="topping-section">
    <div class="topping-section-title" style="color:#8B5CF6">Toppings Premium <span class="ptag ptag-p">{{ $esMoonIce ? '+$1.000 c/u' : '$3.000 c/u' }}</span></div>
    <div class="topping-grid">
        @foreach ($toppingsPremium as $t)
        <label class="topping-btn" onclick="tt(this,'premium')"><input type="checkbox" name="toppings_premium[]" value="{{ $t }}">{{ $t }}</label>
        @endforeach
    </div>
</div>
<div class="topping-section">
    <div class="topping-section-title" style="color:#F59E0B">Toppings Dulces <span class="ptag ptag-n">{{ $esMoonIce ? 'Incluido' : '$2.500 c/u' }}</span></div>
    <div class="topping-grid">
        @foreach ($toppingsDulces as $t)
        <label class="topping-btn" onclick="tt(this,'dulces')"><input type="checkbox" name="toppings_dulces[]" value="{{ $t }}">{{ $t }}</label>
        @endforeach
    </div>
</div>
<div class="topping-section">
    <div class="topping-section-title" style="color:#EF4444">Salsas <span class="ptag ptag-n">{{ $esMoonIce ? 'Incluido' : '$2.500 c/u' }}</span></div>
    <div class="topping-grid">
        @foreach ($toppingsSalsas as $t)
        <label class="topping-btn" onclick="tt(this,'salsas')"><input type="checkbox" name="toppings_salsas[]" value="{{ $t }}">{{ $t }}</label>
        @endforeach
    </div>
</div>
<div class="topping-section">
    <div class="topping-section-title" style="color:#EC4899">Salsas Premium Hersheys <span class="ptag ptag-p">{{ $esMoonIce ? '+$1.000 c/u' : '$3.000 c/u' }}</span></div>
    <div class="topping-grid">
        @foreach ($salsasPremium as $t)
        <label class="topping-btn" onclick="tt(this,'salsasp')"><input type="checkbox" name="toppings_salsasp[]" value="{{ $t }}">{{ $t }}</label>
        @endforeach
    </div>
</div>

<label>Cantidad</label>
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:.5rem">
    <button type="button" onclick="cq(-1)" class="qty-btn-step">-</button>
    <input type="number" name="cantidad" id="qty" value="1" min="1" max="20" class="qty-input-step" onchange="ar()">
    <button type="button" onclick="cq(1)" class="qty-btn-step">+</button>
</div>
<label>Observaciones <span style="font-weight:400;color:var(--muted-color)">(opcional)</span></label>
<textarea name="observaciones" rows="2" placeholder="Indicaciones especiales..."></textarea>

<script>
var pb = {{ $precioMostrar }};
var esMoonIce = {{ $esMoonIce ? 'true' : 'false' }};
var maxMoon = 4;
var pm = {frutas:2500, premium:3000, dulces:2500, salsas:2500, salsasp:3000};
var pmMoon = {frutas:0, premium:1000, dulces:0, salsas:0, salsasp:1000};
var cm = {frutas:'sel-frutas', premium:'sel-premium', dulces:'sel-dulces', salsas:'sel-salsas', salsasp:'sel-salsasp'};
var ccm = {frutas:'', premium:'cp', dulces:'', salsas:'cs', salsasp:'csp'};

function contarTodos() {
    var t = 0;
    ['toppings_frutas[]','toppings_premium[]','toppings_dulces[]','toppings_salsas[]','toppings_salsasp[]']
        .forEach(function(n){ t += document.querySelectorAll('input[name="'+n+'"]:checked').length; });
    return t;
}
function tt(lbl, tipo) {
    var inp = lbl.querySelector('input');
    setTimeout(function() {
        if (esMoonIce && inp.checked && contarTodos() > maxMoon) {
            inp.checked = false; lbl.classList.remove(cm[tipo]); ar(); return;
        }
        inp.checked ? lbl.classList.add(cm[tipo]) : lbl.classList.remove(cm[tipo]);
        ar();
    }, 0);
}
function ar() {
    var cc = document.getElementById('chipsContainer');
    var te = document.getElementById('totalToppings');
    var chips = []; var costo = 0;
    var grupos = [
        {n:'toppings_frutas[]',t:'frutas'},
        {n:'toppings_premium[]',t:'premium'},
        {n:'toppings_dulces[]',t:'dulces'},
        {n:'toppings_salsas[]',t:'salsas'},
        {n:'toppings_salsasp[]',t:'salsasp'}
    ];
    grupos.forEach(function(g) {
        document.querySelectorAll('input[name="'+g.n+'"]:checked').forEach(function(i) {
            chips.push({l: i.value, t: g.t});
            costo += esMoonIce ? pmMoon[g.t] : pm[g.t];
        });
    });
    if (!chips.length) {
        cc.innerHTML = '<span style="color:var(--muted-color);font-size:.8rem;font-weight:600">Ninguno aún</span>';
        te.style.display = 'none';
    } else {
        var avisoMoon = '';
        if (esMoonIce) {
            var restantes = maxMoon - chips.length;
            var color = restantes === 0 ? '#EF4444' : '#10B981';
            avisoMoon = '<div style="font-size:.78rem;color:'+color+';font-weight:800;margin-top:.4rem">'
                + (restantes > 0
                    ? 'Puedes elegir ' + restantes + ' acompañamiento' + (restantes > 1 ? 's' : '') + ' más'
                    : 'Máximo de 4 acompañamientos alcanzado')
                + '</div>';
        }
        cc.innerHTML = chips.map(function(c){ return '<span class="chip '+ccm[c.t]+'">'+c.l+'</span>'; }).join('') + avisoMoon;
        var q = parseInt(document.getElementById('qty').value) || 1;
        var totalPrecio = (pb + costo) * q;
        te.textContent = 'Total estimado: $' + totalPrecio.toLocaleString('es-CO');
        te.style.display = 'block';
    }
}
function cq(d) {
    var i = document.getElementById('qty');
    var v = parseInt(i.value) + d;
    if (v < 1) v = 1; if (v > 20) v = 20;
    i.value = v; ar();
}
</script>

@elseif ($esEnsalada)
<style>
.ensalada-tamano-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:.75rem;margin-bottom:1.25rem; }
@media(max-width:600px){ .ensalada-tamano-grid { grid-template-columns:1fr; } }
.ensalada-tam-btn { border:2px solid var(--card-border);border-radius:14px;background:var(--card-bg);padding:1rem .75rem;text-align:center;cursor:pointer;font-family:'Nunito',sans-serif;transition:all .15s ease;user-select:none;color:var(--heading-color); }
.ensalada-tam-btn:hover { border-color:#F97316; }
.ensalada-tam-btn.sel { border-color:#F97316;background:rgba(249,115,22,.12);box-shadow:0 0 0 3px rgba(249,115,22,.2); }
.ensalada-tam-btn input { display:none; }
.etam-nombre { font-size:.95rem;font-weight:900;color:var(--heading-color);margin-bottom:.2rem; }
.etam-sabores { font-size:.78rem;font-weight:800;color:#F97316;margin-bottom:.15rem; }
.etam-precio { font-size:1.05rem;font-weight:900;color:#2563EB; }
.etam-desc { font-size:.75rem;color:var(--muted-color);margin-top:.25rem;line-height:1.3; }

.sabor-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(145px,1fr));gap:.6rem;margin-bottom:1.25rem; }
.sabor-btn { border:1.5px solid var(--card-border);border-radius:12px;background:var(--card-bg);padding:.65rem .8rem;text-align:center;cursor:pointer;font-family:'Nunito',sans-serif;font-size:.86rem;font-weight:800;color:var(--heading-color);transition:all .15s ease;user-select:none;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,.04); }
.sabor-btn:hover { border-color:#F97316;color:#F97316;transform:translateY(-2px); }
.sabor-btn.sel { border-color:#F97316;background:rgba(249,115,22,.15);color:#F97316;box-shadow:0 0 0 3px rgba(249,115,22,.2);font-weight:900; }
.sabor-btn input { display:none; }

.sabores-section { display:none; }
.sabores-section.visible { display:block; }
.sabor-aviso { background:rgba(249,115,22,.1);border:1.5px solid rgba(249,115,22,.25);border-radius:12px;padding:.8rem 1.1rem;font-size:.86rem;color:#F97316;font-weight:800;margin-bottom:1.25rem; }
.qty-btn-step { width:38px;height:38px;border-radius:9px;border:1.5px solid var(--input-border);background:var(--input-bg);font-size:1.2rem;font-weight:800;cursor:pointer;color:var(--heading-color);transition:background .15s,border-color .25s; }
.qty-input-step { width:80px;text-align:center;font-weight:800;font-size:1.1rem; }
</style>

<div style="font-size:.8rem;font-weight:800;color:var(--label-color);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.65rem">1. Elige el tamaño</div>
<div class="ensalada-tamano-grid">
    <label class="ensalada-tam-btn" onclick="elegirTamano(this,1,'Mini',15000)">
        <input type="radio" name="tamano_ensalada" value="mini">
        <div class="etam-nombre">Mini</div>
        <div class="etam-sabores">1 sabor de helado</div>
        <div class="etam-precio">$15.000</div>
        <div class="etam-desc">Fruta fresca · salsa · galletas · queso doble crema</div>
    </label>
    <label class="ensalada-tam-btn" onclick="elegirTamano(this,2,'Especial',19000)">
        <input type="radio" name="tamano_ensalada" value="especial">
        <div class="etam-nombre">Especial</div>
        <div class="etam-sabores">2 sabores de helado</div>
        <div class="etam-precio">$19.000</div>
        <div class="etam-desc">Fruta fresca · salsa · galleta · queso doble crema</div>
    </label>
    <label class="ensalada-tam-btn" onclick="elegirTamano(this,3,'Super Especial',25000)">
        <input type="radio" name="tamano_ensalada" value="super_especial">
        <div class="etam-nombre">Super Especial</div>
        <div class="etam-sabores">3 sabores de helado</div>
        <div class="etam-precio">$25.000</div>
        <div class="etam-desc">Fruta fresca · salsa · crema chantilly · galletas · queso doble crema</div>
    </label>
</div>

<div class="sabores-section" id="saboresSection">
    <div class="sabor-aviso" id="saborAviso"></div>
    <div style="font-size:.8rem;font-weight:800;color:var(--label-color);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.65rem">2. Elige tus sabores de helado</div>
    <div class="sabor-grid" id="saborGrid">
        @foreach ($saboresHelado as $s)
        <label class="sabor-btn" onclick="ts(this)">
            <input type="checkbox" name="sabores[]" value="{{ $s }}">
            {{ $s }}
        </label>
        @endforeach
    </div>
</div>

<label>Cantidad</label>
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:.5rem">
    <button type="button" onclick="cqe(-1)" class="qty-btn-step">-</button>
    <input type="number" name="cantidad" id="qtyE" value="1" min="1" max="20" class="qty-input-step">
    <button type="button" onclick="cqe(1)" class="qty-btn-step">+</button>
</div>
<label>Observaciones <span style="font-weight:400;color:var(--muted-color)">(opcional)</span></label>
<textarea name="observaciones" rows="2" placeholder="Indicaciones especiales para tu ensalada..."></textarea>
<input type="hidden" name="precio_ensalada" id="precioEnsalada" value="">
<input type="hidden" name="tamano" id="tamanoEnsalada" value="">

<script>
var maxSabores = 0;
function elegirTamano(lbl, max, nombre, precio) {
    document.querySelectorAll('.ensalada-tam-btn').forEach(function(b){ b.classList.remove('sel'); });
    lbl.classList.add('sel');
    lbl.querySelector('input').checked = true;
    document.getElementById('precioEnsalada').value = precio;
    document.getElementById('tamanoEnsalada').value = nombre;
    maxSabores = max;
    document.querySelectorAll('#saborGrid input').forEach(function(i){ i.checked = false; });
    document.querySelectorAll('#saborGrid .sabor-btn').forEach(function(b){ b.classList.remove('sel'); });
    document.getElementById('saborAviso').innerHTML =
        '<strong>' + nombre + '</strong> incluye <strong>' + max + ' sabor' + (max>1?'es':'') + ' de helado</strong>';
    document.getElementById('saboresSection').classList.add('visible');
}
function ts(lbl) {
    if (maxSabores === 0) return;
    var inp = lbl.querySelector('input');
    setTimeout(function() {
        var checked = document.querySelectorAll('#saborGrid input:checked');
        if (inp.checked && checked.length > maxSabores) {
            inp.checked = false; lbl.classList.remove('sel'); return;
        }
        inp.checked ? lbl.classList.add('sel') : lbl.classList.remove('sel');
    }, 0);
}
function cqe(d) {
    var i = document.getElementById('qtyE');
    var v = parseInt(i.value) + d;
    if (v < 1) v = 1; if (v > 20) v = 20; i.value = v;
}
</script>

@elseif ($esMalteadaSencilla)
<style>
.malt-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(145px,1fr));gap:.6rem;margin-bottom:1.25rem; }
.malt-btn { border:1.5px solid var(--card-border);border-radius:12px;background:var(--card-bg);padding:.65rem .8rem;text-align:center;cursor:pointer;font-family:'Nunito',sans-serif;font-size:.86rem;font-weight:800;color:var(--heading-color);transition:all .15s ease;user-select:none;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,.04); }
.malt-btn:hover { border-color:#8B5CF6;color:#8B5CF6;transform:translateY(-2px); }
.malt-btn.sel { border-color:#8B5CF6;background:rgba(139,92,246,.15);color:#8B5CF6;box-shadow:0 0 0 3px rgba(139,92,246,.2);font-weight:900; }
.malt-btn input { display:none; }
.qty-btn-step { width:38px;height:38px;border-radius:9px;border:1.5px solid var(--input-border);background:var(--input-bg);font-size:1.2rem;font-weight:800;cursor:pointer;color:var(--heading-color);transition:background .15s,border-color .25s; }
.qty-input-step { width:80px;text-align:center;font-weight:800;font-size:1.1rem; }
</style>

<div style="background:rgba(139,92,246,.12);border:1.5px solid rgba(139,92,246,.3);border-radius:12px;padding:.85rem 1.1rem;margin-bottom:1.25rem;font-size:.85rem;color:#8B5CF6;font-weight:800;display:flex;align-items:center;gap:.5rem;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; flex-shrink:0;"><path d="M6 10h12l-1 11H7z"/><path d="M9 10V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v5"/><path d="M6 10h12"/></svg>
    <span><strong>Malteada Sencilla</strong> — Elige <strong>1 sabor</strong>. Precio fijo: <strong>$13.000</strong></span>
</div>

<div style="font-size:.8rem;font-weight:800;color:var(--label-color);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.65rem">Elige tu sabor</div>
<div class="malt-grid">
    @foreach ($saboresMalteada as $s)
    <label class="malt-btn" onclick="selSaborMalt(this)">
        <input type="radio" name="sabores[]" value="{{ $s }}" required>
        {{ $s }}
    </label>
    @endforeach
</div>
<input type="hidden" name="precio_malteada" value="13000">

<label>Cantidad</label>
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:.5rem">
    <button type="button" onclick="cqm(-1)" class="qty-btn-step">-</button>
    <input type="number" name="cantidad" id="qtyM" value="1" min="1" max="20" class="qty-input-step">
    <button type="button" onclick="cqm(1)" class="qty-btn-step">+</button>
</div>
<label>Observaciones <span style="font-weight:400;color:var(--muted-color)">(opcional)</span></label>
<textarea name="observaciones" rows="2" placeholder="Indicaciones especiales para tu malteada..."></textarea>

<script>
function selSaborMalt(lbl) {
    document.querySelectorAll('.malt-btn').forEach(function(b){ b.classList.remove('sel'); });
    lbl.classList.add('sel');
    lbl.querySelector('input').checked = true;
}
function cqm(d) {
    var i = document.getElementById('qtyM');
    var v = parseInt(i.value) + d;
    if (v < 1) v = 1; if (v > 20) v = 20; i.value = v;
}
</script>

@elseif ($esMalteadaEspecial)
<style>
.qty-btn-step { width:38px;height:38px;border-radius:9px;border:1.5px solid var(--input-border);background:var(--input-bg);font-size:1.2rem;font-weight:800;cursor:pointer;color:var(--heading-color);transition:background .15s,border-color .25s; }
.qty-input-step { width:80px;text-align:center;font-weight:800;font-size:1.1rem; }
</style>

<div style="background:rgba(245,158,11,.12);border:1.5px solid rgba(245,158,11,.3);border-radius:12px;padding:.85rem 1.1rem;margin-bottom:1.25rem;font-size:.85rem;color:#F59E0B;font-weight:800;display:flex;align-items:center;gap:.5rem;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; flex-shrink:0;"><path d="M6 10h12l-1 11H7z"/><path d="M9 10V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v5"/><path d="M6 10h12"/></svg>
    <span><strong>Malteada Especial</strong> — Precio fijo: <strong>${{ number_format($precioMostrar, 0, ',', '.') }}</strong></span>
</div>
<div style="background:var(--card-bg);border:1.5px solid var(--card-border);border-radius:12px;padding:1rem 1.2rem;margin-bottom:1.25rem;font-size:.9rem;color:var(--heading-color);font-weight:800">
    Sabor seleccionado: <span style="color:#2563EB">{{ $producto->nombre }}</span>
</div>

<label>Cantidad</label>
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:.5rem">
    <button type="button" onclick="cqme(-1)" class="qty-btn-step">-</button>
    <input type="number" name="cantidad" id="qtyME" value="1" min="1" max="20" class="qty-input-step">
    <button type="button" onclick="cqme(1)" class="qty-btn-step">+</button>
</div>
<label>Observaciones <span style="font-weight:400;color:var(--muted-color)">(opcional)</span></label>
<textarea name="observaciones" rows="2" placeholder="Indicaciones especiales..."></textarea>

<script>
function cqme(d) {
    var i = document.getElementById('qtyME');
    var v = parseInt(i.value) + d;
    if (v < 1) v = 1; if (v > 20) v = 20; i.value = v;
}
</script>

@elseif ($esCanasta)
@php
    $tieneSelector = !empty($canastaCfg['selector']) && !empty($canastaCfg['opciones']);
    $esAcida       = !empty($canastaCfg['acida']);
    $listaSabores  = $esAcida ? $saboresAcidos : $saboresHelado;
    $maxCanasta    = (int)($canastaCfg['max'] ?? 3);
    $precioCanasta = (int)($canastaCfg['precio'] ?? $precioMostrar);
@endphp

<style>
.canasta-opcion-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:.75rem;margin-bottom:1.25rem; }
.canasta-opcion-btn { border:2px solid var(--card-border);border-radius:14px;background:var(--card-bg);padding:.9rem .75rem;text-align:center;cursor:pointer;font-family:'Nunito',sans-serif;transition:all .15s ease;user-select:none;color:var(--heading-color); }
.canasta-opcion-btn:hover { border-color:#2563EB; }
.canasta-opcion-btn.sel { border-color:#2563EB;background:rgba(37,99,235,.12);box-shadow:0 0 0 3px rgba(37,99,235,.2); }
.canasta-opcion-btn input { display:none; }
.cop-label { font-size:.95rem;font-weight:900;color:var(--heading-color);margin-bottom:.2rem; }
.cop-precio { font-size:1rem;font-weight:900;color:#2563EB; }

.canasta-sabor-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:.6rem;margin-bottom:1.25rem; }
.canasta-sabor-btn { border:1.5px solid var(--card-border);border-radius:12px;background:var(--card-bg);padding:.65rem .8rem;text-align:center;cursor:pointer;font-family:'Nunito',sans-serif;font-size:.86rem;font-weight:800;color:var(--heading-color);transition:all .15s ease;user-select:none;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,.04); }
.canasta-sabor-btn:hover { border-color:#2563EB;color:#2563EB;transform:translateY(-2px); }
.canasta-sabor-btn.sel { border-color:#2563EB;background:rgba(37,99,235,.15);color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.2);font-weight:900; }
.canasta-sabor-btn input { display:none; }

.canasta-sabores-wrap { display:none; }
.canasta-sabores-wrap.visible { display:block; }
.canasta-aviso { background:rgba(37,99,235,.1);border:1.5px solid rgba(37,99,235,.25);border-radius:12px;padding:.8rem 1.1rem;font-size:.86rem;color:#2563EB;font-weight:800;margin-bottom:1.25rem;display:flex;align-items:center;gap:.4rem; }
.qty-btn-step { width:38px;height:38px;border-radius:9px;border:1.5px solid var(--input-border);background:var(--input-bg);font-size:1.2rem;font-weight:800;cursor:pointer;color:var(--heading-color);transition:background .15s,border-color .25s; }
.qty-input-step { width:80px;text-align:center;font-weight:800;font-size:1.1rem; }
</style>

<div class="canasta-aviso">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; flex-shrink:0;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    <span><strong>Canasta {{ $producto->nombre }}</strong>
    @if (!$tieneSelector)
     — {{ $maxCanasta }} sabor{{ $maxCanasta > 1 ? 'es' : '' }} de helado · <strong>${{ number_format($precioCanasta, 0, ',', '.') }}</strong>
    @endif
    @if ($esAcida) &nbsp;·&nbsp; <span style="color:#8B5CF6">Solo sabores ácidos</span>@endif</span>
</div>

@if ($tieneSelector)
<div style="font-size:.8rem;font-weight:800;color:var(--label-color);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.65rem">1. Elige la opción</div>
<div class="canasta-opcion-grid">
    @foreach ($canastaCfg['opciones'] as $idx => $op)
    <label class="canasta-opcion-btn" onclick="elegirOpcionCanasta(this,{{ (int)$op['sabores'] }},{{ (int)$op['precio'] }})">
        <input type="radio" name="canasta_opcion" value="{{ (int)$op['sabores'] }}">
        <div class="cop-label">{{ (int)$op['sabores'] }} sabores</div>
        <div class="cop-precio">${{ number_format($op['precio'], 0, ',', '.') }}</div>
    </label>
    @endforeach
</div>
<input type="hidden" name="precio_canasta" id="precioCanasta" value="">
<div class="canasta-sabores-wrap" id="canastaSaboresWrap">
    <div class="canasta-aviso" id="canastaAviso"></div>
    <div style="font-size:.8rem;font-weight:800;color:var(--label-color);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.65rem">2. Elige tus sabores de helado</div>
    <div class="canasta-sabor-grid" id="canastaSaborGrid">
        @foreach ($listaSabores as $s)
        <label class="canasta-sabor-btn" onclick="tsCanasta(this)">
            <input type="checkbox" name="sabores[]" value="{{ $s }}">
            {{ $s }}
        </label>
        @endforeach
    </div>
</div>
<script>
var maxCanasta = 0;
function elegirOpcionCanasta(lbl, max, precio) {
    document.querySelectorAll('.canasta-opcion-btn').forEach(function(b){ b.classList.remove('sel'); });
    lbl.classList.add('sel');
    lbl.querySelector('input').checked = true;
    document.getElementById('precioCanasta').value = precio;
    maxCanasta = max;
    document.querySelectorAll('#canastaSaborGrid input').forEach(function(i){ i.checked = false; });
    document.querySelectorAll('#canastaSaborGrid .canasta-sabor-btn').forEach(function(b){ b.classList.remove('sel'); });
    document.getElementById('canastaAviso').innerHTML =
        '<span>Elige <strong>' + max + ' sabor' + (max>1?'es':'') + ' de helado</strong></span>';
    document.getElementById('canastaSaboresWrap').classList.add('visible');
}
function tsCanasta(lbl) {
    if (maxCanasta === 0) return;
    var inp = lbl.querySelector('input');
    setTimeout(function() {
        var checked = document.querySelectorAll('#canastaSaborGrid input:checked');
        if (inp.checked && checked.length > maxCanasta) {
            inp.checked = false; lbl.classList.remove('sel'); return;
        }
        inp.checked ? lbl.classList.add('sel') : lbl.classList.remove('sel');
    }, 0);
}
</script>
@else
<input type="hidden" name="precio_canasta" value="{{ $precioCanasta }}">
<div style="font-size:.8rem;font-weight:800;color:var(--label-color);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.65rem">Elige tus {{ $maxCanasta }} sabores de helado:</div>
<div class="canasta-sabor-grid" id="canastaSaborGrid">
    @foreach ($listaSabores as $s)
    <label class="canasta-sabor-btn" onclick="tsCanasta(this)">
        <input type="checkbox" name="sabores[]" value="{{ $s }}">
        {{ $s }}
    </label>
    @endforeach
</div>
<script>
var maxCanasta = {{ $maxCanasta }};
function tsCanasta(lbl) {
    var inp = lbl.querySelector('input');
    setTimeout(function() {
        var checked = document.querySelectorAll('#canastaSaborGrid input:checked');
        if (inp.checked && checked.length > maxCanasta) {
            inp.checked = false; lbl.classList.remove('sel'); return;
        }
        inp.checked ? lbl.classList.add('sel') : lbl.classList.remove('sel');
    }, 0);
}
</script>
@endif

<label>Cantidad</label>
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:.5rem">
    <button type="button" onclick="cqcan(-1)" class="qty-btn-step">-</button>
    <input type="number" name="cantidad" id="qtyCan" value="1" min="1" max="20" class="qty-input-step">
    <button type="button" onclick="cqcan(1)" class="qty-btn-step">+</button>
</div>
<label>Observaciones <span style="font-weight:400;color:var(--muted-color)">(opcional)</span></label>
<textarea name="observaciones" rows="2" placeholder="Indicaciones especiales para tu canasta..."></textarea>
<script>
function cqcan(d) {
    var i = document.getElementById('qtyCan');
    var v = parseInt(i.value) + d;
    if (v < 1) v = 1; if (v > 20) v = 20; i.value = v;
}
</script>

@else
<style>
.qty-btn-step { width:38px;height:38px;border-radius:9px;border:1.5px solid var(--input-border);background:var(--input-bg);font-size:1.2rem;font-weight:800;cursor:pointer;color:var(--heading-color);transition:background .15s,border-color .25s; }
.qty-input-step { width:80px;text-align:center;font-weight:800;font-size:1.1rem; }
</style>

<label>Cantidad</label>
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:.5rem">
    <button type="button" onclick="cqg(-1)" class="qty-btn-step">-</button>
    <input type="number" name="cantidad" id="qtyG" value="1" min="1" max="20" class="qty-input-step">
    <button type="button" onclick="cqg(1)" class="qty-btn-step">+</button>
</div>
<label>Observaciones <span style="font-weight:400;color:var(--muted-color)">(opcional)</span></label>
<textarea name="observaciones" rows="2" placeholder="Indicaciones especiales..."></textarea>
<script>
function cqg(d){var i=document.getElementById('qtyG');var v=parseInt(i.value)+d;if(v<1)v=1;if(v>20)v=20;i.value=v;}
</script>
@endif

<div style="display:flex;gap:.75rem;margin-top:1.5rem">
    <button type="submit" class="btn-next" style="flex:1;background:#2563EB;display:inline-flex;align-items:center;justify-content:center;"
            onclick="document.getElementById('accion_form').value='carrito'">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; margin-right: 6px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg> Agregar al carrito
    </button>
    <button type="submit" class="btn-next" style="flex:1;display:inline-flex;align-items:center;justify-content:center;background:#10B981"
            onclick="document.getElementById('accion_form').value='pedir'">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; margin-right: 6px;"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Pedir ahora
    </button>
</div>
</form>
@endsection
