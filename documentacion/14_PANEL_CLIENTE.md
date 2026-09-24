# 👤 14. Panel del Cliente (`ClienteDashboardController.php`)

## 1. Propósito
Ofrecer al cliente un espacio personalizado para hacer seguimiento de sus órdenes en curso, consultar el histórico de compras y visualizar las facturas electrónicas de cada pedido.

---

## 2. Lógica del Controlador (`ClienteDashboardController.php`)

### Seguridad y Aislamiento de Datos
El controlador **siempre deriva la identidad del cliente a partir de `Auth::id()`**, garantizando que ningún usuario pueda consultar pedidos ajenos:

```php
public function index(Request $request)
{
    $idpersona = Auth::id();
    $cliente = Cliente::where('idpersona', $idpersona)->first();

    $pedidos = [];
    $detallesPorPedido = [];
    $facturasPorPedido = [];
    $enviosPorPedido = [];

    if ($cliente) {
        $pedidos = DB::table('pedido')
            ->where('idcliente', $cliente->idcliente)
            ->orderBy('fecha', 'desc')
            ->get();
        
        // Carga de detalles, facturas y datos de envío de cada pedido
    }

    return view('dashboard.cliente.pedidos', compact(
        'pedidos', 'detallesPorPedido', 'facturasPorPedido', 'enviosPorPedido'
    ));
}
```

---

## 3. Características de la Vista del Cliente

* **Tarjetas Desplegables de Pedido (`_card.blade.php`):** Muestran el número de pedido, fecha y hora en formato 12h (`24/09/2026 02:30 PM`), estado con badge de color interactivo y total pagado.
* **Desglose de Ítems:** Detalla cada producto comprado, cantidad, sabores o toppings seleccionados, tamaño y precio unitario.
* **Detalle del Domicilio:** Muestra la dirección de entrega y el tiempo estimado.
* **Modal de Factura:** Permite visualizar o imprimir el comprobante detallado con método de pago (Nequi, Daviplata, Tarjeta).
