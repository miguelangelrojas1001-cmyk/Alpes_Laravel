# 👥 12. Módulo de Administración de Usuarios y Roles

## 1. `AdminUsuariosController.php`

### Propósito
Permite al administrador gestionar los usuarios registrados en el sistema, crear nuevos administradores, clientes o repartidores, y modificar los roles asignados.

---

## 2. Especialización de Tablas por Rol

En la arquitectura de base de datos de **Los Alpes**, cada usuario registrado en `persona` puede tener un registro de especialización correspondiente según su rol:
* `idrol = 1 (administrador)` ➔ Registro en la tabla `administrador (idadministrador, idpersona)`.
* `idrol = 2 (cliente)` ➔ Registro en la tabla `cliente (idcliente, idpersona)`.
* `idrol = 3 (domiciliario)` ➔ Registro en la tabla `domiciliario (iddomiciliario, idpersona)`.

---

## 3. Funcionalidades del Módulo

### A. Cambio Dinámico de Rol (`cambiarRol`)
Cuando un administrador cambia el rol de un usuario:
1. Actualiza el campo `idrol` en la tabla `persona`.
2. Sincroniza automáticamente las tablas hijas (`administrador`, `cliente`, `domiciliario`):
   * Si el usuario se convierte en `domiciliario` y no tiene registro en `domiciliario`, lo inserta.
   * Si pasa a `administrador`, asegura su registro en `administrador`.
   * Si pasa a `cliente`, asegura su registro en `cliente`.

### B. Creación Directa de Usuarios (`crear`)
* Valida unicidad de correo electrónico y longitud de contraseña mínima (6 caracteres).
* Encripta la contraseña con `Hash::make()`.
* Inserta el registro en `persona` y su tabla de rol especializada.

### C. Eliminación de Usuarios (`eliminar`)
* Previene que el administrador elimine su propia cuenta activa.
* Elimina los registros asociados en orden referencial para mantener la integridad.
