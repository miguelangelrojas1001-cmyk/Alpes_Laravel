# 🍨 08. Módulo de Administración de Productos y Categorías

## 1. `AdminProductosController.php`

### Propósito
Permite al administrador gestionar el catálogo comercial de helados, yogures, malteadas y postres, incluyendo sus precios, imágenes, disponibilidad y recetas asociadas (`detalle_insumo`).

### Métodos Principales

#### `index(Request $request)`
* Obtiene todos los productos con su categoría (`catalogo`), ordenados alfabéticamente.
* Carga todos los catálogos e insumos disponibles para el formulario de creación/edición.
* Consulta las recetas técnicas de cada producto (`detalle_insumo`) para mostrar los insumos necesarios para su preparación.

#### `guardar(Request $request)`
* Permite crear un nuevo producto o actualizar uno existente (`idproducto`).
* **Subida de Imagen:** Si se adjunta un archivo en el campo `imagen`, se genera un nombre único con timestamp (`time() . '_' . $file->getClientOriginalName()`) y se guarda en el directorio público `public/img/`.
* **Asignación de Insumos / Receta:** Procesa los arrays `insumos[]` y `cantidades[]` para insertar o actualizar los registros en `detalle_insumo`.

#### `eliminar(Request $request, $id)`
* Valida que el producto no tenga pedidos asociados antes de eliminarlo.
* Si el producto tiene historial de ventas, desactiva su disponibilidad (`disponible = 0`) para preservar la integridad referencial.

---

## 2. `AdminCategoriasController.php`

### Propósito
Controla las agrupaciones o categorías del menú (ejemplo: Helados de Crema, Yogur Griego, Malteadas, Conos y Canastas).

### Métodos Principales

#### `index()`
Lista todas las categorías con el conteo de productos asociados a cada una.

#### `guardar(Request $request)`
Crea una nueva categoría o actualiza el nombre de una existente.

#### `toggle(Request $request, $id)`
Activa o desactiva la visibilidad de una categoría completa en el catálogo público (`activo = 1 / 0`).

#### `eliminar(Request $request, $id)`
Elimina una categoría únicamente si no contiene productos registrados.
