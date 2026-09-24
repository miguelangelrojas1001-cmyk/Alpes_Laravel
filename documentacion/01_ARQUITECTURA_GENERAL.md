# 🏗️ 01. Arquitectura General del Sistema

## 1. Introducción
El proyecto **Alpes_Laravel** es una aplicación web empresarial orientada al comercio electrónico y gestión integral de la heladería **Los Alpes**. Está construida con el framework **Laravel 11**, adoptando el patrón arquitectónico **Modelo-Vista-Controlador (MVC)** junto con capas de servicios transaccionales y middleware de control de acceso.

---

## 2. Diagrama de Arquitectura de Alto Nivel

```
[ Cliente Web / Navegador ]
            │
            ▼
    [ Servidor Web / Router (routes/web.php) ]
            │
            ▼
    [ Middlewares (Auth, CheckRole) ]
            │
            ▼
    [ Controladores (app/Http/Controllers) ]
     ├── Autenticación (LoginController)
     ├── Catálogo & Carrito (CatalogoController, CarritoController)
     ├── Checkout & Pagos (CheckoutController)
     ├── Paneles de Usuario (ClienteDashboard, DomiciliarioDashboard)
     ├── Administración (AdminProductos, AdminInventario, AdminVentas, etc.)
     └── Asistente Virtual (ChatbotController)
            │
    ┌───────┴───────────────────────┐
    ▼                               ▼
[ Modelos Eloquent & Query Builder ]  [ Vistas Blade (resources/views) ]
    │                               │
    ▼                               ▼
[ Base de Datos MySQL (bdalpes) ]     [ HTML5 / CSS Moderno / Interfaz ]
```

---

## 3. Ciclo de Vida de una Petición (Request Lifecycle)

1. **Entrada de la Petición:** El servidor web recibe la solicitud HTTP y la dirige a `public/index.php`.
2. **Arranque del Framework:** Se inicializa la aplicación mediante `bootstrap/app.php` y se registran los proveedores de servicio (`AppServiceProvider`).
3. **Enrutamiento:** `routes/web.php` analiza la URL y el método HTTP (GET, POST, DELETE).
4. **Filtros de Middleware:** Se aplican filtros de sesión, autenticación (`auth`) y validación de roles (`role:administrador`, `role:cliente`, `role:domiciliario`).
5. **Controlador:** Se invoca el método correspondiente de la clase controladora encargada de la lógica de negocio.
6. **Persistencia de Datos:** El controlador interactúa con la base de datos `bdalpes` a través de **Modelos Eloquent** (`Persona`, `Producto`, `Catalogo`) o **Query Builder transaccional** (`DB::transaction`).
7. **Renderizado de la Respuesta:** Se compila la vista Blade (`.blade.php`) retornando el HTML final con el layout y los datos inyectados.

---

## 4. Principios y Patrones Utilizados

* **Separación de Responsabilidades (SoC):** Cada capa (enrutamiento, lógica, presentación y datos) está desacoplada.
* **Transacciones ACID:** Los procesos críticos como el Checkout aseguran que la inserción de pedidos, facturas y descuento de inventario se realicen de forma atómica.
* **Control de Acceso Basado en Roles (RBAC):** Middleware dinámico para restringir y redirigir usuarios según sus permisos.
* **Diseño Reactivo y Responsivo:** CSS puro con variables y soporte nativo para modo oscuro (`data-theme="dark"`).
