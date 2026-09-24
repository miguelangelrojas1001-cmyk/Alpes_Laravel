<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Iniciar Checkout desde el Carrito de compras (Etapa 5)
     */
    public function iniciarCarrito(Request $request)
    {
        $carrito = $request->session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito.index');
        }

        $request->session()->put('checkout_modo', 'carrito');
        $request->session()->put('checkout_carrito_snapshot', $carrito);

        return redirect()->route('checkout.entrega');
    }

    /**
     * Step 1: Product Customization Form
     */
    public function opciones(Request $request)
    {
        $idproducto = $request->get('idproducto');

        if (!$idproducto) {
            return redirect()->route('catalogo.index');
        }

        $producto = Producto::with('catalogo')->where('idproducto', $idproducto)->first();

        if (!$producto) {
            return redirect()->route('catalogo.index');
        }

        return view('checkout.step1_opciones', compact('producto'));
    }

    /**
     * Step 1 (POST): Save Options & Add to Cart / Order
     */
    public function guardarOpciones(Request $request)
    {
        $idproducto = $request->input('idproducto');

        if (!$idproducto) {
            return redirect()->route('catalogo.index');
        }

        $producto = Producto::with('catalogo')->where('idproducto', $idproducto)->first();

        if (!$producto) {
            return redirect()->route('catalogo.index');
        }

        $cantidad = max(1, (int)$request->input('cantidad', 1));
        $obs      = trim($request->input('observaciones', ''));

        $nombreCat       = strtolower($producto->catalogo ? $producto->catalogo->nombre : '');
        $nombreProd      = strtolower($producto->nombre ?? '');
        $esYogurt        = str_contains($nombreCat, 'yogurt') || str_contains($nombreCat, 'yogur');
        $esEnsalada      = str_contains($nombreCat, 'ensalada');
        $esMalteada      = str_contains($nombreCat, 'malteada');
        $esCanasta       = str_contains($nombreCat, 'canasta');
        $esMalteadaSencilla = $esMalteada && str_contains($nombreProd, 'sencilla');
        $esMalteadaEspecial = $esMalteada && !$esMalteadaSencilla;

        $precioBase = (float)$producto->precio;
        if ($esYogurt && $precioBase < 1000 && $precioBase > 0) {
            $precioBase *= 1000;
        }

        $costoToppings   = 0;
        $toppingsResumen = [];

        if ($esYogurt) {
            $esMoonIce = str_contains($nombreProd, 'moon ice');
            $tFrutas   = $request->input('toppings_frutas', []);
            $tPremium  = $request->input('toppings_premium', []);
            $tDulces   = $request->input('toppings_dulces', []);
            $tSalsas   = $request->input('toppings_salsas', []);
            $tSalsasP  = $request->input('toppings_salsasp', []);

            if ($esMoonIce) {
                foreach ($tFrutas  as $t) { $toppingsResumen[] = e($t); }
                foreach ($tPremium as $t) { $costoToppings += 1000; $toppingsResumen[] = e($t) . ' (Premium)'; }
                foreach ($tDulces  as $t) { $toppingsResumen[] = e($t); }
                foreach ($tSalsas  as $t) { $toppingsResumen[] = e($t); }
                foreach ($tSalsasP as $t) { $costoToppings += 1000; $toppingsResumen[] = e($t) . ' (Hersheys)'; }
            } else {
                foreach ($tFrutas  as $t) { $costoToppings += 2500; $toppingsResumen[] = e($t); }
                foreach ($tPremium as $t) { $costoToppings += 3000; $toppingsResumen[] = e($t) . ' (Premium)'; }
                foreach ($tDulces  as $t) { $costoToppings += 2500; $toppingsResumen[] = e($t); }
                foreach ($tSalsas  as $t) { $costoToppings += 2500; $toppingsResumen[] = e($t); }
                foreach ($tSalsasP as $t) { $costoToppings += 3000; $toppingsResumen[] = e($t) . ' (Hersheys)'; }
            }
            $sabores = implode(', ', $toppingsResumen);
            $tamano  = '';

        } elseif ($esMalteadaSencilla) {
            $saboresArr = $request->input('sabores', []);
            $sabores    = implode(', ', array_map('e', $saboresArr));
            $precioBase = 13000;
            $tamano     = '';

        } elseif ($esMalteadaEspecial) {
            $sabores = '';
            $tamano  = '';

        } elseif ($esCanasta) {
            $saboresArr = $request->input('sabores', []);
            $sabores    = implode(', ', array_map('e', $saboresArr));
            $tamano     = '';
            $precioForm = (float)$request->input('precio_canasta', 0);
            if ($precioForm > 0) {
                $precioBase = $precioForm;
            }

        } elseif ($esEnsalada) {
            $saboresArr = $request->input('sabores', []);
            $sabores    = implode(', ', array_map('e', $saboresArr));
            $tamano     = e(trim($request->input('tamano', '')));
            $precioForm = (float)$request->input('precio_ensalada', $precioBase);
            $precioBase = $precioForm > 0 ? $precioForm : $precioBase;

        } else {
            $saboresArr = $request->input('sabores', []);
            $sabores    = implode(', ', array_map('e', $saboresArr));
            $tamano     = e($request->input('tamano', ''));
        }

        $precioUnitario = $precioBase + $costoToppings;
        $accion         = $request->input('accion', 'pedir');

        if ($accion === 'carrito') {
            $carrito = $request->session()->get('carrito', []);
            $clave = $producto->idproducto . '_' . md5($sabores . $tamano . $obs);

            if (isset($carrito[$clave])) {
                $carrito[$clave]['cantidad'] += $cantidad;
            } else {
                $carrito[$clave] = [
                    'idproducto'          => $producto->idproducto,
                    'nombre'              => $producto->nombre,
                    'precio'              => $precioUnitario,
                    'precio_base'         => $precioBase,
                    'costo_toppings'      => $costoToppings,
                    'sabores'             => $sabores,
                    'tamano'              => $tamano,
                    'observaciones'       => $obs,
                    'cantidad'            => $cantidad,
                    'es_yogurt'           => $esYogurt,
                    'es_ensalada'         => $esEnsalada,
                    'es_malteada_sencilla'=> $esMalteadaSencilla,
                    'es_malteada_especial'=> $esMalteadaEspecial,
                    'es_canasta'          => $esCanasta,
                ];
            }

            $request->session()->put('carrito', $carrito);
            $request->session()->put('checkout_modo', 'carrito');
            $request->session()->flash('carrito_msg', $producto->nombre . ' agregado al carrito');

            $idcatalogo = $producto->idcatalogo;
            return redirect()->route('catalogo.index', array_filter(['idcatalogo' => $idcatalogo, 'agregado' => 1]));

        } else {
            // Modo "Pedir ahora": Guarda la orden individual en sesión y redirige a la entrega (Step 2)
            $request->session()->put('checkout_modo', 'individual');
            $request->session()->put('checkout_orden', [
                'idproducto'          => $producto->idproducto,
                'nombre_producto'     => $producto->nombre,
                'precio_unitario'     => $precioUnitario,
                'precio_base'         => $precioBase,
                'costo_toppings'      => $costoToppings,
                'costo_domicilio'     => 2000,
                'sabores'             => $sabores,
                'tamano'              => $tamano,
                'cantidad'            => $cantidad,
                'observaciones'       => $obs,
                'es_yogurt'           => $esYogurt,
                'es_ensalada'         => $esEnsalada,
                'es_malteada_sencilla'=> $esMalteadaSencilla,
                'es_malteada_especial'=> $esMalteadaEspecial,
                'es_canasta'          => $esCanasta,
            ]);

            return redirect()->route('checkout.entrega');
        }
    }

    /**
     * Step 2: Delivery Address Form
     */
    public function entrega(Request $request)
    {
        $modo = $request->session()->get('checkout_modo', 'individual');

        if ($modo === 'carrito') {
            if (empty($request->session()->get('carrito'))) {
                return redirect()->route('catalogo.index');
            }
        } else {
            if (empty($request->session()->get('checkout_orden'))) {
                return redirect()->route('catalogo.index');
            }
        }

        return view('checkout.step2_entrega');
    }

    /**
     * Step 2 (POST): Save Delivery Address
     */
    public function guardarEntrega(Request $request)
    {
        $request->validate([
            'destinatario' => 'required|string|max:255',
            'direccion'    => 'required|string|max:255',
            'barrio'       => 'required|string|max:255',
            'ciudad'       => 'required|string|max:255',
            'telefono'     => 'required|string|max:50',
        ]);

        $modo = $request->session()->get('checkout_modo', 'individual');

        if ($modo === 'carrito') {
            $carrito = $request->session()->get('carrito');
            if (empty($carrito)) {
                return redirect()->route('carrito.index');
            }
            $request->session()->put('checkout_carrito_snapshot', $carrito);
        } else {
            if (empty($request->session()->get('checkout_orden'))) {
                return redirect()->route('catalogo.index');
            }
        }

        $request->session()->put('checkout_entrega', [
            'destinatario'  => trim($request->input('destinatario')),
            'direccion'     => trim($request->input('direccion')),
            'barrio'        => trim($request->input('barrio')),
            'ciudad'        => trim($request->input('ciudad')),
            'telefono'      => trim($request->input('telefono')),
            'instrucciones' => trim($request->input('instrucciones', '')),
        ]);

        return redirect()->route('checkout.pago');
    }

    /**
     * Step 3: Payment Method Selection Form
     */
    public function pago(Request $request)
    {
        $modo = $request->session()->get('checkout_modo', 'individual');

        if ($modo === 'carrito') {
            $activo = $request->session()->get('carrito') ?? $request->session()->get('checkout_carrito_snapshot', []);
            if (empty($activo)) {
                return redirect()->route('carrito.index');
            }
            if (empty($request->session()->get('carrito')) && !empty($request->session()->get('checkout_carrito_snapshot'))) {
                $request->session()->put('carrito', $request->session()->get('checkout_carrito_snapshot'));
            }
        } else {
            if (empty($request->session()->get('checkout_orden'))) {
                return redirect()->route('catalogo.index');
            }
        }

        if (empty($request->session()->get('checkout_entrega'))) {
            return redirect()->route('checkout.entrega');
        }

        return view('checkout.step3_pago');
    }

    /**
     * Step 3 (POST): Confirm Order & Execute Transactional Writes to bdalpes
     */
    public function confirmar(Request $request)
    {
        $metodopago = $request->input('metodopago');

        if (!$metodopago || !in_array($metodopago, ['nequi', 'daviplata', 'tarjeta'])) {
            $request->session()->flash('checkout_error', 'Debes seleccionar un método de pago válido.');
            return redirect()->route('checkout.pago');
        }

        $modo    = $request->session()->get('checkout_modo', 'individual');
        $entrega = $request->session()->get('checkout_entrega');

        if ($modo === 'carrito') {
            $carrito = $request->session()->get('carrito') ?? $request->session()->get('checkout_carrito_snapshot', []);
            if (empty($carrito) || empty($entrega)) {
                return redirect()->route('carrito.index');
            }
        } else {
            $orden = $request->session()->get('checkout_orden');
            if (empty($orden) || empty($entrega)) {
                return redirect()->route('catalogo.index');
            }
        }

        $idpersona = Auth::id();
        $idpedido  = null;
        $idfactura = null;
        $idcliente = null;
        $iddireccion = null;

        try {
            DB::transaction(function () use ($modo, $entrega, $metodopago, $idpersona, &$idpedido, &$idfactura, &$idcliente, &$iddireccion, $request) {
                // 1. Obtener o crear cliente
                $cliente = DB::table('cliente')->where('idpersona', $idpersona)->first();
                if (!$cliente) {
                    $idcliente = DB::table('cliente')->insertGetId(['idpersona' => $idpersona]);
                } else {
                    $idcliente = $cliente->idcliente;
                }

                // 2. Insertar dirección
                $descDir = trim(($entrega['direccion'] ?? '') . ', ' . ($entrega['barrio'] ?? '') . ', ' . ($entrega['ciudad'] ?? ''));
                $iddireccion = DB::table('direccion')->insertGetId(['descripcion' => $descDir]);

                // 3. Procesar segun modo (carrito vs individual)
                if ($modo === 'carrito') {
                    $carrito  = $request->session()->get('carrito') ?? $request->session()->get('checkout_carrito_snapshot', []);
                    $subtotal = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $carrito));
                    $total    = $subtotal + 2000;

                    $idpedido = DB::table('pedido')->insertGetId([
                        'idcliente' => $idcliente,
                        'total'     => $total,
                        'fecha'     => now(),
                        'estado'    => 'pendiente',
                    ]);

                    $idfactura = DB::table('factura')->insertGetId([
                        'idpedido'     => $idpedido,
                        'fecha'        => now(),
                        'metodopago'   => $metodopago,
                        'totalfactura' => $total,
                    ]);

                    foreach ($carrito as $item) {
                        $sub = $item['precio'] * $item['cantidad'];

                        DB::table('detalle_pedido')->insert([
                            'idpedido'        => $idpedido,
                            'idproducto'      => $item['idproducto'],
                            'cantidad'        => $item['cantidad'],
                            'tamano'          => $item['tamano'] ?? '',
                            'sabores'         => $item['sabores'] ?? '',
                            'observaciones'   => $item['observaciones'] ?? '',
                            'precio_unitario' => $item['precio'],
                            'total'           => $sub,
                        ]);

                        DB::table('detalle_factura')->insert([
                            'idfactura'       => $idfactura,
                            'idproducto'      => $item['idproducto'],
                            'cantidad'        => $item['cantidad'],
                            'precio_unitario' => $item['precio'],
                            'total'           => $sub,
                        ]);

                        $this->descontarInsumos($item['idproducto'], $item['cantidad']);
                    }

                    $request->session()->put('checkout_orden', [
                        'metodopago' => $metodopago,
                        'items'      => $carrito,
                        'total'      => $total,
                    ]);

                } else {
                    $orden     = $request->session()->get('checkout_orden');
                    $subtotal  = $orden['precio_unitario'] * $orden['cantidad'];
                    $domicilio = (int)($orden['costo_domicilio'] ?? 2000);
                    $total     = $subtotal + $domicilio;

                    $idpedido = DB::table('pedido')->insertGetId([
                        'idcliente' => $idcliente,
                        'total'     => $total,
                        'fecha'     => now(),
                        'estado'    => 'pendiente',
                    ]);

                    DB::table('detalle_pedido')->insert([
                        'idpedido'        => $idpedido,
                        'idproducto'      => $orden['idproducto'],
                        'cantidad'        => $orden['cantidad'],
                        'tamano'          => $orden['tamano'] ?? '',
                        'sabores'         => $orden['sabores'] ?? '',
                        'observaciones'   => $orden['observaciones'] ?? '',
                        'precio_unitario' => $orden['precio_unitario'],
                        'total'           => $subtotal,
                    ]);

                    $idfactura = DB::table('factura')->insertGetId([
                        'idpedido'     => $idpedido,
                        'fecha'        => now(),
                        'metodopago'   => $metodopago,
                        'totalfactura' => $total,
                    ]);

                    DB::table('detalle_factura')->insert([
                        'idfactura'       => $idfactura,
                        'idproducto'      => $orden['idproducto'],
                        'cantidad'        => $orden['cantidad'],
                        'precio_unitario' => $orden['precio_unitario'],
                        'total'           => $subtotal,
                    ]);

                    $this->descontarInsumos($orden['idproducto'], $orden['cantidad']);

                    $orden['metodopago'] = $metodopago;
                    $request->session()->put('checkout_orden', $orden);
                }
            });

            // Asignación de domiciliario (fuera de la transacción principal para que si falla no provoque un rollback de la compra)
            try {
                $this->asignarDomiciliario($idpedido, $idcliente, $iddireccion, $entrega['telefono'] ?? null);
            } catch (\Exception $eDom) {
                Log::warning("[Domiciliario] Fallo al asignar pedido {$idpedido}: " . $eDom->getMessage());
            }

            $request->session()->put('ultima_factura', $idfactura);
            $request->session()->put('ultimo_pedido', $idpedido);

            return redirect()->route('checkout.factura');

        } catch (\Exception $e) {
            Log::error('[Checkout] Error al confirmar pedido: ' . $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine());
            $request->session()->flash('checkout_error', 'Ocurrió un error al procesar tu pedido: ' . $e->getMessage());
            return redirect()->route('checkout.pago');
        }
    }

    /**
     * Step 4: Final Factura & Receipt View
     */
    public function factura(Request $request)
    {
        $idfactura = session('ultima_factura');
        $idpedido  = session('ultimo_pedido');

        if (!$idfactura || !$idpedido) {
            return redirect()->route('catalogo.index');
        }

        $modo    = session('checkout_modo', 'individual');
        $orden   = session('checkout_orden', []);
        $entrega = session('checkout_entrega', []);

        // Limpiar sesión del checkout tras capturar los datos para la vista
        $request->session()->forget([
            'carrito',
            'checkout_orden',
            'checkout_entrega',
            'ultima_factura',
            'ultimo_pedido',
            'checkout_modo',
            'checkout_carrito_snapshot'
        ]);

        return view('checkout.step4_factura', compact('modo', 'orden', 'entrega', 'idfactura', 'idpedido'));
    }

    /**
     * Asignar automáticamente domiciliario con menos pedidos activos o crear registro de envío sin domiciliario.
     */
    private function asignarDomiciliario(int $idpedido, int $idcliente, ?int $iddireccion, ?string $telefono)
    {
        $existe = DB::table('envios')->where('idpedido', $idpedido)->first();
        if ($existe) {
            return;
        }

        // Buscar domiciliario con menos pedidos activos ('pendiente', 'en proceso')
        $dom = DB::table('domiciliario as d')
            ->leftJoin('envios as e', function ($join) {
                $join->on('e.iddomiciliario', '=', 'd.iddomiciliario')
                    ->whereIn('e.idpedido', function ($q) {
                        $q->select('idpedido')->from('pedido')->whereIn('estado', ['pendiente', 'en proceso']);
                    });
            })
            ->select('d.iddomiciliario', DB::raw('COUNT(e.idenvio) as pedidos_activos'))
            ->groupBy('d.iddomiciliario')
            ->orderBy('pedidos_activos', 'asc')
            ->first();

        $iddomiciliario = $dom ? (int)$dom->iddomiciliario : null;

        $adm = DB::table('administrador')->first();
        $idadmin = $adm ? (int)$adm->idadministrador : null;

        DB::table('envios')->insert([
            'idpedido'          => $idpedido,
            'idcliente'         => $idcliente,
            'idadministrador'   => $idadmin,
            'iddomiciliario'    => $iddomiciliario,
            'iddireccion'       => $iddireccion,
            'telefono_contacto' => $telefono,
            'estado'            => 'pendiente',
        ]);
    }

    /**
     * Descontar insumos en base a la receta del producto y registrar movimientos en inventario.
     */
    private function descontarInsumos(int $idproducto, int $cantidadVendida)
    {
        $insumos = DB::table('detalle_insumo')->where('idproducto', $idproducto)->get();

        if ($insumos->isEmpty()) {
            return;
        }

        foreach ($insumos as $insumo) {
            $cantidadDescontar = (float)$insumo->cantidad * $cantidadVendida;

            // 1. Descontar stock actual
            DB::table('insumo')
                ->where('idinsumo', $insumo->idinsumo)
                ->decrement('stock_actual', $cantidadDescontar);

            // 2. Registrar movimiento en inventario (tipo_salida = 1)
            DB::table('inventario')->insert([
                'idinsumo'     => $insumo->idinsumo,
                'tipo_entrada' => 0,
                'tipo_salida'  => 1,
                'cantidad'     => $cantidadDescontar,
                'fecha'        => now(),
            ]);

            // 3. Verificar si el stock quedó en o por debajo del stock mínimo (stock_actual <= stock_minimo)
            $critico = DB::table('insumo')
                ->where('idinsumo', $insumo->idinsumo)
                ->whereColumn('stock_actual', '<=', 'stock_minimo')
                ->first();

            if ($critico) {
                // Verificar si existe notificación en las últimas 24 horas para este insumo
                $notifReciente = DB::table('notificacion')
                    ->where('idinsumo', $critico->idinsumo)
                    ->where('tipo_stock_minimo', 1)
                    ->where('fecha', '>=', DB::raw('NOW() - INTERVAL 24 HOUR'))
                    ->first();

                if (!$notifReciente) {
                    $admins = DB::table('persona as p')
                        ->join('roles as r', 'p.idrol', '=', 'r.idrol')
                        ->where('r.nombre', 'administrador')
                        ->select('p.idpersona')
                        ->get();

                    $msg = "⚠️ Stock crítico: «{$critico->nombre}» tiene {$critico->stock_actual} unidades (mínimo: {$critico->stock_minimo}).";

                    foreach ($admins as $admin) {
                        DB::table('notificacion')->insert([
                            'idpersona'         => $admin->idpersona,
                            'idinsumo'          => $critico->idinsumo,
                            'tipo_stock_minimo' => 1,
                            'tipo_nuevo_pedido' => 0,
                            'tipo_cambio_estado'=> 0,
                            'mensaje'           => $msg,
                            'fecha'             => now(),
                        ]);
                    }
                }
            }
        }
    }
}
