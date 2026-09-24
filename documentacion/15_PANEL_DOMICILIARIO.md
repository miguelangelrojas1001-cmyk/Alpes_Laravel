# 🛵 15. Panel del Domiciliario (`DomiciliarioDashboardController.php`)

## 1. Propósito
Proveer al equipo de domiciliarios y repartidores una interfaz ágil y adaptada a dispositivos móviles para gestionar las entregas asignadas, consultar rutas, teléfonos de contacto y actualizar el estado de los pedidos en tiempo real.

---

## 2. Gestión de Entregas y Estados

### A. Pedidos Asignados Activos
Pedidos asignados al repartidor autenticado con estado `'pendiente'` o `'en proceso'`.
* Muestra el nombre del cliente, teléfono de contacto directo, dirección completa y barrio.
* Incluye observaciones del cliente (ej. "timbre dañado, llamar al llegar").

### B. Transiciones de Estado Permitidas
El repartidor dispone de botones de acción rápida para avanzar el flujo de entrega:
1. **Marcar en camino:** Cambia el estado del pedido a `'en proceso'`.
2. **Marcar entregado:** Cambia el estado del pedido a `'completado'`.

```php
public function actualizarEstado(Request $request)
{
    $idpersona = Auth::id();
    $domiciliario = Domiciliario::where('idpersona', $idpersona)->first();

    // Verificación de seguridad: el pedido debe estar asignado a este domiciliario
    $envioValido = DB::table('envios')
        ->where('idpedido', $idpedido)
        ->where('iddomiciliario', $domiciliario->iddomiciliario)
        ->exists();

    if (!$envioValido) {
        abort(403, 'No tienes permiso para actualizar este pedido.');
    }

    $nuevoEstado = $request->has('marcar_entregado') ? 'completado' : 'en proceso';

    DB::table('pedido')
        ->where('idpedido', $idpedido)
        ->update(['estado' => $nuevoEstado]);

    return redirect()->route('domiciliario.dashboard');
}
```

---

## 3. Histórico y Métricas del Día
* **Entregas de Hoy:** Conteo en tiempo real de los pedidos completados en la fecha actual (`$hoy = date('Y-m-d')`).
* **Historial Reciente:** Últimas 50 entregas realizadas por el repartidor.
