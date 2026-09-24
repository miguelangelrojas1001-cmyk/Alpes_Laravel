# 📚 Índice General de Documentación - Alpes Laravel

Bienvenido a la documentación técnica oficial del proyecto **Los Alpes — Heladería** desarrollado sobre el framework **Laravel 11**.

Esta colección de documentos detalla de manera exhaustiva la arquitectura, estructura de archivos, módulos del sistema, base de datos, flujos de negocio y mejores prácticas implementadas en el código fuente.

---

## 🗂️ Contenido de la Documentación

| # | Documento | Descripción |
|---|-----------|-------------|
| **01** | [`01_ARQUITECTURA_GENERAL.md`](./01_ARQUITECTURA_GENERAL.md) | Visión global del sistema, patrón MVC, ciclo de vida de peticiones y flujo de datos. |
| **02** | [`02_ESTRUCTURA_DIRECTORIOS.md`](./02_ESTRUCTURA_DIRECTORIOS.md) | Explicación detallada carpeta por carpeta de todo el árbol del proyecto. |
| **03** | [`03_BASE_DE_DATOS_Y_MODELOS.md`](./03_BASE_DE_DATOS_Y_MODELOS.md) | Diagrama relacional, tablas de `bdalpes`, tipos de datos y modelos Eloquent en `app/Models/`. |
| **04** | [`04_RUTAS_Y_ROUTER.md`](./04_RUTAS_Y_ROUTER.md) | Mapeo de rutas en `routes/web.php`, nombres, métodos HTTP y middlewares aplicados. |
| **05** | [`05_AUTENTICACION_Y_ROLES.md`](./05_AUTENTICACION_Y_ROLES.md) | Sistema de autenticación personalizada, hashing con Bcrypt, control de roles (`CheckRole`). |
| **06** | [`06_CONTROLADORES_CATALOGO_Y_CARRITO.md`](./06_CONTROLADORES_CATALOGO_Y_CARRITO.md) | Lógica del catálogo público y carrito de compras en sesión (`CatalogoController`, `CarritoController`). |
| **07** | [`07_FLUJO_CHECKOUT_Y_PAGO.md`](./07_FLUJO_CHECKOUT_Y_PAGO.md) | Flujo de compra en 4 pasos (Etapa 5): personalización, entrega, medios de pago y facturación. |
| **08** | [`08_MODULO_ADMIN_PRODUCTOS_Y_CATEGORIAS.md`](./08_MODULO_ADMIN_PRODUCTOS_Y_CATEGORIAS.md) | Gestión de productos, carga de imágenes y administración de categorías de catálogo. |
| **09** | [`09_MODULO_ADMIN_INVENTARIO_E_INSUMOS.md`](./09_MODULO_ADMIN_INVENTARIO_E_INSUMOS.md) | Control de stock, recetas por producto (`detalle_insumo`) y alertas de stock crítico. |
| **10** | [`10_MODULO_ADMIN_VENTAS_Y_PEDIDOS.md`](./10_MODULO_ADMIN_VENTAS_Y_PEDIDOS.md) | Panel de ventas global, control de estados de pedidos y seguimiento de envíos. |
| **11** | [`11_MODULO_ADMIN_PROVEEDORES.md`](./11_MODULO_ADMIN_PROVEEDORES.md) | Gestión de proveedores de insumos (CRUD completo y relaciones). |
| **12** | [`12_MODULO_ADMIN_USUARIOS.md`](./12_MODULO_ADMIN_USUARIOS.md) | Administración de cuentas de usuario, creación y cambio dinámico de roles. |
| **13** | [`13_MODULO_ADMIN_REPORTES_Y_METRICAS.md`](./13_MODULO_ADMIN_REPORTES_Y_METRICAS.md) | Métricas contables, balance de ingresos, productos más vendidos y filtros por fecha. |
| **14** | [`14_PANEL_CLIENTE.md`](./14_PANEL_CLIENTE.md) | Dashboard del cliente, seguimiento de pedidos propios y visualización de comprobantes. |
| **15** | [`15_PANEL_DOMICILIARIO.md`](./15_PANEL_DOMICILIARIO.md) | Panel para repartidores, gestión de entregas en camino/completadas y métricas del día. |
| **16** | [`16_CHATBOT_ASISTENTE_VIRTUAL.md`](./16_CHATBOT_ASISTENTE_VIRTUAL.md) | Asistente conversacional inteligente con recomendaciones de productos y soporte en vivo. |
| **17** | [`17_SISTEMA_DE_VISTAS_Y_BLADE.md`](./17_SISTEMA_DE_VISTAS_Y_BLADE.md) | Layouts base, componentes Blade, diseño responsivo y motor de temas (Dark / Light). |
| **18** | [`18_CONFIGURACIONES_Y_ENTORNO.md`](./18_CONFIGURACIONES_Y_ENTORNO.md) | Variables de entorno `.env`, zonas horarias (`America/Bogota`) y ajustes de base de datos. |
| **19** | [`19_SEGURIDAD_Y_TRANSACCIONES.md`](./19_SEGURIDAD_Y_TRANSACCIONES.md) | Protección CSRF, inyección SQL, integridad transaccional con `DB::transaction` y validaciones. |
| **20** | [`20_GUIA_DESPLIEGUE_Y_MANTENIMIENTO.md`](./20_GUIA_DESPLIEGUE_Y_MANTENIMIENTO.md) | Comandos Artisan, limpieza de cachés, buenas prácticas y guía de despliegue en producción. |

---

## 🚀 Convenciones del Código

* **Estilo de Código:** PSR-12 para backend en PHP.
* **Manejo de Base de Datos:** Nombres de tablas en singular (`persona`, `pedido`, `factura`, `insumo`).
* **Seguridad:** Todas las rutas mutativas requieren token CSRF `@csrf`.
* **Diseño:** CSS moderno con variables CSS personalizadas y diseño adaptativo sin dependencias pesadas.
