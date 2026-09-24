# 🛣️ 04. Rutas y Enrutamiento (`routes/web.php`)

## 1. Visión General del Archivo de Rutas
El archivo `routes/web.php` define todos los puntos de entrada HTTP de la aplicación, organizados mediante grupos, middlewares de autenticación y middlewares de control de roles.

---

## 2. Mapa Completo de Rutas

### 🟢 Rutas Públicas (Acceso Universal)
| Método | Ruta | Nombre de Ruta | Controlador y Acción | Propósito |
|--------|------|----------------|----------------------|-----------|
| `GET` | `/` | `home` | `CatalogoController@index` | Catálogo de productos en portada |
| `GET` | `/catalogo` | `catalogo.index` | `CatalogoController@index` | Catálogo filtrado por categoría |
| `GET` | `/login` | `login` | `Auth\LoginController@showLoginForm` | Formulario de inicio de sesión |
| `POST` | `/login` | `login.post` | `Auth\LoginController@login` | Procesar inicio de sesión |
| `GET` | `/register` | `register` | `Auth\LoginController@showRegisterForm` | Formulario de registro de cliente |
| `POST` | `/register` | `register.post` | `Auth\LoginController@register` | Procesar nuevo registro |
| `POST` | `/logout` | `logout` | `Auth\LoginController@logout` | Cerrar sesión |

### 🛒 Rutas del Carrito de Compras (En Sesión)
| Método | Ruta | Nombre de Ruta | Controlador y Acción | Propósito |
|--------|------|----------------|----------------------|-----------|
| `GET` | `/carrito` | `carrito.index` | `CarritoController@index` | Ver productos del carrito |
| `POST` | `/carrito/agregar` | `carrito.agregar` | `CarritoController@agregar` | Añadir producto directo |
| `POST` | `/carrito/actualizar` | `carrito.actualizar` | `CarritoController@actualizar` | Cambiar cantidad de un ítem |
| `DELETE` | `/carrito/eliminar/{id}` | `carrito.eliminar` | `CarritoController@eliminar` | Quitar un producto |
| `POST` | `/carrito/vaciar` | `carrito.vaciar` | `CarritoController@vaciar` | Vaciar todo el carrito |

### 🛍️ Rutas de Checkout (Etapa 5)
| Método | Ruta | Nombre de Ruta | Middleware | Controlador y Acción |
|--------|------|----------------|------------|----------------------|
| `GET` | `/checkout/carrito` | `checkout.iniciarCarrito` | Ninguno | `CheckoutController@iniciarCarrito` |
| `GET` | `/checkout/opciones` | `checkout.opciones` | Ninguno | `CheckoutController@opciones` (Paso 1) |
| `POST` | `/checkout/opciones` | `checkout.guardarOpciones` | Ninguno | `CheckoutController@guardarOpciones` |
| `GET` | `/checkout/entrega` | `checkout.entrega` | `auth` | `CheckoutController@entrega` (Paso 2) |
| `POST` | `/checkout/entrega` | `checkout.guardarEntrega` | `auth` | `CheckoutController@guardarEntrega` |
| `GET` | `/checkout/pago` | `checkout.pago` | `auth` | `CheckoutController@pago` (Paso 3) |
| `POST` | `/checkout/confirmar` | `checkout.confirmar` | `auth` | `CheckoutController@confirmar` (Transacción) |
| `GET` | `/checkout/factura` | `checkout.factura` | `auth` | `CheckoutController@factura` (Paso 4) |

### 🔒 Rutas Protegidas por Rol

#### 🛡️ Rol: Administrador (`middleware: ['auth', 'role:administrador']`)
* `/admin/dashboard` ➔ Redirección hacia `/admin/productos`.
* `/admin/productos` ➔ `AdminProductosController@index` / `@guardar` / `@eliminar`.
* `/admin/inventario` ➔ `AdminInventarioController@index` / `@guardarInsumo` / `@registrarMovimiento` / `@eliminarInsumo`.
* `/admin/ventas` ➔ `AdminVentasController@index` / `@actualizarEstado`.
* `/admin/proveedores` ➔ `AdminProveedoresController@index` / `@guardar` / `@eliminar`.
* `/admin/categorias` ➔ `AdminCategoriasController@index` / `@guardar` / `@toggle` / `@eliminar`.
* `/admin/usuarios` ➔ `AdminUsuariosController@index` / `@cambiarRol` / `@crear` / `@eliminar`.
* `/admin/reportes` ➔ `AdminReportesController@index`.

#### 👤 Rol: Cliente (`middleware: ['auth', 'role:cliente']`)
* `/cliente/dashboard` ➔ `ClienteDashboardController@index` (Historial de pedidos y facturas).

#### 🛵 Rol: Domiciliario (`middleware: ['auth', 'role:domiciliario']`)
* `/domiciliario/dashboard` ➔ `DomiciliarioDashboardController@index` (Envíos asignados).
* `/domiciliario/pedidos/estado` ➔ `DomiciliarioDashboardController@actualizarEstado` (Marcar en camino / Entregado).
