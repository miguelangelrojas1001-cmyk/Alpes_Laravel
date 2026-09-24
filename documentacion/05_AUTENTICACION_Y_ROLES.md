# 🔐 05. Autenticación y Control de Roles

## 1. Sistema de Autenticación
La autenticación en **Alpes_Laravel** se gestiona a través del controlador [`LoginController.php`](file:///c:/laragon/www/Alpes_Laravel/app/Http/Controllers/Auth/LoginController.php) utilizando la tabla `persona`.

---

## 2. Hashing de Contraseñas y Compatibilidad Legacy
Para garantizar la seguridad y permitir la migración progresiva de contraseñas de sistemas antiguos, se implementó un flujo inteligente de verificación en `login()`:

```php
// Si la contraseña almacenada ya es un hash de Bcrypt:
if (password_get_info($persona->contrasena)['algo'] !== null) {
    $contrasenaValida = Hash::check($contrasena, $persona->contrasena);
} else {
    // Si era texto plano heredado, valida y migra automáticamente a Bcrypt:
    $contrasenaValida = ($contrasena === $persona->contrasena);
    if ($contrasenaValida) {
        $persona->contrasena = Hash::make($contrasena);
        $persona->save();
    }
}
```

---

## 3. Redirección Inteligente por Rol
Al autenticarse exitosamente, el método `redirectUser()` dirige al usuario según su rol correspondiente:

```php
protected function redirectUser(Persona $user)
{
    $rol = strtolower($user->rol_nombre ?? '');

    if ($rol === 'administrador') {
        return redirect()->route('admin.dashboard');
    } elseif ($rol === 'domiciliario') {
        return redirect()->route('domiciliario.dashboard');
    } else {
        return redirect()->route('cliente.dashboard');
    }
}
```

---

## 4. Middleware de Protección por Rol (`CheckRole.php`)

Ubicado en `app/Http/Middleware/CheckRole.php`, este middleware intercepta peticiones a rutas restringidas:

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = strtolower(Auth::user()->rol_nombre ?? '');
        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $allowedRoles, true)) {
            // Si el rol no está autorizado, se redirige a su panel natural
            if ($userRole === 'administrador') {
                return redirect()->route('admin.dashboard');
            } elseif ($userRole === 'domiciliario') {
                return redirect()->route('domiciliario.dashboard');
            } else {
                return redirect()->route('cliente.dashboard');
            }
        }

        return $next($request);
    }
}
```

### Alias del Middleware
En `bootstrap/app.php` se registra el alias:
```php
$middleware->alias([
    'role' => \App\Http\Middleware\CheckRole::class,
]);
```
Permitiendo en las rutas escribir de forma limpia: `middleware(['auth', 'role:administrador'])`.
