<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Show the application login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'correo'     => 'required|email',
            'contrasena' => 'required',
        ], [
            'correo.required'     => 'El correo es obligatorio.',
            'correo.email'        => 'El correo debe tener un formato válido.',
            'contrasena.required' => 'La contraseña es obligatoria.',
        ]);

        $correo     = trim($request->input('correo'));
        $contrasena = $request->input('contrasena');

        $persona = Persona::with('rol')->where('correo', $correo)->first();

        if (!$persona) {
            return back()->withInput($request->only('correo'))->with('error', 'Correo o contraseña incorrectos. Intenta de nuevo.');
        }

        $contrasenaValida = false;

        // Verificar si la contraseña almacenada es un hash bcrypt
        if (password_get_info($persona->contrasena)['algo'] !== null) {
            $contrasenaValida = Hash::check($contrasena, $persona->contrasena);
        } else {
            // Verificación legacy en texto plano y migración automática a bcrypt al iniciar sesión
            $contrasenaValida = ($contrasena === $persona->contrasena);
            if ($contrasenaValida) {
                $persona->contrasena = Hash::make($contrasena);
                $persona->save();
            }
        }

        if (!$contrasenaValida) {
            return back()->withInput($request->only('correo'))->with('error', 'Correo o contraseña incorrectos. Intenta de nuevo.');
        }

        Auth::login($persona, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectUser($persona);
    }

    /**
     * Show the application registration form.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }

        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $nombre      = trim($request->input('nombre', ''));
        $correo      = trim($request->input('correo', ''));
        $contrasena  = $request->input('contrasena', '');
        $contrasena2 = $request->input('contrasena2', '');

        if (empty($nombre) || empty($correo) || empty($contrasena)) {
            return back()->withInput()->with('error', 'Todos los campos son obligatorios.');
        }

        if (strlen($contrasena) < 6) {
            return back()->withInput()->with('error', 'La contraseña debe tener al menos 6 caracteres.');
        }

        if ($contrasena !== $contrasena2) {
            return back()->withInput()->with('error', 'Las contraseñas no coinciden.');
        }

        $existe = DB::table('persona')->where('correo', $correo)->first();
        if ($existe) {
            return back()->withInput()->with('error', 'El correo ya está registrado.');
        }

        $rolCliente = DB::table('roles')->where('nombre', 'cliente')->first();
        $idrol = $rolCliente ? $rolCliente->idrol : 2;

        try {
            $idpersona = DB::table('persona')->insertGetId([
                'nombre'     => $nombre,
                'correo'     => $correo,
                'contrasena' => Hash::make($contrasena),
                'idrol'      => $idrol,
            ]);

            DB::table('cliente')->insert(['idpersona' => $idpersona]);

            $personaNueva = Persona::find($idpersona);
            Auth::login($personaNueva);

            return redirect()->route('cliente.dashboard')->with('success', 'Cuenta creada con éxito. ¡Bienvenido!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al registrar la cuenta: ' . $e->getMessage());
        }
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect authenticated user according to their role.
     */
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
}
