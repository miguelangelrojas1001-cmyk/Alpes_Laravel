# 🎨 17. Sistema de Vistas, Plantillas Blade y Temas

## 1. Motor de Plantillas Blade
El proyecto utiliza el motor de plantillas **Blade** de Laravel, permitiendo una estructuración modular con herencia de layouts mediante `@extends`, `@section` y `@yield`.

---

## 2. Layouts Principales

### A. `resources/views/layouts/app.blade.php`
Es la plantilla base utilizada en toda la aplicación. Incluye:
* **Favicon Oficial:** Vinculado a `public/img/logo.png`.
* **Sidebar Dinámico:** Muestra opciones adaptadas según el rol del usuario autenticado (`administrador`, `domiciliario`, `cliente` o visitante).
* **Contador de Carrito en Vivo:** Badge con la cantidad total de artículos agregados en sesión.
* **Barra Superior (Topbar):** Barra de búsqueda de productos y toggle de cambio de tema.
* **Footer de Información:** Enlaces rápidos y derechos reservados.

### B. `resources/views/checkout/step_layout.blade.php`
Layout especializado para el flujo de Checkout (Etapa 5):
* **Stepper Visual:** Indicador circular con 4 pasos (Personaliza ➔ Entrega ➔ Pago ➔ Confirmación), mostrando qué pasos están completados (`done`), activos (`active`) o pendientes (`todo`).
* **Botón de Retorno:** Navegación hacia atrás manteniendo el estado de sesión.

---

## 3. Sistema de Temas (Dark Mode / Light Mode)

La aplicación implementa un sistema de diseño con **CSS Variables** puro, permitiendo alternar instantáneamente entre modo oscuro (por defecto) y modo claro:

```css
/* Tema Oscuro (Default) */
:root {
    --main-bg:       #111827;
    --sb-bg:         #1a1a2e;
    --card-bg:       #1f2937;
    --card-border:   rgba(255,255,255,.07);
    --heading-color: #f1f5f9;
    --body-color:    #cbd5e1;
    --blue:          #2563EB;
}

/* Tema Claro */
[data-theme="light"] {
    --main-bg:       #F8FAFC;
    --sb-bg:         #FFFFFF;
    --card-bg:       #FFFFFF;
    --card-border:   #E2E8F0;
    --heading-color: #0F172A;
    --body-color:    #475569;
}
```

El tema preferido por el usuario se guarda en el almacenamiento local del navegador (`localStorage`) para persistir su elección entre recargas.
