# 🛒 10. Módulo de Administración de Ventas y Pedidos

## 1. `AdminVentasController.php`

### Propósito
Proporcionar al administrador una vista en tiempo real de todas las transacciones y pedidos realizados en el sistema, permitiendo cambiar estados y supervisar la asignación de domiciliarios.

---

## 2. Flujo de Estados del Pedido

```
  [ Pedido Creado (Cliente) ]
              │
              ▼
        [ 'pendiente' ]  ◄── Pedido recibido y en cola
              │
              ▼
       [ 'en proceso' ]  ◄── En preparación o en camino con domiciliario
              │
       ┌──────┴──────┐
       ▼             ▼
 [ 'completado' ] [ 'cancelado' ]
```

---

## 3. Consultas y Carga de Información (`index`)
* Consulta la tabla `pedido` con joins a `cliente`, `persona`, `envios`, `domiciliario` y `factura`.
* Obtiene el desglose de productos asociados a cada pedido mediante `detalle_pedido`.
* Calcula métricas clave en la cabecera:
  * Total de pedidos recibidos.
  * Pedidos pendientes de atención.
  * Pedidos completados con éxito.
  * Ingresos totales facturados.

---

## 4. Cambio de Estado de Pedidos (`actualizarEstado`)

Permite al administrador modificar manualmente el estado de cualquier pedido:
```php
public function actualizarEstado(Request $request)
{
    $request->validate([
        'idpedido' => 'required|integer',
        'estado'   => 'required|in:pendiente,en proceso,completado,cancelado',
    ]);

    $idpedido = (int)$request->input('idpedido');
    $estado   = $request->input('estado');

    DB::table('pedido')
        ->where('idpedido', $idpedido)
        ->update(['estado' => $estado]);

    // Sincronizar estado del envío si corresponde
    DB::table('envios')
        ->where('idpedido', $idpedido)
        ->update(['estado' => $estado]);

    return redirect()->route('admin.ventas.index')->with('success', "Pedido #{$idpedido} actualizado a estado: {$estado}");
}
```
