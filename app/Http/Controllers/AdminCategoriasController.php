<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminCategoriasController extends Controller
{
    /**
     * Display admin categories management dashboard.
     */
    public function index(Request $request)
    {
        $categoriasList = DB::table('catalogo as c')
            ->leftJoin('producto as p', 'c.idcatalogo', '=', 'p.idcatalogo')
            ->select('c.*', DB::raw('COUNT(p.idproducto) as total_productos'))
            ->groupBy('c.idcatalogo')
            ->orderBy('c.nombre', 'asc')
            ->get();

        $categorias = $categoriasList->map(fn($item) => (array)$item)->toArray();

        return view('dashboard.admin.categorias', compact('categorias'));
    }

    /**
     * Create new category.
     */
    public function guardar(Request $request)
    {
        $nombre      = trim($request->input('nombre', ''));
        $descripcion = trim($request->input('descripcion', ''));

        if (empty($nombre)) {
            return redirect()->route('admin.categorias.index');
        }

        try {
            DB::table('catalogo')->insert([
                'nombre'      => $nombre,
                'descripcion' => $descripcion,
                'estado'      => 'activo',
                'imagen'      => '',
            ]);

            return redirect()->route('admin.categorias.index')->with('mensaje', 'Categoría agregada.');
        } catch (\Exception $e) {
            Log::error("[AdminCategorias] Error al guardar categoría: " . $e->getMessage());
            return redirect()->route('admin.categorias.index')->with('mensaje', 'Error al agregar la categoría.');
        }
    }

    /**
     * Toggle category active/inactive status.
     */
    public function toggle(Request $request, $id)
    {
        $idcatalogo = (int)$id;

        if ($idcatalogo > 0) {
            try {
                DB::table('catalogo')
                    ->where('idcatalogo', $idcatalogo)
                    ->update([
                        'estado' => DB::raw("CASE WHEN estado = 'activo' THEN 'inactivo' ELSE 'activo' END")
                    ]);
            } catch (\Exception $e) {
                Log::error("[AdminCategorias] Error al cambiar estado de categoría {$idcatalogo}: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.categorias.index');
    }

    /**
     * Delete category.
     */
    public function eliminar(Request $request, $id)
    {
        $idcatalogo = (int)$id;

        if ($idcatalogo > 0) {
            try {
                DB::table('catalogo')->where('idcatalogo', $idcatalogo)->delete();
                return redirect()->route('admin.categorias.index');
            } catch (\Exception $e) {
                Log::warning("[AdminCategorias] No se pudo eliminar categoría {$idcatalogo}: " . $e->getMessage());
                return redirect()->route('admin.categorias.index')->with('mensaje', 'Error al eliminar la categoría.');
            }
        }

        return redirect()->route('admin.categorias.index');
    }
}
