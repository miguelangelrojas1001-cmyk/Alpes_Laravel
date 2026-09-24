# 🏢 11. Módulo de Administración de Proveedores

## 1. `AdminProveedoresController.php`

### Propósito
Gestionar las empresas y distribuidores que suministran las materias primas (lácteos, empaques, frutas, esencias) a la heladería.

---

## 2. Estructura de la Tabla `proveedor`
* `idproveedor`: Identificador único.
* `nombre`: Razón social o nombre comercial del proveedor.
* `telefono`: Teléfono de contacto / WhatsApp comercial.
* `correo`: Correo electrónico para órdenes de compra.
* `direccion`: Dirección física de la sede o bodega.

---

## 3. Operaciones CRUD

### A. Listado (`index`)
* Consulta todos los proveedores registrados junto con el conteo de insumos suministrados por cada uno.

### B. Guardar / Editar (`guardar`)
* Valida campos obligatorios: `nombre`, `telefono`, `correo` y `direccion`.
* Si se envía un `idproveedor`, actualiza los datos; si no, inserta un nuevo registro en la tabla `proveedor`.

### C. Eliminación Segura (`eliminar`)
* Verifica que el proveedor no tenga insumos vinculados en la tabla `insumo`.
* Si existen insumos asociados, previene la eliminación e informa al usuario con un mensaje de alerta.
