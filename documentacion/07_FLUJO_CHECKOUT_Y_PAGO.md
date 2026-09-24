# 💳 07. Flujo de Checkout y Pagos (Etapa 5)

## 1. Visión General del Flujo
El proceso de compra en **Alpes_Laravel** está diseñado como un flujo asistido en 4 pasos progresivos, soportando dos modalidades:
1. **Modo Individual ("Pedir ahora"):** Personalización y compra rápida de un solo producto.
2. **Modo Carrito:** Compra simultánea de múltiples productos guardados en el carrito de compras.

---

## 2. Diagrama de Pasos del Checkout

```
[ Catálogo / Producto ]  ────►  [ Paso 1: Opciones & Toppings ]
          │                                  │
          ▼                                  ▼
[ Carrito de Compras ]   ────►  [ Paso 2: Datos de Entrega ] (Requiere Auth)
                                             │
                                             ▼
                                [ Paso 3: Medio de Pago ] (Nequi, Daviplata, Tarjeta)
                                             │
                                             ▼ [ DB::transaction ]
                                             ├─ Inserción pedido & factura
                                             ├─ Descuento automático de insumos
                                             ├─ Asignación de domiciliario
                                             ▼
                                [ Paso 4: Factura & Resumen ]
```

---

## 3. Detalle de los Pasos

### Paso 1: Personalización de Producto (`opciones` / `guardarOpciones`)
* Permite seleccionar sabores, tamaños, toppings de frutas, dulces, salsas y adicionales.
* Calcula dinámicamente los recargos por toppings premium.
* Si la acción es `'carrito'`, añade el producto personalizado al carrito y redirige al catálogo.
* Si la acción es `'pedir'`, guarda la orden en sesión y avanza al Paso 2.

### Paso 2: Datos de Entrega (`entrega` / `guardarEntrega`)
* Formulario protegido con `auth`.
* Captura el nombre del destinatario, dirección, barrio, ciudad (por defecto "Campoalegre"), teléfono de contacto e instrucciones especiales.

### Paso 3: Método de Pago (`pago` / `confirmar`)
* Presenta el desglose final: Subtotal + Costo de envío a domicilio ($2.000 COP) = Total a pagar.
* Permite seleccionar entre:
  * 📱 **Nequi**
  * 📱 **Daviplata**
  * 💳 **Tarjeta de crédito / débito**
* Al enviar el formulario, se ejecuta la transacción atómica de base de datos.

### Transacción Atómica en Base de Datos (`DB::transaction`)
1. **Cliente:** Busca o crea el registro en `cliente` correspondiente al usuario autenticado.
2. **Dirección:** Inserta la dirección en la tabla `direccion`.
3. **Pedido:** Crea el registro en la tabla `pedido` con estado `'pendiente'` y fecha actual (`now()`).
4. **Factura:** Inserta el comprobante en `factura` con el método de pago seleccionado.
5. **Detalles:** Inserta registros en `detalle_pedido` y `detalle_factura` por cada producto.
6. **Descuento de Insumos:** Ejecuta `descontarInsumos($idproducto, $cantidad)`, reduciendo `stock_actual` en `insumo` y registrando el movimiento de salida en `inventario`.
7. **Asignación de Repartidor:** Asigna el pedido al domiciliario con menor carga de pedidos activos.

### Paso 4: Factura Final (`factura`)
* Muestra el comprobante detallado con número de pedido, número de factura, fecha, hora local formateada, desglose de ítems, dirección y costo total.
* Limpia los datos temporales del carrito y sesión.
