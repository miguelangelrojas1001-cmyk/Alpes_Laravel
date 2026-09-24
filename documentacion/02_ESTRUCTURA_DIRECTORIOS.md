# 📁 02. Estructura de Directorios del Proyecto

Este documento describe el propósito y contenido de cada una de las carpetas principales del proyecto **Alpes_Laravel**.

---

## 🌳 Árbol Principal de Directorios

```
Alpes_Laravel/
├── app/                      # Lógica central de la aplicación (PHP)
│   ├── Http/
│   │   ├── Controllers/      # Controladores de peticiones web y API
│   │   │   ├── Auth/         # Controladores de Login y Registro
│   │   │   └── ...           # Controladores de Admin, Catálogo, Checkout, etc.
│   │   └── Middleware/       # Filtros HTTP personalizados (CheckRole)
│   ├── Models/               # Modelos Eloquent de la base de datos
│   └── Providers/            # Service Providers de inicialización (AppServiceProvider)
├── bootstrap/                # Inicialización del framework (app.php, providers.php)
├── config/                   # Archivos de configuración general (app, database, auth, etc.)
├── database/                 # Migraciones, factories y seeders de la base de datos
├── documentacion/            # Documentación técnica completa del proyecto
├── public/                   # Raíz web accesible públicamente
│   ├── img/                  # Imágenes del sistema, logos y fotos de productos
│   ├── favicon.ico           # Icono de pestaña
│   ├── index.php             # Punto de entrada HTTP único
│   └── robots.txt            # Reglas para motores de búsqueda
├── resources/                # Recursos sin compilar y vistas
│   ├── css/                  # Hojas de estilo
│   ├── js/                   # Scripts JavaScript
│   └── views/                # Plantillas Blade (.blade.php)
│       ├── auth/             # Vistas de autenticación (Login, Registro)
│       ├── carrito/          # Vista del carrito de compras
│       ├── catalogo/         # Catálogo interactivo de productos
│       ├── checkout/         # Pasos del checkout (Etapa 5)
│       ├── dashboard/        # Paneles divididos por rol (Admin, Cliente, Domiciliario)
│       └── layouts/          # Layouts base (app.blade.php)
├── routes/                   # Definición de rutas del sistema
│   ├── console.php           # Comandos de consola Artisan
│   └── web.php               # Rutas web del sistema
├── storage/                  # Archivos temporales, logs, sesiones y cachés
│   ├── app/                  # Archivos privados
│   ├── framework/            # Cachés compiladas de vistas, rutas y sesiones
│   └── logs/                 # Registro de logs del sistema (laravel.log)
├── tests/                    # Pruebas automatizadas (Unitarias y Funcionales)
├── .env                      # Variables de entorno locales (Base de datos, Timezone, etc.)
├── composer.json             # Dependencias y paquetes PHP
└── artisan                   # Ejecutable CLI de Laravel
```

---

## 🔍 Detalle de Carpetas Clave

### 1. `app/Http/Controllers/`
Contiene la lógica de negocio dividida modularmente:
* `Auth/LoginController.php`: Gestión de sesiones, login seguro y registro de clientes.
* `CatalogoController.php`: Renderizado del catálogo con filtrado por categoría.
* `CarritoController.php`: Manipulación del carrito en sesión (añadir, actualizar cantidad, eliminar, vaciar).
* `CheckoutController.php`: Procesamiento de los 4 pasos del checkout y generación de facturas.
* `Admin*.php`: Conjunto de controladores dedicados a la gestión administrativa (Productos, Inventario, Reportes, Usuarios, Proveedores, Ventas).
* `ClienteDashboardController.php`: Historial de pedidos y facturas del cliente.
* `DomiciliarioDashboardController.php`: Gestión de entregas asignadas al repartidor.
* `ChatbotController.php`: Respuestas del asistente virtual interactivo.

### 2. `resources/views/`
Estructurada en submódulos para un mantenimiento ágil:
* `layouts/app.blade.php`: Plantilla maestra con sidebar, barra superior de búsqueda, toggle de modo oscuro/claro y footer.
* `checkout/step_layout.blade.php`: Layout de pasos con barra de progreso (Stepper visual).
* `dashboard/admin/`: Vistas de inventario, ventas, reportes, usuarios y productos.

### 3. `public/`
Es el único directorio que debe ser expuesto por el servidor web hacia Internet. Las imágenes subidas por el panel de productos se almacenan en `public/img/`.
