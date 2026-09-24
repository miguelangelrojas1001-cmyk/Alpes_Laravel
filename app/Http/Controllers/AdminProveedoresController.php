<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminProveedoresController extends Controller
{
    /**
     * Display admin suppliers management dashboard.
     */
    public function index(Request $request)
    {
        $proveedorEditar = null;

        if ($request->has('editar')) {
            $idEditar = (int)$request->get('editar');
            $proveedorEditar = DB::table('proveedor')->where('idproveedor', $idEditar)->first();
            if ($proveedorEditar) {
                $proveedorEditar = (array)$proveedorEditar;
            }
        }

        $proveedoresList = DB::table('proveedor')->orderBy('nombre', 'asc')->get();
        $proveedores = $proveedoresList->map(fn($item) => (array)$item)->toArray();

        return view('dashboard.admin.proveedores', compact('proveedores', 'proveedorEditar'));
    }

    /**
     * Create or update supplier details.
     */
    public function guardar(Request $request)
    {
        $idproveedor = (int)$request->input('idproveedor', 0);
        $nombre      = trim($request->input('nombre', ''));
        $telefono    = trim($request->input('telefono', ''));
        $correo      = trim($request->input('correo', ''));

        if (empty($nombre)) {
            return redirect()->route('admin.proveedores.index');
        }

        try {
            if ($idproveedor > 0) {
                DB::table('proveedor')
                    ->where('idproveedor', $idproveedor)
                    ->update([
                        'nombre'   => $nombre,
                        'telefono' => $telefono,
                        'correo'   => $correo,
                    ]);
                $mensaje = 'Proveedor actualizado.';
            } else {
                DB::table('proveedor')->insert([
                    'nombre'   => $nombre,
                    'telefono' => $telefono,
                    'correo'   => $correo,
                ]);
                $mensaje = 'Proveedor agregado.';
            }

            return redirect()->route('admin.proveedores.index')->with('mensaje', $mensaje);
        } catch (\Exception $e) {
            Log::error("[AdminProveedores] Error al guardar proveedor: " . $e->getMessage());
            return redirect()->route('admin.proveedores.index')->with('mensaje', 'Error al guardar el proveedor.');
        }
    }

    /**
     * Delete supplier.
     */
    public function eliminar(Request $request, $id)
    {
        $idproveedor = (int)$id;

        if ($idproveedor > 0) {
            try {
                DB::table('proveedor')->where('idproveedor', $idproveedor)->delete();
                return redirect()->route('admin.proveedores.index');
            } catch (\Exception $e) {
                Log::warning("[AdminProveedores] No se pudo eliminar proveedor {$idproveedor}: " . $e->getMessage());
                return redirect()->route('admin.proveedores.index')->with('mensaje', 'Error al eliminar el proveedor.');
            }
        }

        return redirect()->route('admin.proveedores.index');
    }
}
