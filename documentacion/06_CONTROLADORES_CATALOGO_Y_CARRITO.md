# 🍦 06. Controladores de Catálogo y Carrito

## 1. `CatalogoController.php`

### Propósito
Gestiona la visualización pública de las categorías y productos disponibles.

### Lógica Principal
1. Carga todas las categorías ordenadas alfabéticamente desde el modelo `Catalogo`.
2. Determina la categoría activa (por parámetro `idcatalogo` o la primera disponible).
3. Consulta los productos asociados a dicha categoría con `disponible = 1`.
4. Renderiza la vista `catalogo.index`.

```php
public function index(Request $request)
{
    $categorias = Catalogo::orderBy('nombre')->get();
    $idActivo = (int)$request->get('idcatalogo', 0);

    if (!$idActivo && $categorias->isNotEmpty()) {
        $idActivo = (int)$categorias->first()->idcatalogo;
    }

    $catActiva = $categorias->firstWhere('idcatalogo', $idActivo);
    $productos = $catActiva 
        ? Producto::where('idcatalogo', $idActivo)->where('disponible', 1)->get() 
        : collect();

    return view('catalogo.index', compact('categorias', 'catActiva', 'productos', 'idActivo'));
}
```

---

## 2. `CarritoController.php`

### Propósito
Permite al cliente acumular varios productos en su sesión antes de proceder al pago.

### Estructura de un Ítem en Sesión
Cada producto en el carrito se almacena bajo una clave única en la sesión `carrito`:
* **Clave única:** `idproducto` (para productos simples) o `idproducto_hash(sabores+tamano+obs)` (para productos personalizados con toppings).

### Métodos del Controlador

#### `index(Request $request)`
Calcula el total sumando `precio * cantidad` de todos los ítems y renderiza `carrito.index`.

#### `agregar(Request $request)`
Añade un producto estándar directamente desde el catálogo. Si el producto ya existía en el carrito, incrementa su cantidad.

#### `actualizar(Request $request)`
Modifica la cantidad seleccionada para un ítem específico asegurando que sea mayor o igual a 1 y hasta 20 unidades.

#### `eliminar(Request $request, $id)`
Remueve un ítem específico del carrito mediante su clave decodificada (`urldecode($id)`).

#### `vaciar(Request $request)`
Limpia completamente el array `carrito` de la sesión del usuario.
