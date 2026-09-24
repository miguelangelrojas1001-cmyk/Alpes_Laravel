# 🛡️ 19. Seguridad, Validaciones e Integridad Transaccional

## 1. Protección contra Vulnerabilidades Comunes

### A. Prevención de Cross-Site Request Forgery (CSRF)
Todos los formularios HTML que realizan operaciones mutativas (`POST`, `PUT`, `DELETE`) incluyen la directiva `@csrf` de Blade:
```html
<form method="POST" action="{{ route('checkout.confirmar') }}">
    @csrf
    <!-- campos -->
</form>
```

### B. Prevención de Inyección SQL
Todas las consultas utilizan **Query Builder** o **Eloquent ORM** con enlace de parámetros preparados (`PDO Parameter Binding`), evitando cualquier posibilidad de concatenación insegura.

### C. Prevención de Cross-Site Scripting (XSS)
El motor de plantillas Blade escapa por defecto cualquier salida dinámica utilizando `{{ $variable }}` (equivalente a `htmlspecialchars()`). En los casos donde se procesan strings manuales, se aplica la función `e()`.

---

## 2. Integridad Transaccional con `DB::transaction`

En operaciones críticas como la confirmación del Checkout (`CheckoutController@confirmar`), se utiliza una transacción atómica para garantizar que **o todo se guarda exitosamente, o nada se altera**:

```php
DB::transaction(function () use (...) {
    // 1. Crear o recuperar cliente
    // 2. Guardar dirección de entrega
    // 3. Crear pedido principal
    // 4. Crear factura asociada
    // 5. Insertar ítems en detalle_pedido y detalle_factura
    // 6. Descontar materias primas del inventario
});
```

Si ocurre cualquier excepción durante el proceso (ejemplo: caída de base de datos o error de validación), Laravel realiza un `ROLLBACK` automático, impidiendo inconsistencias contables o cobros sin factura.

---

## 3. Validación Estricta de Formularios
Las peticiones validan tipos de datos, longitudes y reglas de negocio antes de procesarse:
```php
$request->validate([
    'destinatario' => 'required|string|max:255',
    'direccion'    => 'required|string|max:255',
    'barrio'       => 'required|string|max:255',
    'telefono'     => 'required|string|max:50',
]);
```
