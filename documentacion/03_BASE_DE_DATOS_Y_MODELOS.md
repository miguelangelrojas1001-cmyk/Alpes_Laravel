# 💾 03. Base de Datos y Modelos Eloquent

## 1. Esquema de Base de Datos (`bdalpes`)
El sistema utiliza una base de datos relacional MySQL llamada `bdalpes`. A continuación se detalla la estructura de las tablas principales y sus relaciones:

```
[roles] 1 ──── ∞ [persona]
                    │
       ┌────────────┼────────────┐
       │ (1:1)      │ (1:1)      │ (1:1)
       ▼            ▼            ▼
  [cliente]   [administrador]  [domiciliario]
       │            │            │
       │ (1:∞)      │ (1:∞)      │ (1:∞)
       ▼            ▼            ▼
   [pedido] ─────── [envios] ────┘
       │                │
       ├─── 1:1 ─── [direccion]
       │
       ├─── 1:1 ─── [factura] ─── 1:∞ ─── [detalle_factura]
       │                                         │
       └─── 1:∞ ─── [detalle_pedido]             │
                          │                      │
                          └────── ∞:1 ───────────┴─── [producto] ─── ∞:1 ─── [catalogo]
                                                          │
                                                          └─── 1:∞ ─── [detalle_insumo]
                                                                             │
                                                                             └─── ∞:1 ─── [insumo] ─── ∞:1 ─── [proveedor]
                                                                                               │
                                                                                               └─── 1:∞ ─── [inventario]
```

---

## 2. Tablas Principales

| Tabla | Clave Primaria | Propósito |
|-------|---------------|-----------|
| `persona` | `idpersona` | Información de usuarios (nombre, correo, contraseña, rol). |
| `roles` | `idrol` | Catálogo de roles (`administrador`, `cliente`, `domiciliario`). |
| `cliente` | `idcliente` | Especialización de persona como cliente (relación 1:1 con `persona`). |
| `administrador` | `idadministrador` | Especialización de persona como administrador. |
| `domiciliario` | `iddomiciliario` | Especialización de persona como repartidor. |
| `catalogo` | `idcatalogo` | Categorías de productos (Helados, Yogures, Malteadas, etc.). |
| `producto` | `idproducto` | Productos a la venta, precios, disponibilidad e imagen. |
| `pedido` | `idpedido` | Encabezado del pedido (cliente, total, estado, fecha). |
| `detalle_pedido` | `iddetalle` | Ítems del pedido con sabores seleccionados, tamaño y precio. |
| `factura` | `idfactura` | Comprobante fiscal y método de pago (`nequi`, `daviplata`, `tarjeta`). |
| `detalle_factura` | `iddetalle_factura` | Desglose fiscal de los productos cobrados. |
| `direccion` | `iddireccion` | Dirección física de entrega vinculada al envío. |
| `envios` | `idenvio` | Asignación del pedido a un repartidor y seguimiento de entrega. |
| `insumo` | `idinsumo` | Materias primas (leche, frutas, vasos, toppings, stock actual y mínimo). |
| `detalle_insumo` | `iddetalle_insumo` | Receta técnica: insumos requeridos por cada unidad de producto. |
| `inventario` | `idinventario` | Registro de movimientos de entrada y salida de insumos. |
| `proveedor` | `idproveedor` | Proveedores de materias primas e insumos. |
| `notificacion` | `idnotificacion` | Alertas del sistema para administradores (ej. stock crítico). |

---

## 3. Modelos Eloquent (`app/Models/`)

### `Persona.php`
Implementa la interfaz `Authenticatable` para la autenticación de Laravel.
```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Persona extends Authenticatable
{
    protected $table = 'persona';
    protected $primaryKey = 'idpersona';
    public $timestamps = false;

    protected $fillable = ['nombre', 'correo', 'contrasena', 'idrol'];
    protected $hidden = ['contrasena', 'remember_token'];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idrol', 'idrol');
    }

    public function getRolNombreAttribute(): string
    {
        return $this->rol ? strtolower($this->rol->nombre) : 'cliente';
    }
}
```

### `Producto.php`
Representa los artículos del catálogo y su relación con la categoría.
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'idproducto';
    public $timestamps = false;

    protected $fillable = ['nombre', 'precio', 'idcatalogo', 'imagen', 'disponible'];

    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class, 'idcatalogo', 'idcatalogo');
    }
}
```

### `Catalogo.php`
Representa las categorías agrupadoras de productos.
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $table = 'catalogo';
    protected $primaryKey = 'idcatalogo';
    public $timestamps = false;

    protected $fillable = ['nombre', 'activo'];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'idcatalogo', 'idcatalogo');
    }
}
```
