<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminUsuariosController extends Controller
{
    /**
     * Display admin users and roles management dashboard.
     */
    public function index(Request $request)
    {
        $usuariosList = DB::table('persona as p')
            ->join('roles as r', 'p.idrol', '=', 'r.idrol')
            ->select('p.idpersona', 'p.nombre', 'p.correo', 'p.idrol', 'r.nombre as rol')
            ->orderBy('p.idpersona', 'desc')
            ->get();

        $usuarios = $usuariosList->map(fn($item) => (array)$item)->toArray();

        $rolesList = DB::table('roles')->orderBy('nombre', 'asc')->get();
        $roles = $rolesList->map(fn($item) => (array)$item)->toArray();

        return view('dashboard.admin.usuarios', compact('usuarios', 'roles'));
    }

    /**
     * Change role for a user and ensure profile record exists.
     */
    public function cambiarRol(Request $request)
    {
        $idpersona = (int)$request->input('idpersona', 0);
        $idrol     = (int)$request->input('idrol', 0);

        if ($idpersona <= 0 || $idrol <= 0) {
            return redirect()->route('admin.usuarios.index')->with('mensaje', [
                'tipo'  => 'err',
                'texto' => 'Datos inválidos.'
            ]);
        }

        try {
            $rol = DB::table('roles')->where('idrol', $idrol)->first();
            if (!$rol) {
                return redirect()->route('admin.usuarios.index')->with('mensaje', [
                    'tipo'  => 'err',
                    'texto' => 'El rol seleccionado no existe.'
                ]);
            }

            $rolNuevo = strtolower($rol->nombre);

            DB::table('persona')
                ->where('idpersona', $idpersona)
                ->update(['idrol' => $idrol]);

            // Crear registro en la tabla de perfil correspondiente si no existe
            if ($rolNuevo === 'domiciliario') {
                $existe = DB::table('domiciliario')->where('idpersona', $idpersona)->first();
                if (!$existe) {
                    DB::table('domiciliario')->insert(['idpersona' => $idpersona]);
                }
            } elseif ($rolNuevo === 'administrador') {
                $existe = DB::table('administrador')->where('idpersona', $idpersona)->first();
                if (!$existe) {
                    DB::table('administrador')->insert(['idpersona' => $idpersona]);
                }
            } elseif ($rolNuevo === 'cliente') {
                $existe = DB::table('cliente')->where('idpersona', $idpersona)->first();
                if (!$existe) {
                    DB::table('cliente')->insert(['idpersona' => $idpersona]);
                }
            }

            return redirect()->route('admin.usuarios.index')->with('mensaje', [
                'tipo'  => 'ok',
                'texto' => 'Rol actualizado correctamente.'
            ]);
        } catch (\Exception $e) {
            Log::error("[AdminUsuarios] Error al cambiar rol: " . $e->getMessage());
            return redirect()->route('admin.usuarios.index')->with('mensaje', [
                'tipo'  => 'err',
                'texto' => 'Error al actualizar el rol.'
            ]);
        }
    }

    /**
     * Create new user and profile record.
     */
    public function crear(Request $request)
    {
        $nombre     = trim($request->input('nombre', ''));
        $correo     = trim($request->input('correo', ''));
        $contrasena = $request->input('contrasena', '');
        $rolNombre  = strtolower(trim($request->input('rol', 'cliente')));

        if (empty($nombre) || empty($correo) || empty($contrasena)) {
            return redirect()->route('admin.usuarios.index')->with('mensaje', [
                'tipo'  => 'err',
                'texto' => 'Todos los campos son obligatorios.'
            ]);
        }

        if (strlen($contrasena) < 6) {
            return redirect()->route('admin.usuarios.index')->with('mensaje', [
                'tipo'  => 'err',
                'texto' => 'La contraseña debe tener al menos 6 caracteres.'
            ]);
        }

        $existeCorreo = DB::table('persona')->where('correo', $correo)->first();
        if ($existeCorreo) {
            return redirect()->route('admin.usuarios.index')->with('mensaje', [
                'tipo'  => 'err',
                'texto' => 'El correo ya está registrado o el rol no existe.'
            ]);
        }

        $rolObj = DB::table('roles')->where('nombre', $rolNombre)->first();
        if (!$rolObj) {
            return redirect()->route('admin.usuarios.index')->with('mensaje', [
                'tipo'  => 'err',
                'texto' => 'El correo ya está registrado o el rol no existe.'
            ]);
        }

        try {
            $idpersonaNueva = DB::table('persona')->insertGetId([
                'nombre'     => $nombre,
                'correo'     => $correo,
                'contrasena' => Hash::make($contrasena),
                'idrol'      => $rolObj->idrol,
            ]);

            if ($idpersonaNueva > 0) {
                if ($rolNombre === 'domiciliario') {
                    DB::table('domiciliario')->insert(['idpersona' => $idpersonaNueva]);
                } elseif ($rolNombre === 'administrador') {
                    DB::table('administrador')->insert(['idpersona' => $idpersonaNueva]);
                } elseif ($rolNombre === 'cliente') {
                    DB::table('cliente')->insert(['idpersona' => $idpersonaNueva]);
                }
            }

            return redirect()->route('admin.usuarios.index')->with('mensaje', [
                'tipo'  => 'ok',
                'texto' => 'Usuario creado correctamente.'
            ]);
        } catch (\Exception $e) {
            Log::error("[AdminUsuarios] Error al crear usuario: " . $e->getMessage());
            return redirect()->route('admin.usuarios.index')->with('mensaje', [
                'tipo'  => 'err',
                'texto' => 'Error al crear el usuario.'
            ]);
        }
    }

    /**
     * Delete user using transaction.
     */
    public function eliminar(Request $request, $id)
    {
        $idpersona = (int)$id;

        if ($idpersona > 0) {
            try {
                DB::transaction(function () use ($idpersona) {
                    DB::table('cliente')->where('idpersona', $idpersona)->delete();
                    DB::table('domiciliario')->where('idpersona', $idpersona)->delete();
                    DB::table('administrador')->where('idpersona', $idpersona)->delete();
                    DB::table('persona')->where('idpersona', $idpersona)->delete();
                });
                return redirect()->route('admin.usuarios.index');
            } catch (\Exception $e) {
                Log::warning("[AdminUsuarios] No se pudo eliminar usuario {$idpersona}: " . $e->getMessage());
                return redirect()->route('admin.usuarios.index')->with('mensaje', [
                    'tipo'  => 'err',
                    'texto' => 'Error al eliminar: ' . $e->getMessage()
                ]);
            }
        }

        return redirect()->route('admin.usuarios.index');
    }
}
