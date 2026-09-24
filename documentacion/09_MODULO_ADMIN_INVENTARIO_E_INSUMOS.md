# 📦 09. Módulo de Administración de Inventario e Insumos

## 1. `AdminInventarioController.php`

### Propósito
Controlar las materias primas (insumos) requeridas para la producción y despacho de heladería (leche, conos, frutas, salsas, empaques), registrar movimientos de entrada y salida, y generar alertas automáticas cuando los insumos alcancen niveles críticos.

---

## 2. Estructura de Datos de Inventario

* **Tabla `insumo`:**
  * `idinsumo`: Identificador único.
  * `nombre`: Nombre del insumo.
  * `categoria`: Categoría del insumo (Lácteo, Fruta, Topping, Empaque, etc.).
  * `stock_actual`: Cantidad física disponible.
  * `stock_minimo`: Umbral de alerta para reposición.
  * `unidad_medida`: g, ml, unidades, kg, litros.
  * `idproveedor`: Proveedor habitual.

* **Tabla `inventario` (Kárdex / Movimientos):**
  * `idinventario`: Identificador del movimiento.
  * `idinsumo`: Insumo afectado.
  * `tipo_entrada`: `1` si es ingreso / compra, `0` en otro caso.
  * `tipo_salida`: `1` si es egreso / consumo por venta, `0` en otro caso.
  * `cantidad`: Cantidad movida.
  * `fecha`: Timestamp del movimiento.

---

## 3. Funcionalidades Clave

### A. Registro Manual de Movimientos (`registrarMovimiento`)
El administrador puede registrar entradas (compras de insumos) o ajustes manuales de stock:
* Incrementa `stock_actual` si es entrada.
* Decrementa `stock_actual` si es salida manual o merma.
* Inserta el registro histórico en `inventario`.

### B. Descuento Automático en Ventas (`descontarInsumos`)
Cuando un cliente confirma un pedido en el checkout, el sistema:
1. Consulta la receta técnica del producto en `detalle_insumo`.
2. Multiplica la cantidad de insumo por la cantidad de productos comprados.
3. Decrementa automáticamente el stock en `insumo`.
4. Registra un movimiento con `tipo_salida = 1` en `inventario`.

### C. Alertas de Stock Crítico y Notificaciones
Si `stock_actual <= stock_minimo`, el sistema verifica si existe una notificación en las últimas 24 horas. Si no existe, genera una notificación en la tabla `notificacion` para todos los usuarios con rol de administrador:
```php
if ($critico && !$notifReciente) {
    $msg = "⚠️ Stock crítico: «{$critico->nombre}» tiene {$critico->stock_actual} unidades (mínimo: {$critico->stock_minimo}).";
    // Inserción de notificación para administradores
}
```
