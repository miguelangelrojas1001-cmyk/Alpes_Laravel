# ⚙️ 18. Configuraciones y Variables de Entorno

## 1. Archivo `.env`
El archivo `.env` almacena los parámetros de configuración específicos del entorno local o de producción.

```ini
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:tn3VyL5vGP0OzAzgFlGwDUheCvp5yoyeDoAQy5GtoQ8=
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=America/Bogota

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=en_US

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3320
DB_DATABASE=bdalpes
DB_USERNAME=root
DB_PASSWORD=
DB_TIMEZONE=-05:00

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

---

## 2. Configuración de Zona Horaria (Colombia UTC-5)

Para asegurar que todas las fechas y horas registradas en pedidos, facturas, movimientos de inventario y logs coincidan exactamente con la hora oficial de Colombia:
1. **`config/app.php`:**
   ```php
   'timezone' => env('APP_TIMEZONE', 'America/Bogota'),
   ```
2. **`config/database.php`:**
   ```php
   'mysql' => [
       'driver' => 'mysql',
       'timezone' => env('DB_TIMEZONE', '-05:00'),
       // ...
   ],
   ```
3. **`AppServiceProvider.php`:**
   ```php
   public function boot(): void
   {
       date_default_timezone_set(config('app.timezone', 'America/Bogota'));
   }
   ```

---

## 3. Configuración de Sesiones y Almacenamiento
* **Driver de Sesión:** `file` con un tiempo de expiración de 120 minutos (`SESSION_LIFETIME=120`).
* **Disco Local:** `public_path('img')` utilizado para el almacenamiento y entrega directa de imágenes de productos y logos sin requerir enlaces simbólicos complejos.
